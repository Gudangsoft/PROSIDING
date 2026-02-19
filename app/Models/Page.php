<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content',
        'cover_image', 'status', 'is_featured',
        'show_in_menu', 'menu_type', 'menu_parent_id',
        'meta_title', 'meta_description',
        'layout', 'sort_order', 'views_count',
        'author_id', 'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'show_in_menu' => 'boolean',
        'published_at' => 'datetime',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    const STATUS_LABELS = [
        'draft' => 'Draf',
        'published' => 'Dipublikasi',
    ];

    const LAYOUT_OPTIONS = [
        'default' => 'Default (Full Width)',
        'sidebar' => 'Dengan Sidebar',
        'narrow' => 'Narrow / Artikel',
        'blank' => 'Blank (Tanpa Header/Footer)',
    ];

    const MENU_TYPE_OPTIONS = [
        '' => 'Tidak Tampil di Menu',
        'main' => 'Menu Utama',
        'child' => 'Sub Menu (di bawah menu lain)',
    ];

    /* ── Relationships ── */

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function menuParent()
    {
        return $this->belongsTo(Menu::class, 'menu_parent_id');
    }

    /**
     * Find the Menu record that points to this page's URL.
     */
    public function findLinkedMenu(): ?Menu
    {
        return Menu::where('url', '/page/' . $this->slug)->first();
    }

    /**
     * Sync menu entry based on show_in_menu / menu_type / menu_parent_id.
     */
    public function syncMenu(): void
    {
        $existingMenu = $this->findLinkedMenu();

        // Remove menu if not shown or page is draft
        if (!$this->show_in_menu || $this->status !== self::STATUS_PUBLISHED) {
            $existingMenu?->delete();
            return;
        }

        $data = [
            'title' => $this->title,
            'url' => '/page/' . $this->slug,
            'target' => '_self',
            'location' => 'header',
            'parent_id' => $this->menu_type === 'child' ? $this->menu_parent_id : null,
            'is_active' => true,
            'sort_order' => $this->sort_order,
        ];

        if ($existingMenu) {
            $existingMenu->update($data);
        } else {
            Menu::create($data);
        }
    }

    /* ── Scopes ── */

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                     ->where(function ($q) {
                         $q->whereNull('published_at')
                           ->orWhere('published_at', '<=', now());
                     });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /* ── Accessors ── */

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getUrlAttribute(): string
    {
        return route('page.show', $this->slug);
    }

    /* ── Boot ── */

    protected static function booted()
    {
        static::creating(function (Page $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
            // Ensure unique slug
            $base = $page->slug;
            $i = 1;
            while (static::where('slug', $page->slug)->exists()) {
                $page->slug = $base . '-' . $i++;
            }
            if (empty($page->author_id) && auth()->check()) {
                $page->author_id = auth()->id();
            }
        });
    }
}
