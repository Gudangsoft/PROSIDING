<?php

namespace App\Livewire\Admin;

use App\Models\HelpdeskTicket;
use Livewire\Component;
use Livewire\WithPagination;

class HelpdeskManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $categoryFilter = '';
    public string $priorityFilter = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }
    public function updatingPriorityFilter() { $this->resetPage(); }

    public function updateStatus(HelpdeskTicket $ticket, string $status)
    {
        $data = ['status' => $status];
        if ($status === 'resolved') {
            $data['resolved_at'] = now();
        }
        if ($status === 'in_progress' && !$ticket->assigned_to) {
            $data['assigned_to'] = auth()->id();
        }
        $ticket->update($data);
        session()->flash('success', 'Status tiket berhasil diperbarui.');
    }

    public function delete(HelpdeskTicket $ticket)
    {
        $ticket->delete();
        session()->flash('success', 'Tiket berhasil dihapus.');
    }

    public function render()
    {
        $tickets = HelpdeskTicket::with('user', 'assignee')
            ->withCount('replies')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('subject', 'like', "%{$this->search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%"));
            }))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category', $this->categoryFilter))
            ->when($this->priorityFilter, fn($q) => $q->where('priority', $this->priorityFilter))
            ->latest()
            ->paginate(15);

        $counts = [
            'total' => HelpdeskTicket::count(),
            'open' => HelpdeskTicket::where('status', 'open')->count(),
            'in_progress' => HelpdeskTicket::where('status', 'in_progress')->count(),
            'resolved' => HelpdeskTicket::where('status', 'resolved')->count(),
        ];

        return view('livewire.admin.helpdesk-management', compact('tickets', 'counts'))
            ->layout('layouts.app');
    }
}
