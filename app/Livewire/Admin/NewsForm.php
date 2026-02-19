<?php

namespace App\Livewire\Admin;

use App\Models\News;
use App\Models\Conference;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class NewsForm extends Component
{
    use WithFileUploads;

    public ?News $news = null;
    public bool $isEdit = false;

    public string $title = '';
    public string $excerpt = '';
    public string $content = '';
    public string $category = 'general';
    public string $status = 'draft';
    public bool $is_featured = false;
    public bool $is_pinned = false;
    public string $conference_id = '';
    public $cover_image;

    public function mount(?News $news = null)
    {
        if ($news && $news->exists) {
            $this->isEdit = true;
            $this->news = $news;
            $this->fill([
                'title' => $news->title,
                'excerpt' => $news->excerpt ?? '',
                'content' => $news->content,
                'category' => $news->category,
                'status' => $news->status,
                'is_featured' => $news->is_featured,
                'is_pinned' => $news->is_pinned,
                'conference_id' => (string) ($news->conference_id ?? ''),
            ]);
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required',
            'status' => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $this->title,
            'excerpt' => $this->excerpt ?: null,
            'content' => $this->content,
            'category' => $this->category,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'is_pinned' => $this->is_pinned,
            'conference_id' => $this->conference_id ?: null,
        ];

        if ($this->cover_image) {
            $data['cover_image'] = $this->cover_image->store('news', 'public');
        }

        if ($this->status === 'published') {
            $data['published_at'] = now();
        }

        if ($this->isEdit) {
            $this->news->update($data);
        } else {
            $data['slug'] = News::generateSlug($this->title);
            $data['author_id'] = Auth::id();
            News::create($data);
        }

        session()->flash('success', $this->isEdit ? 'Berita berhasil diperbarui.' : 'Berita berhasil dibuat.');
        return redirect()->route('admin.news');
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get();
        return view('livewire.admin.news-form', compact('conferences'))
            ->layout('layouts.app');
    }
}
