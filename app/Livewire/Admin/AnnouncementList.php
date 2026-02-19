<?php

namespace App\Livewire\Admin;

use App\Models\Announcement;
use App\Models\Conference;
use Livewire\Component;
use Livewire\WithPagination;

class AnnouncementList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = '';
    public string $statusFilter = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingTypeFilter() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function delete(Announcement $announcement)
    {
        $announcement->delete();
        session()->flash('success', 'Pengumuman berhasil dihapus.');
    }

    public function togglePinned(Announcement $announcement)
    {
        $announcement->update(['is_pinned' => !$announcement->is_pinned]);
    }

    public function render()
    {
        $announcements = Announcement::with('creator', 'conference')
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->typeFilter, fn($q) => $q->where('type', $this->typeFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.announcement-list', compact('announcements'))
            ->layout('layouts.app');
    }
}
