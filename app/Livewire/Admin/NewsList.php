<?php

namespace App\Livewire\Admin;

use App\Models\News;
use App\Models\Conference;
use Livewire\Component;
use Livewire\WithPagination;

class NewsList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $categoryFilter = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }

    public function delete(News $news)
    {
        $news->delete();
        session()->flash('success', 'Berita berhasil dihapus.');
    }

    public function toggleFeatured(News $news)
    {
        $news->update(['is_featured' => !$news->is_featured]);
    }

    public function togglePinned(News $news)
    {
        $news->update(['is_pinned' => !$news->is_pinned]);
    }

    public function render()
    {
        $newsItems = News::with('author', 'conference')
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category', $this->categoryFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.news-list', compact('newsItems'))
            ->layout('layouts.app');
    }
}
