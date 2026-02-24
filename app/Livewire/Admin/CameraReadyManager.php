<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\Paper;
use Livewire\Component;
use Livewire\WithPagination;

class CameraReadyManager extends Component
{
    use WithPagination;

    public int    $conferenceId  = 0;
    public string $statusFilter  = 'pending';
    public string $search        = '';
    public string $rejectNote    = '';
    public ?int   $rejectPaperId = null;

    public function updatedConferenceId(): void { $this->resetPage(); }
    public function updatedStatusFilter(): void { $this->resetPage(); }

    public function approve(int $paperId): void
    {
        $paper = Paper::findOrFail($paperId);
        $paper->update(['camera_ready_status' => 'approved']);

        \App\Models\Notification::create([
            'user_id' => $paper->user_id,
            'type'    => 'camera_ready_approved',
            'title'   => 'Camera-Ready Disetujui',
            'message' => 'File camera-ready paper "' . $paper->title . '" telah disetujui.',
            'data'    => ['paper_id' => $paper->id],
        ]);

        session()->flash('success', 'Camera-ready disetujui.');
    }

    public function openReject(int $paperId): void
    {
        $this->rejectPaperId = $paperId;
        $this->rejectNote    = '';
    }

    public function reject(): void
    {
        $this->validate(['rejectNote' => 'required|string|min:5'], [], ['rejectNote' => 'Catatan penolakan']);

        $paper = Paper::findOrFail($this->rejectPaperId);
        $paper->update([
            'camera_ready_status' => 'rejected',
            'camera_ready_notes'  => $this->rejectNote,
        ]);

        \App\Models\Notification::create([
            'user_id' => $paper->user_id,
            'type'    => 'camera_ready_rejected',
            'title'   => 'Camera-Ready Ditolak',
            'message' => 'File camera-ready paper "' . $paper->title . '" ditolak. Catatan: ' . $this->rejectNote,
            'data'    => ['paper_id' => $paper->id],
        ]);

        $this->rejectPaperId = null;
        $this->rejectNote    = '';
        session()->flash('success', 'Camera-ready ditolak, author akan dinotifikasi.');
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        $papers      = collect();

        $statusCounts = [];
        if ($this->conferenceId) {
            $statusCounts = Paper::where('conference_id', $this->conferenceId)
                ->whereIn('camera_ready_status', ['pending', 'approved', 'rejected'])
                ->selectRaw('camera_ready_status, count(*) as total')
                ->groupBy('camera_ready_status')
                ->pluck('total', 'camera_ready_status')
                ->toArray();

            $papers = Paper::where('conference_id', $this->conferenceId)
                ->when($this->statusFilter !== 'all', fn($q) => $q->where('camera_ready_status', $this->statusFilter))
                ->when($this->statusFilter === 'all', fn($q) => $q->whereIn('camera_ready_status', ['pending', 'approved', 'rejected']))
                ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
                ->with('user:id,name,email')
                ->orderByRaw("FIELD(camera_ready_status,'pending','rejected','approved')")
                ->paginate(20);
        }

        return view('livewire.admin.camera-ready-manager', compact('conferences', 'papers', 'statusCounts'))
            ->layout('layouts.app', ['title' => 'Camera-Ready Manager']);
    }
}
