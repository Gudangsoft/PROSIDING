<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\Paper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class AcceptanceLetterManager extends Component
{
    use WithPagination;

    public int    $conferenceId = 0;
    public string $search       = '';
    public string $statusFilter = '';

    public function updatedConferenceId(): void { $this->resetPage(); }
    public function updatedSearch(): void       { $this->resetPage(); }

    public function generateLetter(int $paperId): void
    {
        $paper = Paper::with(['user', 'conference'])->findOrFail($paperId);
        $conf  = $paper->conference;

        $data = [
            'paper'      => $paper,
            'conference' => $conf,
            'issuedAt'   => now()->format('d F Y'),
            'siteName'   => \App\Models\Setting::get('site_name', config('app.name')),
            'siteAddress'=> \App\Models\Setting::get('address', ''),
        ];

        $pdf  = Pdf::loadView('pdfs.acceptance-letter', $data)->setPaper('A4');
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

        return view('livewire.admin.acceptance-letter-manager', compact('conferences', 'papers'))
            ->layout('layouts.app', ['title' => 'Acceptance Letter']);
    }
}
