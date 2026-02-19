<?php

namespace App\Livewire\Reviewer;

use App\Models\Review;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ReviewList extends Component
{
    public string $statusFilter = '';

    public function render()
    {
        $reviews = Review::with(['paper.user', 'paper.files'])
            ->where('reviewer_id', Auth::id())
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.reviewer.review-list', compact('reviews'))->layout('layouts.app');
    }
}
