<?php

namespace App\Livewire\Author;

use App\Models\Paper;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoaList extends Component
{
    public function render()
    {
        $papers = Paper::where('user_id', Auth::id())
            ->whereNotNull('loa_link')
            ->whereNotNull('accepted_at')
            ->with(['payment'])
            ->latest('accepted_at')
            ->get();

        return view('livewire.author.loa-list', compact('papers'))->layout('layouts.app');
    }
}
