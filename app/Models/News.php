<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'conference_id', 'title', 'slug', 'excerpt', 'content',
        'cover_image', 'category', 'status', 'is_featured',
        'is_pinned', 'published_at', 'author_id', 'views_count',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
    ];

    const CATEGORY_LABELS = [
        'general' => 'Umum',
        'call_for_papers' => 'Call for Papers',
        'update' => 'Update',
        'result' => 'Hasil',
        'event' => 'Acara',
        'publication' => 'Publikasi',
    ];

    const CATEGORY_COLORS = [
        'general' => 'gray',
        'call_for_papers' => 'blue',
        'update' => 'yellow',
        'result' => 'green',
        'event' => 'purple',
        'publication' => 'indigo',
    ];

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORY_LABELS[$this->category] ?? $this->category;
    }

    public function getCategoryColorAttribute(): string
    {
        return self::CATEGORY_COLORS[$this->category] ?? 'gray';
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
