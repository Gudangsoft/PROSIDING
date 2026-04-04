<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Conference;
use App\Models\Payment;
use App\Models\Paper;
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateManager extends Component
{
    use WithPagination, WithFileUploads;

    // Main filters
    public int $conferenceId = 0;
    public string $type = 'participant';
    public string $search = '';

    // Tab management
    public string $activeTab = 'certificates'; // certificates, templates

    // Template management
    public bool $showTemplateModal = false;
    public bool $showPreviewModal = false;
    public ?int $editingTemplateId = null;
    public string $templateName = '';
    public string $templateType = 'all';
    public string $templateHtml = '';
    public string $templateOrientation = 'landscape';
    public string $templatePaperSize = 'a4';
    public bool $templateIsDefault = false;

    // Signature details
    public string $signature1Name = '';
    public string $signature1Position = '';
    public string $signature2Name = '';
    public string $signature2Position = '';

    // File uploads
    public $signatureFile;
    public $signature2File;
    public $stampFile;
    public $logoFile;
    public $backgroundFile;

    // Existing images
    public ?string $existingSignature = null;
    public ?string $existingSignature2 = null;
    public ?string $existingStamp = null;
    public ?string $existingLogo = null;
    public ?string $existingBackground = null;

    // Preview
    public string $previewHtml = '';
    public ?int $selectedTemplateId = null;

    protected $queryString = ['conferenceId', 'type', 'search', 'activeTab'];

    protected $rules = [
        'templateName' => 'required|string|max:255',
        'templateType' => 'required|in:all,participant,presenter,reviewer,committee',
        'templateHtml' => 'required|string',
        'templateOrientation' => 'required|in:landscape,portrait',
        'templatePaperSize' => 'required|in:a4,letter,legal',
        'signatureFile' => 'nullable|image|max:2048',
        'signature2File' => 'nullable|image|max:2048',
        'stampFile' => 'nullable|image|max:2048',
        'logoFile' => 'nullable|image|max:2048',
        'backgroundFile' => 'nullable|image|max:5120',
        'signature1Name' => 'nullable|string|max:255',
        'signature1Position' => 'nullable|string|max:255',
        'signature2Name' => 'nullable|string|max:255',
        'signature2Position' => 'nullable|string|max:255',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->conferenceId = Conference::where('is_active', true)->value('id') ?? 0;
    }

    // ─── Template Management ───────────────────────────────────────────────────

    public function openTemplateModal(?int $templateId = null): void
    {
        $this->resetValidation();
        $this->resetTemplateForm();

        if ($templateId) {
            $template = CertificateTemplate::findOrFail($templateId);
            $this->editingTemplateId = $template->id;
            $this->templateName = $template->name;
            $this->templateType = $template->type;
            $this->templateHtml = $template->html_template;
            $this->templateOrientation = $template->orientation;
            $this->templatePaperSize = $template->paper_size;
            $this->templateIsDefault = $template->is_default;
            $this->existingSignature = $template->signature_image;
            $this->existingSignature2 = $template->signature2_image;
            $this->existingStamp = $template->stamp_image;
            $this->existingLogo = $template->logo_image;
            $this->existingBackground = $template->background_image;
            $this->signature1Name = $template->signature1_name ?? '';
            $this->signature1Position = $template->signature1_position ?? '';
            $this->signature2Name = $template->signature2_name ?? '';
            $this->signature2Position = $template->signature2_position ?? '';
        }

        $this->showTemplateModal = true;
    }

    public function usePredefinedTemplate(string $key): void
    {
        $templates = CertificateTemplate::getPredefinedTemplates();
        if (isset($templates[$key])) {
            $this->templateHtml = $templates[$key]['html'];
            $this->templateName = $this->templateName ?: $templates[$key]['name'];
        }
    }

    public function saveTemplate(): void
    {
        $this->validate();

        $data = [
            'conference_id' => $this->conferenceId ?: null,
            'name' => $this->templateName,
            'type' => $this->templateType,
            'html_template' => $this->templateHtml,
            'orientation' => $this->templateOrientation,
            'paper_size' => $this->templatePaperSize,
            'is_default' => $this->templateIsDefault,
            'signature1_name' => $this->signature1Name ?: null,
            'signature1_position' => $this->signature1Position ?: null,
            'signature2_name' => $this->signature2Name ?: null,
            'signature2_position' => $this->signature2Position ?: null,
        ];

        // Handle file uploads
        if ($this->signatureFile) {
            $data['signature_image'] = $this->signatureFile->store('certificates/signatures', 'public');
        } elseif ($this->existingSignature) {
            $data['signature_image'] = $this->existingSignature;
        }

        if ($this->signature2File) {
            $data['signature2_image'] = $this->signature2File->store('certificates/signatures', 'public');
        } elseif ($this->existingSignature2) {
            $data['signature2_image'] = $this->existingSignature2;
        }

        if ($this->stampFile) {
            $data['stamp_image'] = $this->stampFile->store('certificates/stamps', 'public');
        } elseif ($this->existingStamp) {
            $data['stamp_image'] = $this->existingStamp;
        }

        if ($this->logoFile) {
            $data['logo_image'] = $this->logoFile->store('certificates/logos', 'public');
        } elseif ($this->existingLogo) {
            $data['logo_image'] = $this->existingLogo;
        }

        if ($this->backgroundFile) {
            $data['background_image'] = $this->backgroundFile->store('certificates/backgrounds', 'public');
        } elseif ($this->existingBackground) {
            $data['background_image'] = $this->existingBackground;
        }

        // If setting as default, unset others
        if ($this->templateIsDefault) {
            CertificateTemplate::where('conference_id', $this->conferenceId ?: null)
                ->where('type', $this->templateType)
                ->update(['is_default' => false]);
        }

        if ($this->editingTemplateId) {
            CertificateTemplate::findOrFail($this->editingTemplateId)->update($data);
            session()->flash('success', 'Template berhasil diperbarui.');
        } else {
            $data['slug'] = Str::slug($this->templateName) . '-' . Str::random(6);
            CertificateTemplate::create($data);
            session()->flash('success', 'Template baru berhasil dibuat.');
        }

        $this->closeTemplateModal();
    }

    public function deleteTemplate(int $id): void
    {
        $template = CertificateTemplate::findOrFail($id);

        // Delete associated files
        if ($template->signature_image) Storage::disk('public')->delete($template->signature_image);
        if ($template->signature2_image) Storage::disk('public')->delete($template->signature2_image);
        if ($template->stamp_image) Storage::disk('public')->delete($template->stamp_image);
        if ($template->logo_image) Storage::disk('public')->delete($template->logo_image);
        if ($template->background_image) Storage::disk('public')->delete($template->background_image);

        $template->delete();
        session()->flash('success', 'Template berhasil dihapus.');
    }

    public function duplicateTemplate(int $id): void
    {
        $template = CertificateTemplate::findOrFail($id);
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (Copy)';
        $newTemplate->slug = Str::slug($newTemplate->name) . '-' . Str::random(6);
        $newTemplate->is_default = false;
        $newTemplate->save();

        session()->flash('success', 'Template berhasil diduplikasi.');
    }

    public function setDefaultTemplate(int $id): void
    {
        $template = CertificateTemplate::findOrFail($id);

        // Unset all other defaults for this type
        CertificateTemplate::where('conference_id', $template->conference_id)
            ->where(function ($q) use ($template) {
                $q->where('type', $template->type)->orWhere('type', 'all');
            })
            ->update(['is_default' => false]);

        $template->update(['is_default' => true]);
        session()->flash('success', 'Template default berhasil diubah.');
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
        $this->templateType = 'all';
        $this->templateHtml = '';
        $this->templateOrientation = 'landscape';
        $this->templatePaperSize = 'a4';
        $this->templateIsDefault = false;
        $this->signatureFile = null;
        $this->signature2File = null;
        $this->stampFile = null;
        $this->logoFile = null;
        $this->backgroundFile = null;
        $this->existingSignature = null;
        $this->existingSignature2 = null;
        $this->existingStamp = null;
        $this->existingLogo = null;
        $this->existingBackground = null;
        $this->signature1Name = '';
        $this->signature1Position = '';
        $this->signature2Name = '';
        $this->signature2Position = '';
    }

    public function removeExistingImage(string $type): void
    {
        match ($type) {
            'signature' => $this->existingSignature = null,
            'signature2' => $this->existingSignature2 = null,
            'stamp' => $this->existingStamp = null,
            'logo' => $this->existingLogo = null,
            'background' => $this->existingBackground = null,
            default => null,
        };
    }

    // ─── Preview ───────────────────────────────────────────────────────────────

    public function previewTemplate(?int $templateId = null): void
    {
        if ($templateId) {
            $template = CertificateTemplate::findOrFail($templateId);
            $html = $template->html_template;
        } else {
            $html = $this->templateHtml;
        }

        // Replace with sample data
        $this->previewHtml = str_replace(
            ['{{nama}}', '{{nomor}}', '{{tipe}}', '{{konferensi}}', '{{tanggal}}', '{{tahun}}', '{{email}}', '{{institusi}}'],
            ['Dr. John Doe, M.Sc.', 'CERT/2026/0001', 'Presenter', 'International Conference on Technology 2026', '28 Februari 2026', '2026', 'john.doe@example.com', 'Universitas Indonesia'],
            $html
        );

        // Handle image placeholders
        $this->previewHtml = str_replace(
            ['{{ttd}}', '{{stempel}}', '{{logo}}', '{{background}}'],
            ['<div style="height:60px;border-bottom:1px solid #ccc;width:120px;margin:0 auto;"></div>', '', '', ''],
            $this->previewHtml
        );

        $this->showPreviewModal = true;
    }

    public function closePreviewModal(): void
    {
        $this->showPreviewModal = false;
        $this->previewHtml = '';
    }

    // ─── Certificate Generation ────────────────────────────────────────────────

    public function generateBulk(): void
    {
        if (!$this->conferenceId) {
            session()->flash('error', 'Pilih konferensi terlebih dahulu.');
            return;
        }

        $conference = Conference::findOrFail($this->conferenceId);

        if ($this->type === 'participant') {
            $users = Payment::with('user')
                ->whereHas('registrationPackage', fn($q) => $q->where('conference_id', $this->conferenceId))
                ->where('type', 'participant')
                ->where('status', 'verified')
                ->get()
                ->map(fn($p) => $p->user)
                ->filter()
                ->unique('id');
        } elseif ($this->type === 'presenter') {
            $users = Paper::with('user')
                ->where('conference_id', $this->conferenceId)
                ->whereIn('status', ['accepted', 'payment_verified', 'deliverables_pending', 'completed'])
                ->get()
                ->map(fn($p) => $p->user)
                ->filter()
                ->unique('id');
        } elseif ($this->type === 'reviewer') {
            $users = Review::with('reviewer')
                ->whereHas('paper', fn($q) => $q->where('conference_id', $this->conferenceId))
                ->get()
                ->map(fn($r) => $r->reviewer)
                ->filter()
                ->unique('id');
        } else {
            $users = collect();
        }

        $count = 0;
        foreach ($users as $user) {
            $exists = Certificate::where('user_id', $user->id)
                ->where('conference_id', $this->conferenceId)
                ->where('type', $this->type)
                ->exists();

            if (!$exists) {
                Certificate::create([
                    'user_id' => $user->id,
                    'conference_id' => $this->conferenceId,
                    'type' => $this->type,
                    'certificate_number' => Certificate::generateNumber(strtoupper(substr($this->type, 0, 4))),
                    'recipient_name' => $user->name,
                    'issued_at' => now(),
                ]);
                $count++;
            }
        }

        session()->flash('success', "{$count} sertifikat berhasil digenerate.");
    }

    public function deleteCertificate(int $id): void
    {
        Certificate::findOrFail($id)->delete();
        session()->flash('success', 'Sertifikat berhasil dihapus.');
    }

    // ─── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $certs = Certificate::with(['user', 'conference'])
            ->when($this->conferenceId, fn($q) => $q->where('conference_id', $this->conferenceId))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->search, fn($q) => $q->where('recipient_name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(15);

        $templates = CertificateTemplate::query()
            ->when($this->conferenceId, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('conference_id', $this->conferenceId)
                        ->orWhereNull('conference_id');
                });
            })
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();

        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        $predefinedTemplates = CertificateTemplate::getPredefinedTemplates();

        return view('livewire.admin.certificate-manager', compact(
            'certs', 'templates', 'conferences', 'predefinedTemplates'
        ))->layout('layouts.app', ['title' => 'Manajemen Sertifikat']);
    }
}
