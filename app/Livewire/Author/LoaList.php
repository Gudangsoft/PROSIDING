<?php

namespace App\Livewire\Author;

use App\Models\AbstractSubmission;
use App\Models\Paper;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoaList extends Component
{
    public function render()
    {
        // Papers with LOA
        $papers = Paper::where('user_id', Auth::id())
            ->whereNotNull('loa_link')
            ->whereNotNull('accepted_at')
            ->with(['payment', 'conference'])
            ->latest('accepted_at')
            ->get();

        // Abstracts with verified payment (get LOA)
        $abstracts = AbstractSubmission::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->whereHas('payment', fn($q) => $q->where('status', 'verified'))
            ->with(['payment', 'conference'])
            ->latest()
            ->get();

        return view('livewire.author.loa-list', compact('papers', 'abstracts'))->layout('layouts.app');
    }
}
