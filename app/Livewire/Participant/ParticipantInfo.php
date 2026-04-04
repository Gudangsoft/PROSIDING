<?php

namespace App\Livewire\Participant;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ParticipantInfo extends Component
{
    public function render()
    {
        $user = Auth::user();

        // Participant-type payment (for users with participant role)
        $payment = \App\Models\Payment::where('user_id', $user->id)
            ->where('type', \App\Models\Payment::TYPE_PARTICIPANT)
            ->first();

        // For authors (pemakalah): also fetch their papers
        $papers = null;
        if ($user->isAuthor()) {
            $papers = \App\Models\Paper::where('user_id', $user->id)
                ->with(['conference:id,name', 'payment'])
                ->latest('submitted_at')
                ->get();
        }

        $conference = \App\Models\Conference::active()->first();

        return view('livewire.participant.participant-info', compact('user', 'payment', 'conference', 'papers'))
            ->layout('layouts.app');
    }
}
