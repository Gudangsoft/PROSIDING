<?php

namespace App\Livewire\Author;

use App\Models\HelpdeskTicket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Helpdesk extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    // Form fields for creating new ticket
    public bool $showForm = false;
    public string $subject = '';
    public string $message = '';
    public string $category = 'lainnya';
    public string $priority = 'normal';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        if (!$this->showForm) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->subject = '';
        $this->message = '';
        $this->category = 'lainnya';
        $this->priority = 'normal';
    }

    public function createTicket()
    {
        $this->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'category' => 'required|in:teknis,paper,pembayaran,akun,lainnya',
            'priority' => 'required|in:rendah,normal,tinggi,urgent',
        ]);

        HelpdeskTicket::create([
            'user_id' => Auth::id(),
            'subject' => $this->subject,
            'message' => $this->message,
            'category' => $this->category,
            'priority' => $this->priority,
            'status' => 'open',
        ]);

        $this->showForm = false;
        $this->resetForm();
        session()->flash('success', 'Tiket helpdesk berhasil dibuat.');
    }

    public function render()
    {
        $tickets = HelpdeskTicket::where('user_id', Auth::id())
            ->when($this->search, fn($q) => $q->where('subject', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->withCount('replies')
            ->latest()
            ->paginate(10);

        return view('livewire.author.helpdesk', compact('tickets'))
            ->layout('layouts.app');
    }
}
