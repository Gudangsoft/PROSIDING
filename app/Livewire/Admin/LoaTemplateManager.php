<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\LoaTemplate;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class LoaTemplateManager extends Component
{
    use WithPagination, WithFileUploads;

    // Filters
    public int $conferenceId = 0;
    public string $search = '';

    // Modal states
    public bool $showModal = false;
    public bool $showPreviewModal = false;
    public ?int $editingId = null;

    // Form fields
    public string $name = '';
    public string $type = 'all';
    public string $htmlTemplate = '';
    public string $orientation = 'portrait';
    public string $paperSize = 'a4';
    public bool $isDefault = false;

    // Signature fields
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
    public $backgroundFile;

    // Existing images
    public ?string $existingSignature = null;
    public ?string $existingSignature2 = null;
    public ?string $existingStamp = null;
    public ?string $existingLogo = null;
    public ?string $existingLetterhead = null;
    public ?string $existingBackground = null;

    // Preview
    public string $previewHtml = '';

    protected $queryString = ['conferenceId', 'search'];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:all,paper,abstract',
            'htmlTemplate' => 'required|string',
            'orientation' => 'required|in:portrait,landscape',
            'paperSize' => 'required|in:a4,letter,legal',
            'signatureFile' => 'nullable|image|max:2048',
            'signature2File' => 'nullable|image|max:2048',
            'stampFile' => 'nullable|image|max:2048',
            'logoFile' => 'nullable|image|max:2048',
            'letterheadFile' => 'nullable|image|max:5120',
            'backgroundFile' => 'nullable|image|max:5120',
            'signatureName' => 'nullable|string|max:255',
            'signaturePosition' => 'nullable|string|max:255',
            'signature2Name' => 'nullable|string|max:255',
            'signature2Position' => 'nullable|string|max:255',
        ];
    }

    public function mount(): void
    {
        $this->conferenceId = Conference::where('is_active', true)->value('id') ?? 0;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ─── CRUD Operations ────────────────────────────────────────────────────────

    public function openModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->resetForm();

        if ($id) {
            $template = LoaTemplate::findOrFail($id);
            $this->editingId = $template->id;
            $this->name = $template->name;
            $this->type = $template->type;
            $this->htmlTemplate = $template->html_template;
            $this->orientation = $template->orientation;
            $this->paperSize = $template->paper_size;
            $this->isDefault = $template->is_default;
            $this->signatureName = $template->signature_name ?? '';
            $this->signaturePosition = $template->signature_position ?? '';
            $this->signature2Name = $template->signature2_name ?? '';
            $this->signature2Position = $template->signature2_position ?? '';
            $this->existingSignature = $template->signature_image;
            $this->existingSignature2 = $template->signature2_image;
            $this->existingStamp = $template->stamp_image;
            $this->existingLogo = $template->logo_image;
            $this->existingLetterhead = $template->letterhead_image;
            $this->existingBackground = $template->background_image;
        }

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->type = 'all';
        $this->htmlTemplate = '';
        $this->orientation = 'portrait';
        $this->paperSize = 'a4';
        $this->isDefault = false;
        $this->signatureName = '';
        $this->signaturePosition = '';
        $this->signature2Name = '';
        $this->signature2Position = '';
        $this->signatureFile = null;
        $this->signature2File = null;
        $this->stampFile = null;
        $this->logoFile = null;
        $this->letterheadFile = null;
        $this->backgroundFile = null;
        $this->existingSignature = null;
        $this->existingSignature2 = null;
        $this->existingStamp = null;
        $this->existingLogo = null;
        $this->existingLetterhead = null;
        $this->existingBackground = null;
    }

    public function usePredefinedTemplate(string $key): void
    {
        $templates = LoaTemplate::getPredefinedTemplates();
        if (isset($templates[$key])) {
            $this->htmlTemplate = $templates[$key]['html'];
            $this->name = $this->name ?: $templates[$key]['name'];
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'conference_id' => $this->conferenceId ?: null,
            'name' => $this->name,
            'type' => $this->type,
            'html_template' => $this->htmlTemplate,
            'orientation' => $this->orientation,
            'paper_size' => $this->paperSize,
            'is_default' => $this->isDefault,
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
            'backgroundFile' => 'background_image',
        ];

        foreach ($fileFields as $uploadField => $dbField) {
            if ($this->{$uploadField}) {
                $data[$dbField] = $this->{$uploadField}->store('loa-templates', 'public');
            }
        }

        // If setting as default, unset other defaults
        if ($this->isDefault) {
            LoaTemplate::where('conference_id', $this->conferenceId ?: null)
                ->where('type', $this->type)
                ->update(['is_default' => false]);
        }

        if ($this->editingId) {
            LoaTemplate::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Template LOA berhasil diperbarui.');
        } else {
            LoaTemplate::create($data);
            session()->flash('success', 'Template LOA berhasil dibuat.');
        }

        $this->closeModal();
    }

    public function delete(int $id): void
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

    public function duplicate(int $id): void
    {
        $template = LoaTemplate::findOrFail($id);
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (Copy)';
        $newTemplate->slug = null; // Will be auto-generated
        $newTemplate->is_default = false;
        $newTemplate->save();
        
        session()->flash('success', 'Template LOA berhasil diduplikasi.');
    }

    public function setDefault(int $id): void
    {
        $template = LoaTemplate::findOrFail($id);
        
        // Unset other defaults of the same type
        LoaTemplate::where('conference_id', $template->conference_id)
            ->where('type', $template->type)
            ->update(['is_default' => false]);
        
        $template->update(['is_default' => true]);
        session()->flash('success', 'Template berhasil dijadikan default.');
    }

    public function toggleActive(int $id): void
    {
        $template = LoaTemplate::findOrFail($id);
        $template->update(['is_active' => !$template->is_active]);
    }

    // ─── Preview ────────────────────────────────────────────────────────────────

    public function preview(int $id): void
    {
        $template = LoaTemplate::findOrFail($id);
        
        // Create dummy data for preview
        $dummyHtml = $template->html_template;
        
        $replacements = [
            '{{judul}}' => 'Judul Paper Contoh untuk Preview Template',
            '{{title}}' => 'Sample Paper Title for Template Preview',
            '{{nama}}' => 'Dr. Nama Penulis Contoh, M.Pd.',
            '{{author}}' => 'Dr. Sample Author Name, M.Pd.',
            '{{email}}' => 'author@example.com',
            '{{institusi}}' => 'Universitas Contoh Indonesia',
            '{{institution}}' => 'Sample University Indonesia',
            '{{konferensi}}' => $template->conference?->name ?? 'Nama Konferensi Contoh 2026',
            '{{conference}}' => $template->conference?->name ?? 'Sample Conference 2026',
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
            '{{ttd_nama}}' => $template->signature_name ?? 'Prof. Dr. Nama Ketua Panitia',
            '{{signature_name}}' => $template->signature_name ?? 'Prof. Dr. Committee Chair',
            '{{ttd_jabatan}}' => $template->signature_position ?? 'Ketua Panitia',
            '{{signature_position}}' => $template->signature_position ?? 'Committee Chair',
            '{{ttd2_nama}}' => $template->signature2_name ?? '',
            '{{signature2_name}}' => $template->signature2_name ?? '',
            '{{ttd2_jabatan}}' => $template->signature2_position ?? '',
            '{{signature2_position}}' => $template->signature2_position ?? '',
        ];
        
        $dummyHtml = str_replace(array_keys($replacements), array_values($replacements), $dummyHtml);
        
        // Process images
        $this->previewHtml = $this->processPreviewImages($dummyHtml, $template);
        $this->showPreviewModal = true;
    }

    private function processPreviewImages(string $html, LoaTemplate $template): string
    {
        // Signature 1
        if ($template->signature_image) {
            $html = str_replace(['{{ttd}}', '{{signature}}'], $this->getImageTag($template->signature_image, 60), $html);
        } else {
            $html = str_replace(['{{ttd}}', '{{signature}}'], '<span style="color:#999;font-size:10px;">[Tanda Tangan]</span>', $html);
        }

        // Signature 2
        if ($template->signature2_image) {
            $html = str_replace(['{{ttd2}}', '{{signature2}}'], $this->getImageTag($template->signature2_image, 60), $html);
        } else {
            $html = str_replace(['{{ttd2}}', '{{signature2}}'], '', $html);
        }

        // Logo
        if ($template->logo_image) {
            $html = str_replace('{{logo}}', $this->getImageTag($template->logo_image, 80), $html);
        } else {
            $html = str_replace('{{logo}}', '<span style="color:#999;font-size:10px;">[Logo]</span>', $html);
        }

        // Letterhead
        if ($template->letterhead_image) {
            $html = str_replace(['{{kop_surat}}', '{{letterhead}}'], $this->getImageTag($template->letterhead_image, 120), $html);
        } else {
            $html = str_replace(['{{kop_surat}}', '{{letterhead}}'], '<div style="text-align:center;color:#999;font-size:10px;padding:20px;border:1px dashed #ccc;">[Kop Surat]</div>', $html);
        }

        // Stamp
        if ($template->stamp_image) {
            $html = str_replace(['{{stempel}}', '{{stamp}}'], $this->getImageTag($template->stamp_image, 80), $html);
        } else {
            $html = str_replace(['{{stempel}}', '{{stamp}}'], '', $html);
        }

        // Background
        if ($template->background_image) {
            $bgPath = storage_path('app/public/' . $template->background_image);
            if (file_exists($bgPath)) {
                $bgData = base64_encode(file_get_contents($bgPath));
                $bgMime = mime_content_type($bgPath);
                $html = str_replace('{{background}}', "data:{$bgMime};base64,{$bgData}", $html);
            }
        }

        // QR Code for verification
        $qrCodeHtml = $this->generatePreviewQrCode('LOA-00001');
        $html = str_replace(['{{qrcode}}', '{{qr_code}}'], $qrCodeHtml, $html);

        return $html;
    }

    /**
     * Generate QR Code for preview
     */
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

    public function closePreview(): void
    {
        $this->showPreviewModal = false;
        $this->previewHtml = '';
    }

    public function downloadPreview(int $id): mixed
    {
        $template = LoaTemplate::findOrFail($id);
        $this->preview($id);
        
        $pdf = Pdf::loadHTML($this->previewHtml)
            ->setPaper($template->paper_size, $template->orientation);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'preview-loa-' . now()->format('YmdHis') . '.pdf');
    }

    public function render()
    {
        $templates = LoaTemplate::query()
            ->when($this->conferenceId, fn($q) => $q->where('conference_id', $this->conferenceId))
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        $predefinedTemplates = LoaTemplate::getPredefinedTemplates();

        return view('livewire.admin.loa-template-manager', compact('templates', 'conferences', 'predefinedTemplates'))
            ->layout('layouts.app', ['title' => 'Kelola Template LOA']);
    }
}
