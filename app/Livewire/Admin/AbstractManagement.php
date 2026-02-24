<?php

namespace App\Livewire\Admin;

use App\Models\AbstractSubmission;
use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class AbstractManagement extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $statusFilter = '';
    public string $confFilter   = '';

    public ?int $reviewingId    = null;
    public string $reviewStatus = '';
    public string $reviewNotes  = '';

    protected $queryString = ['search', 'statusFilter', 'confFilter'];

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function openReview(int $id): void
    {
        $abstract = AbstractSubmission::findOrFail($id);
        $this->reviewingId    = $id;
        $this->reviewStatus   = $abstract->status;
        $this->reviewNotes    = $abstract->reviewer_notes ?? '';
    }

    public function submitReview(): void
    {
        $this->validate([
            'reviewStatus' => 'required|in:approved,rejected,revision_required,pending',
            'reviewNotes'  => 'nullable|string|max:2000',
        ]);

        $abstract = AbstractSubmission::findOrFail($this->reviewingId);
        $abstract->update([
            'status'        => $this->reviewStatus,
            'reviewer_notes'=> $this->reviewNotes,
            'reviewed_by'   => auth()->id(),
            'reviewed_at'   => now(),
        ]);

        // Notifikasi ke author
        $labelMap = [
            'approved'          => 'Abstrak Anda Disetujui',
            'rejected'          => 'Abstrak Anda Ditolak',
            'revision_required' => 'Abstrak Perlu Revisi',
        ];
        Notification::create([
            'user_id' => $abstract->user_id,
            'type'    => 'abstract_review',
            'title'   => $labelMap[$this->reviewStatus] ?? 'Update Status Abstrak',
            'message' => 'Status abstrak "' . $abstract->title . '" diperbarui menjadi: ' . ($labelMap[$this->reviewStatus] ?? $this->reviewStatus),
            'data'    => json_encode(['abstract_id' => $abstract->id]),
        ]);

        $this->reset(['reviewingId', 'reviewStatus', 'reviewNotes']);
        session()->flash('success', 'Review abstrak berhasil disimpan.');
    }

    public function cancelReview(): void
    {
        $this->reset(['reviewingId', 'reviewStatus', 'reviewNotes']);
    }

    public function render()
    {
        $abstracts = AbstractSubmission::with(['user', 'conference'])
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
            }))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->confFilter,   fn($q) => $q->where('conference_id', $this->confFilter))
            ->latest()
            ->paginate(15);

        $conferences = \App\Models\Conference::orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.abstract-management', compact('abstracts', 'conferences'))
            ->layout('layouts.app', ['title' => 'Manajemen Abstrak']);
    }
}
