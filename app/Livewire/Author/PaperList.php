<?php

namespace App\Livewire\Author;

use App\Models\Paper;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PaperList extends Component
{
    public string $search = '';
    public string $statusFilter = '';

    public function render()
    {
        $papers = Paper::where('user_id', Auth::id())
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.author.paper-list', compact('papers'))->layout('layouts.app');
    }
}
