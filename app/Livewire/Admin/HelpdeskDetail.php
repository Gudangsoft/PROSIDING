<?php

namespace App\Livewire\Admin;

use App\Models\HelpdeskTicket;
use App\Models\HelpdeskReply;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HelpdeskDetail extends Component
{
    public HelpdeskTicket $ticket;
    public string $replyMessage = '';
    public string $newStatus = '';

    public function mount(HelpdeskTicket $ticket)
    {
        $this->ticket = $ticket;
        $this->newStatus = $ticket->status;
    }

    public function sendReply()
    {
        $this->validate([
            'replyMessage' => 'required|string|min:2',
        ]);

        HelpdeskReply::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'message' => $this->replyMessage,
        ]);

        // Auto set to in_progress if open
        if ($this->ticket->status === 'open') {
            $this->ticket->update([
                'status' => 'in_progress',
                'assigned_to' => $this->ticket->assigned_to ?? Auth::id(),
            ]);
            $this->newStatus = 'in_progress';
        }

        $this->replyMessage = '';
        $this->ticket->refresh();
        session()->flash('success', 'Balasan berhasil dikirim.');
    }

    public function updateStatus()
    {
        $data = ['status' => $this->newStatus];
        if ($this->newStatus === 'resolved') {
            $data['resolved_at'] = now();
        }
        if ($this->newStatus === 'in_progress' && !$this->ticket->assigned_to) {
            $data['assigned_to'] = Auth::id();
        }
        $this->ticket->update($data);
        $this->ticket->refresh();
        session()->flash('success', 'Status tiket berhasil diperbarui.');
    }

    public function render()
    {
        $replies = $this->ticket->replies()->with('user')->oldest()->get();

        return view('livewire.admin.helpdesk-detail', compact('replies'))
            ->layout('layouts.app');
    }
}
