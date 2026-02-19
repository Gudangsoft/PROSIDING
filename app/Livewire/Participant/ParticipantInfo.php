<?php

namespace App\Livewire\Participant;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ParticipantInfo extends Component
{
    public function render()
    {
        $user = Auth::user();

        $payment = \App\Models\Payment::where('user_id', $user->id)
            ->where('type', \App\Models\Payment::TYPE_PARTICIPANT)
            ->first();

        $conference = \App\Models\Conference::active()->first();

        return view('livewire.participant.participant-info', compact('user', 'payment', 'conference'))
            ->layout('layouts.app');
    }
}
