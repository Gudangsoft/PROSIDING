<?php

namespace App\Livewire\Author;

use App\Models\AbstractSubmission;
use App\Models\Conference;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AbstractList extends Component
{
    public $abstracts = [];

    public function mount(): void
    {
        $this->loadAbstracts();
    }

    public function loadAbstracts(): void
    {
        $this->abstracts = AbstractSubmission::with('conference')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    public function delete(int $id): void
    {
        $abstract = AbstractSubmission::where('user_id', Auth::id())->findOrFail($id);
        if ($abstract->status === 'pending') {
            $abstract->delete();
            session()->flash('success', 'Abstrak berhasil dihapus.');
            $this->loadAbstracts();
        }
    }

    public function render()
    {
        return view('livewire.author.abstract-list')
            ->layout('layouts.app', ['title' => 'Abstrak Saya']);
    }
}
