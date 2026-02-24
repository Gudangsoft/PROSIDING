<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Payment;
use App\Models\Paper;
use App\Models\Review;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CertificateManager extends Component
{
    use WithPagination;

    public int $conferenceId   = 0;
    public string $type        = 'participant';
    public string $search      = '';
    public bool $showTemplate  = false;
    public string $templateHtml = '';

    protected $queryString = ['conferenceId', 'type', 'search'];

    public function updatingSearch(): void { $this->resetPage(); }

    public function mount(): void
    {
        $this->conferenceId = Conference::where('is_active', true)->value('id') ?? 0;
        $this->loadTemplate();
    }

    public function updatedConferenceId(): void
    {
        $this->loadTemplate();
    }

    public function loadTemplate(): void
    {
        if ($this->conferenceId) {
            $conf = Conference::find($this->conferenceId);
            $this->templateHtml = $conf?->certificate_template ?? $this->defaultTemplate();
        }
    }

    public function saveTemplate(): void
    {
        Conference::findOrFail($this->conferenceId)->update([
            'certificate_template' => $this->templateHtml,
            'certificate_enabled'  => true,
        ]);
        $this->showTemplate = false;
        session()->flash('success', 'Template sertifikat disimpan.');
    }

    public function generateBulk(): void
    {
        $conference = Conference::findOrFail($this->conferenceId);

        if ($this->type === 'participant') {
            $users = Payment::with('user')
                ->where('conference_id', $this->conferenceId)
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
                    'user_id'            => $user->id,
                    'conference_id'      => $this->conferenceId,
                    'type'               => $this->type,
                    'certificate_number' => Certificate::generateNumber(strtoupper(substr($this->type, 0, 4))),
                    'recipient_name'     => $user->name,
                    'issued_at'          => now(),
                ]);
                $count++;
            }
        }

        session()->flash('success', "{$count} sertifikat berhasil digenerate.");
    }

    public function downloadPdf(int $certId): mixed
    {
        $cert       = Certificate::with(['user', 'conference'])->findOrFail($certId);
        $template   = $cert->conference->certificate_template ?? $this->defaultTemplate();
        $html       = $this->renderTemplate($template, $cert);

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "sertifikat-{$cert->certificate_number}.pdf"
        );
    }

    private function renderTemplate(string $template, Certificate $cert): string
    {
        return str_replace(
            ['{{nama}}', '{{nomor}}', '{{tipe}}', '{{konferensi}}', '{{tanggal}}'],
            [
                $cert->recipient_name,
                $cert->certificate_number,
                $cert->type_label,
                $cert->conference->name,
                $cert->issued_at?->isoFormat('D MMMM Y') ?? date('d F Y'),
            ],
            $template
        );
    }

    private function defaultTemplate(): string
    {
        return '<html><body style="font-family:serif;text-align:center;padding:80px;">
<h1 style="font-size:36px;color:#1e40af;">SERTIFIKAT</h1>
<p style="font-size:18px;margin-top:30px;">Diberikan kepada:</p>
<h2 style="font-size:32px;border-bottom:2px solid #1e40af;display:inline-block;padding-bottom:8px;">{{nama}}</h2>
<p style="font-size:16px;margin-top:20px;">Sebagai <strong>{{tipe}}</strong> pada</p>
<p style="font-size:20px;font-weight:bold;">{{konferensi}}</p>
<p style="margin-top:40px;color:#6b7280;">No. Sertifikat: {{nomor}} &nbsp;|&nbsp; {{tanggal}}</p>
</body></html>';
    }

    public function render()
    {
        $certs = Certificate::with(['user', 'conference'])
            ->when($this->conferenceId, fn($q) => $q->where('conference_id', $this->conferenceId))
            ->when($this->type,         fn($q) => $q->where('type', $this->type))
            ->when($this->search,       fn($q) => $q->where('recipient_name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(20);

        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.certificate-manager', compact('certs', 'conferences'))
            ->layout('layouts.app', ['title' => 'Sertifikat']);
    }
}
