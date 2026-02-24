<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\Paper;
use App\Models\RevisionRequest;
use Livewire\Component;
use Livewire\WithPagination;

class RevisionManager extends Component
{
    use WithPagination;

    public int    $conferenceId  = 0;
    public string $search        = '';
    public string $statusFilter  = '';

    // Send revision modal
    public bool   $showModal  = false;
    public ?int   $paperId    = null;
    public string $note       = '';
    public string $deadline   = '';

    public function updatedConferenceId(): void { $this->resetPage(); }

    public function openSendRevision(int $paperId): void
    {
        $this->paperId   = $paperId;
        $this->note      = '';
        $this->deadline  = now()->addDays(7)->format('Y-m-d');
        $this->showModal = true;
    }

    public function sendRevision(): void
    {
        $this->validate([
            'note'     => 'required|string|min:10',
            'deadline' => 'nullable|date|after:today',
        ], [], ['note' => 'Catatan revisi', 'deadline' => 'Deadline']);

        $paper = Paper::findOrFail($this->paperId);

        RevisionRequest::create([
            'paper_id'   => $paper->id,
            'created_by' => auth()->id(),
            'note'       => $this->note,
            'deadline'   => $this->deadline ?: null,
        ]);

        $paper->update(['status' => 'revision_required']);

        \App\Models\Notification::create([
            'user_id' => $paper->user_id,
            'type'    => 'revision_required',
            'title'   => 'Revisi Diperlukan',
            'message' => 'Paper "' . $paper->title . '" memerlukan revisi. Deadline: ' . ($this->deadline ? \Carbon\Carbon::parse($this->deadline)->format('d M Y') : 'Tidak ada'),
            'data'    => ['paper_id' => $paper->id],
        ]);

        $this->showModal = false;
        $this->paperId   = null;
        session()->flash('success', 'Permintaan revisi dikirim ke author.');
    }

    public function markResolved(int $requestId): void
    {
        RevisionRequest::findOrFail($requestId)->update(['resolved_at' => now()]);
        session()->flash('success', 'Revisi ditandai selesai.');
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        $papers      = collect();

        if ($this->conferenceId) {
            $papers = Paper::where('conference_id', $this->conferenceId)
                ->whereIn('status', ['in_review', 'screening', 'accepted', 'revision_required', 'revised'])
                ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
                ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
                ->with(['user:id,name', 'revisionRequests' => fn($q) => $q->latest()->limit(1)])
                ->orderByRaw("FIELD(status,'revision_required','revised','in_review','screening','accepted')")
                ->paginate(20);
        }

        return view('livewire.admin.revision-manager', compact('conferences', 'papers'))
            ->layout('layouts.app', ['title' => 'Revision Manager']);
    }
}
