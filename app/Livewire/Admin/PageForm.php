<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use App\Models\Menu;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageForm extends Component
{
    use WithFileUploads;

    public ?Page $page = null;
    public bool $isEdit = false;

    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public string $content = '';
    public string $status = 'draft';
    public string $layout = 'default';
    public bool $is_featured = false;
    public int $sort_order = 0;
    public string $meta_title = '';
    public string $meta_description = '';
    public $cover_image;
    public ?string $existing_cover = null;

    // Menu settings
    public bool $show_in_menu = false;
    public string $menu_type = ''; // '', 'main', 'child'
    public ?int $menu_parent_id = null;

    public function mount(?Page $page = null)
    {
        if ($page && $page->exists) {
            $this->isEdit = true;
            $this->page = $page;
            $this->fill([
                'title' => $page->title,
                'slug' => $page->slug,
                'excerpt' => $page->excerpt ?? '',
                'content' => $page->content ?? '',
                'status' => $page->status,
                'layout' => $page->layout ?? 'default',
                'is_featured' => $page->is_featured,
                'sort_order' => $page->sort_order,
                'meta_title' => $page->meta_title ?? '',
                'meta_description' => $page->meta_description ?? '',
                'existing_cover' => $page->cover_image,
                'show_in_menu' => $page->show_in_menu ?? false,
                'menu_type' => $page->menu_type ?? '',
                'menu_parent_id' => $page->menu_parent_id,
            ]);
        }
    }

    public function updatedTitle()
    {
        if (!$this->isEdit) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'layout' => 'required|in:' . implode(',', array_keys(Page::LAYOUT_OPTIONS)),
            'cover_image' => 'nullable|image|max:3072',
            'sort_order' => 'integer|min:0',
        ]);

        // Check slug uniqueness
        $slugQuery = Page::where('slug', $this->slug);
        if ($this->isEdit) {
            $slugQuery->where('id', '!=', $this->page->id);
        }
        if ($slugQuery->exists()) {
            $this->addError('slug', 'Slug sudah digunakan halaman lain.');
            return;
        }

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt ?: null,
            'content' => $this->content,
            'status' => $this->status,
            'layout' => $this->layout,
            'is_featured' => $this->is_featured,
            'sort_order' => $this->sort_order,
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
            'show_in_menu' => $this->show_in_menu,
            'menu_type' => $this->show_in_menu ? ($this->menu_type ?: 'main') : null,
            'menu_parent_id' => ($this->show_in_menu && $this->menu_type === 'child') ? $this->menu_parent_id : null,
        ];

        if ($this->cover_image) {
            // Delete old cover
            if ($this->existing_cover && \Storage::disk('public')->exists($this->existing_cover)) {
                \Storage::disk('public')->delete($this->existing_cover);
            }
            $data['cover_image'] = $this->cover_image->store('pages', 'public');
        }

        if ($this->status === 'published') {
            $data['published_at'] = $this->isEdit && $this->page->published_at
                ? $this->page->published_at
                : now();
        }

        if ($this->isEdit) {
            $this->page->update($data);
            $page = $this->page;
        } else {
            $data['author_id'] = Auth::id();
            $page = Page::create($data);
        }

        // Sync menu entry
        $page->syncMenu();

        session()->flash('success', $this->isEdit ? 'Halaman berhasil diperbarui.' : 'Halaman berhasil dibuat.');
        return redirect()->route('admin.pages');
    }

    public function removeCover()
    {
        if ($this->existing_cover && \Storage::disk('public')->exists($this->existing_cover)) {
            \Storage::disk('public')->delete($this->existing_cover);
        }
        if ($this->isEdit) {
            $this->page->update(['cover_image' => null]);
        }
        $this->existing_cover = null;
        $this->cover_image = null;
    }

    public function render()
    {
        // Get root-level header menus for parent selection
        $parentMenus = Menu::where('location', 'header')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('livewire.admin.page-form', [
            'layoutOptions' => Page::LAYOUT_OPTIONS,
            'menuTypeOptions' => Page::MENU_TYPE_OPTIONS,
            'parentMenus' => $parentMenus,
        ])->layout('layouts.app');
    }
}
