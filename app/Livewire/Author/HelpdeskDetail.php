<?php

namespace App\Livewire\Author;

use App\Models\HelpdeskTicket;
use App\Models\HelpdeskReply;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HelpdeskDetail extends Component
{
    public HelpdeskTicket $ticket;
    public string $replyMessage = '';

    public function mount(HelpdeskTicket $ticket)
    {
        // Ensure user can only view their own tickets
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }
        $this->ticket = $ticket;
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

        // Re-open if it was resolved
        if ($this->ticket->status === 'resolved') {
            $this->ticket->update(['status' => 'open']);
        }

        $this->replyMessage = '';
        $this->ticket->refresh();
        session()->flash('success', 'Balasan berhasil dikirim.');
    }

    public function closeTicket()
    {
        $this->ticket->update(['status' => 'closed']);
        $this->ticket->refresh();
        session()->flash('success', 'Tiket ditutup.');
    }

    public function render()
    {
        $replies = $this->ticket->replies()->with('user')->oldest()->get();

        return view('livewire.author.helpdesk-detail', compact('replies'))
            ->layout('layouts.app');
    }
}
