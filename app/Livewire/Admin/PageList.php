<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class PageList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function delete(Page $page)
    {
        if ($page->cover_image && \Storage::disk('public')->exists($page->cover_image)) {
            \Storage::disk('public')->delete($page->cover_image);
        }
        $page->delete();
        session()->flash('success', 'Halaman berhasil dihapus.');
    }

    public function toggleFeatured(Page $page)
    {
        $page->update(['is_featured' => !$page->is_featured]);
    }

    public function duplicate(Page $page)
    {
        $new = $page->replicate();
        $new->title = $page->title . ' (Copy)';
        $new->slug = null; // will be auto-generated in booted()
        $new->status = 'draft';
        $new->views_count = 0;
        $new->published_at = null;
        $new->save();
        session()->flash('success', 'Halaman berhasil diduplikasi.');
    }

    public function render()
    {
        $pages = Page::with('author')
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy('sort_order')
            ->latest('updated_at')
            ->paginate(15);

        return view('livewire.admin.page-list', compact('pages'))
            ->layout('layouts.app');
    }
}
