<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\LoaTemplate;
use App\Models\Paper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class AcceptanceLetterManager extends Component
{
    use WithPagination, WithFileUploads;

    public int    $conferenceId = 0;
    public string $search       = '';
    public string $statusFilter = '';

    // Template Modal
    public bool $showTemplateModal = false;
    public ?int $editingTemplateId = null;
    public string $templateName = '';
    public string $templateType = 'paper';
    public string $templateHtml = '';
    public string $templateOrientation = 'portrait';
    public string $templatePaperSize = 'a4';
    public bool $templateIsDefault = false;
    public string $signatureName = '';
    public string $signaturePosition = '';
    public string $signature2Name = '';
    public string $signature2Position = '';
    
    // File uploads
    public $signatureFile;
    public $signature2File;
    public $stampFile;
    public $logoFile;
    public $letterheadFile;
    
    // Existing images
    public ?string $existingSignature = null;
    public ?string $existingSignature2 = null;
    public ?string $existingStamp = null;
    public ?string $existingLogo = null;
    public ?string $existingLetterhead = null;

    // Preview
    public bool $showPreviewModal = false;
    public string $previewHtml = '';

    public function mount(): void
    {
        // Auto-select conference from query parameter
        if (request()->has('conference')) {
            $this->conferenceId = (int) request('conference');
        }
    }

    public function updatedConferenceId(): void { $this->resetPage(); }
    public function updatedSearch(): void       { $this->resetPage(); }

    public function generateLetter(int $paperId): void
    {
        $paper = Paper::with(['user', 'conference'])->findOrFail($paperId);
        $conf  = $paper->conference;

        // Try to find LOA template
        $template = LoaTemplate::active()
            ->forType('paper')
            ->where(function ($q) use ($conf) {
                $q->where('conference_id', $conf?->id)->orWhereNull('conference_id');
            })
            ->orderByRaw('CASE WHEN conference_id IS NOT NULL THEN 0 ELSE 1 END')
            ->orderByDesc('is_default')
            ->first();

        if ($template) {
            // Use template-based rendering
            $html = $template->renderPaper($paper);
            $pdf  = Pdf::loadHTML($html)->setPaper($template->paper_size, $template->orientation);
        } else {
            // Fallback to old view-based rendering
            $data = [
                'paper'      => $paper,
                'conference' => $conf,
                'issuedAt'   => now()->format('d F Y'),
                'siteName'   => \App\Models\Setting::get('site_name', config('app.name')),
                'siteAddress'=> \App\Models\Setting::get('address', ''),
            ];
            $pdf  = Pdf::loadView('pdfs.acceptance-letter', $data)->setPaper('A4');
        }
        
        $path = 'acceptance-letters/letter-' . $paper->id . '-' . now()->format('YmdHis') . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        $paper->update([
            'acceptance_letter_path'    => $path,
            'acceptance_letter_sent_at' => now(),
        ]);

        // Notify author
        \App\Models\Notification::create([
            'user_id' => $paper->user_id,
            'type'    => 'acceptance_letter',
            'title'   => 'Acceptance Letter Siap',
            'message' => 'Acceptance letter untuk paper "' . $paper->title . '" telah diterbitkan.',
            'data'    => ['paper_id' => $paper->id],
        ]);

        session()->flash('success', 'Acceptance letter berhasil digenerate!');
    }

    public function generateBulk(): void
    {
        if (!$this->conferenceId) {
            session()->flash('error', 'Pilih konferensi terlebih dahulu.');
            return;
        }

        $papers = Paper::where('conference_id', $this->conferenceId)
            ->whereIn('status', ['accepted', 'payment_verified', 'deliverables_pending', 'completed'])
            ->whereNull('acceptance_letter_path')
            ->with(['user', 'conference'])
            ->get();

        $count = 0;
        foreach ($papers as $paper) {
            $this->generateLetter($paper->id);
            $count++;
        }

        session()->flash('success', "$count acceptance letter berhasil digenerate sekaligus!");
    }

    public function deleteLetter(int $paperId): void
    {
        $paper = Paper::findOrFail($paperId);
        if ($paper->acceptance_letter_path) {
            Storage::disk('public')->delete($paper->acceptance_letter_path);
        }
        $paper->update(['acceptance_letter_path' => null, 'acceptance_letter_sent_at' => null]);
        session()->flash('success', 'Acceptance letter dihapus.');
    }

    // ─── Template Management ────────────────────────────────────────────────────

    public function openTemplateModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->resetTemplateForm();

        if ($id) {
            $template = LoaTemplate::findOrFail($id);
            $this->editingTemplateId = $template->id;
            $this->templateName = $template->name;
            $this->templateType = $template->type;
            $this->templateHtml = $template->html_template;
            $this->templateOrientation = $template->orientation;
            $this->templatePaperSize = $template->paper_size;
            $this->templateIsDefault = $template->is_default;
            $this->signatureName = $template->signature_name ?? '';
            $this->signaturePosition = $template->signature_position ?? '';
            $this->signature2Name = $template->signature2_name ?? '';
            $this->signature2Position = $template->signature2_position ?? '';
            $this->existingSignature = $template->signature_image;
            $this->existingSignature2 = $template->signature2_image;
            $this->existingStamp = $template->stamp_image;
            $this->existingLogo = $template->logo_image;
            $this->existingLetterhead = $template->letterhead_image;
        }

        $this->showTemplateModal = true;
    }

    public function closeTemplateModal(): void
    {
        $this->showTemplateModal = false;
        $this->resetTemplateForm();
    }

    public function resetTemplateForm(): void
    {
        $this->editingTemplateId = null;
        $this->templateName = '';
        $this->templateType = 'paper';
        $this->templateHtml = '';
        $this->templateOrientation = 'portrait';
        $this->templatePaperSize = 'a4';
        $this->templateIsDefault = false;
        $this->signatureName = '';
        $this->signaturePosition = '';
        $this->signature2Name = '';
        $this->signature2Position = '';
        $this->signatureFile = null;
        $this->signature2File = null;
        $this->stampFile = null;
        $this->logoFile = null;
        $this->letterheadFile = null;
        $this->existingSignature = null;
        $this->existingSignature2 = null;
        $this->existingStamp = null;
        $this->existingLogo = null;
        $this->existingLetterhead = null;
    }

    public function usePredefinedTemplate(string $key): void
    {
        $templates = LoaTemplate::getPredefinedTemplates();
        if (isset($templates[$key])) {
            $this->templateHtml = $templates[$key]['html'];
            $this->templateName = $this->templateName ?: $templates[$key]['name'];
        }
    }

    public function createFromPredefined(string $key): void
    {
        $this->resetTemplateForm();
        $this->usePredefinedTemplate($key);
        $this->showTemplateModal = true;
    }

    public function saveTemplate(): void
    {
        $this->validate([
            'templateName' => 'required|string|max:255',
            'templateType' => 'required|in:all,paper,abstract',
            'templateHtml' => 'required|string',
            'templateOrientation' => 'required|in:portrait,landscape',
            'templatePaperSize' => 'required|in:a4,letter,legal',
            'signatureFile' => 'nullable|image|max:2048',
            'signature2File' => 'nullable|image|max:2048',
            'stampFile' => 'nullable|image|max:2048',
            'logoFile' => 'nullable|image|max:2048',
            'letterheadFile' => 'nullable|image|max:5120',
        ]);

        $data = [
            'conference_id' => $this->conferenceId ?: null,
            'name' => $this->templateName,
            'type' => $this->templateType,
            'html_template' => $this->templateHtml,
            'orientation' => $this->templateOrientation,
            'paper_size' => $this->templatePaperSize,
            'is_default' => $this->templateIsDefault,
            'signature_name' => $this->signatureName ?: null,
            'signature_position' => $this->signaturePosition ?: null,
            'signature2_name' => $this->signature2Name ?: null,
            'signature2_position' => $this->signature2Position ?: null,
        ];

        // Handle file uploads
        $fileFields = [
            'signatureFile' => 'signature_image',
            'signature2File' => 'signature2_image',
            'stampFile' => 'stamp_image',
            'logoFile' => 'logo_image',
            'letterheadFile' => 'letterhead_image',
        ];

        foreach ($fileFields as $uploadField => $dbField) {
            if ($this->{$uploadField}) {
                $data[$dbField] = $this->{$uploadField}->store('loa-templates', 'public');
            }
        }

        // If setting as default, unset other defaults
        if ($this->templateIsDefault) {
            LoaTemplate::where('conference_id', $this->conferenceId ?: null)
                ->where('type', $this->templateType)
                ->update(['is_default' => false]);
        }

        if ($this->editingTemplateId) {
            LoaTemplate::findOrFail($this->editingTemplateId)->update($data);
            session()->flash('success', 'Template LOA berhasil diperbarui.');
        } else {
            LoaTemplate::create($data);
            session()->flash('success', 'Template LOA berhasil dibuat.');
        }

        $this->closeTemplateModal();
    }

    public function deleteTemplate(int $id): void
    {
        $template = LoaTemplate::findOrFail($id);
        
        // Delete associated files
        $files = ['signature_image', 'signature2_image', 'stamp_image', 'logo_image', 'letterhead_image', 'background_image'];
        foreach ($files as $file) {
            if ($template->{$file}) {
                Storage::disk('public')->delete($template->{$file});
            }
        }
        
        $template->delete();
        session()->flash('success', 'Template LOA berhasil dihapus.');
    }

    public function setDefaultTemplate(int $id): void
    {
        $template = LoaTemplate::findOrFail($id);
        
        LoaTemplate::where('conference_id', $template->conference_id)
            ->where('type', $template->type)
            ->update(['is_default' => false]);
        
        $template->update(['is_default' => true]);
        session()->flash('success', 'Template berhasil dijadikan default.');
    }

    public function previewTemplate(?int $id = null): void
    {
        if ($id) {
            $template = LoaTemplate::findOrFail($id);
            $html = $template->html_template;
        } else {
            $html = $this->templateHtml;
        }

        // Replace with dummy data
        $replacements = [
            '{{judul}}' => 'Judul Paper Contoh untuk Preview Template',
            '{{title}}' => 'Sample Paper Title for Template Preview',
            '{{nama}}' => 'Dr. Nama Penulis Contoh, M.Pd.',
            '{{author}}' => 'Dr. Sample Author Name, M.Pd.',
            '{{email}}' => 'author@example.com',
            '{{institusi}}' => 'Universitas Contoh Indonesia',
            '{{institution}}' => 'Sample University Indonesia',
            '{{konferensi}}' => 'Nama Konferensi Contoh 2026',
            '{{conference}}' => 'Sample Conference 2026',
            '{{nomor_loa}}' => 'LOA-00001',
            '{{loa_number}}' => 'LOA-00001',
            '{{tanggal}}' => now()->isoFormat('D MMMM Y'),
            '{{date}}' => now()->format('F d, Y'),
            '{{tahun}}' => date('Y'),
            '{{year}}' => date('Y'),
            '{{tanggal_konferensi}}' => now()->addMonths(3)->isoFormat('D MMMM Y'),
            '{{conference_date}}' => now()->addMonths(3)->format('F d, Y'),
            '{{tempat}}' => 'Jakarta, Indonesia',
            '{{location}}' => 'Jakarta, Indonesia',
            '{{topic}}' => 'Topik: Pendidikan dan Teknologi',
            '{{topik}}' => 'Topik: Pendidikan dan Teknologi',
            '{{ttd_nama}}' => $this->signatureName ?: 'Prof. Dr. Nama Ketua Panitia',
            '{{signature_name}}' => $this->signatureName ?: 'Prof. Dr. Committee Chair',
            '{{ttd_jabatan}}' => $this->signaturePosition ?: 'Ketua Panitia',
            '{{signature_position}}' => $this->signaturePosition ?: 'Committee Chair',
            '{{ttd2_nama}}' => $this->signature2Name ?: '',
            '{{signature2_name}}' => $this->signature2Name ?: '',
            '{{ttd2_jabatan}}' => $this->signature2Position ?: '',
            '{{signature2_position}}' => $this->signature2Position ?: '',
        ];
        
        $html = str_replace(array_keys($replacements), array_values($replacements), $html);
        
        // Process images
        if ($id) {
            $template = LoaTemplate::find($id);
            $html = $this->processPreviewImages($html, $template);
        } else {
            // Use placeholder for images in live preview
            $html = str_replace(['{{ttd}}', '{{signature}}'], '<span style="color:#999;font-size:10px;">[Tanda Tangan 1]</span>', $html);
            $html = str_replace(['{{ttd2}}', '{{signature2}}'], '<span style="color:#999;font-size:10px;">[Tanda Tangan 2]</span>', $html);
            $html = str_replace('{{logo}}', '<span style="color:#999;font-size:10px;">[Logo]</span>', $html);
            $html = str_replace(['{{kop_surat}}', '{{letterhead}}'], '<div style="text-align:center;color:#999;font-size:10px;padding:20px;border:1px dashed #ccc;">[Kop Surat]</div>', $html);
            $html = str_replace(['{{stempel}}', '{{stamp}}'], '', $html);
        }

        // Generate QR Code
        $qrCodeHtml = $this->generatePreviewQrCode('LOA-00001');
        $html = str_replace(['{{qrcode}}', '{{qr_code}}'], $qrCodeHtml, $html);

        $this->previewHtml = $html;
        $this->showPreviewModal = true;
    }

    private function processPreviewImages(string $html, ?LoaTemplate $template): string
    {
        if (!$template) return $html;

        if ($template->signature_image) {
            $html = str_replace(['{{ttd}}', '{{signature}}'], $this->getImageTag($template->signature_image, 60), $html);
        } else {
            $html = str_replace(['{{ttd}}', '{{signature}}'], '<span style="color:#999;font-size:10px;">[Tanda Tangan]</span>', $html);
        }

        if ($template->signature2_image) {
            $html = str_replace(['{{ttd2}}', '{{signature2}}'], $this->getImageTag($template->signature2_image, 60), $html);
        } else {
            $html = str_replace(['{{ttd2}}', '{{signature2}}'], '', $html);
        }

        if ($template->logo_image) {
            $html = str_replace('{{logo}}', $this->getImageTag($template->logo_image, 80), $html);
        } else {
            $html = str_replace('{{logo}}', '<span style="color:#999;font-size:10px;">[Logo]</span>', $html);
        }

        if ($template->letterhead_image) {
            $html = str_replace(['{{kop_surat}}', '{{letterhead}}'], $this->getImageTag($template->letterhead_image, 120), $html);
        } else {
            $html = str_replace(['{{kop_surat}}', '{{letterhead}}'], '', $html);
        }

        if ($template->stamp_image) {
            $html = str_replace(['{{stempel}}', '{{stamp}}'], $this->getImageTag($template->stamp_image, 80), $html);
        } else {
            $html = str_replace(['{{stempel}}', '{{stamp}}'], '', $html);
        }

        return $html;
    }

    private function getImageTag(string $imagePath, int $maxHeight = 80): string
    {
        $fullPath = storage_path('app/public/' . $imagePath);
        
        if (!file_exists($fullPath)) {
            return '';
        }

        $imageData = base64_encode(file_get_contents($fullPath));
        $mimeType = mime_content_type($fullPath);
        
        return "<img src=\"data:{$mimeType};base64,{$imageData}\" style=\"max-height:{$maxHeight}px;\">";
    }

    private function generatePreviewQrCode(string $loaNumber): string
    {
        $verifyUrl = url('/verify-loa/' . $loaNumber);
        
        try {
            $qrCode = new QrCode(
                data: $verifyUrl,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Medium,
                size: 100,
                margin: 3,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                foregroundColor: new Color(0, 0, 0),
                backgroundColor: new Color(255, 255, 255)
            );

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            
            $qrDataUri = $result->getDataUri();
            
            return "<img src=\"{$qrDataUri}\" style=\"width:70px; height:70px;\">";
        } catch (\Exception $e) {
            return '<span style="color:#999;font-size:10px;padding:10px;border:1px dashed #ccc;display:inline-block;">[QR Code]</span>';
        }
    }

    public function closePreviewModal(): void
    {
        $this->showPreviewModal = false;
        $this->previewHtml = '';
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        $papers      = collect();

        if ($this->conferenceId) {
            $papers = Paper::where('conference_id', $this->conferenceId)
                ->whereIn('status', ['accepted', 'payment_verified', 'deliverables_pending', 'completed'])
                ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")))
                ->when($this->statusFilter === 'issued', fn($q) => $q->whereNotNull('acceptance_letter_path'))
                ->when($this->statusFilter === 'not_issued', fn($q) => $q->whereNull('acceptance_letter_path'))
                ->with('user:id,name,email')
                ->orderBy('title')
                ->paginate(20);
        }

        // Get templates for current conference
        $templates = LoaTemplate::query()
            ->when($this->conferenceId, fn($q) => $q->where(function($q2) {
                $q2->where('conference_id', $this->conferenceId)->orWhereNull('conference_id');
            }))
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        // Get active template
        $activeTemplate = LoaTemplate::active()
            ->forType('paper')
            ->when($this->conferenceId, fn($q) => $q->where(function($q2) {
                $q2->where('conference_id', $this->conferenceId)->orWhereNull('conference_id');
            }))
            ->orderByRaw('CASE WHEN conference_id IS NOT NULL THEN 0 ELSE 1 END')
            ->orderByDesc('is_default')
            ->first();

        $predefinedTemplates = LoaTemplate::getPredefinedTemplates();

        return view('livewire.admin.acceptance-letter-manager', compact('conferences', 'papers', 'templates', 'activeTemplate', 'predefinedTemplates'))
            ->layout('layouts.app', ['title' => 'Acceptance Letter']);
    }
}
