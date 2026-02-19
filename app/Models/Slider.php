<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'description', 'image',
        'button_text', 'button_url', 'button_text_2', 'button_url_2',
        'text_position', 'text_color', 'overlay_color',
        'is_active', 'sort_order', 'start_date', 'end_date',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    const POSITIONS = [
        'left'   => 'Kiri',
        'center' => 'Tengah',
        'right'  => 'Kanan',
    ];

    const TEXT_COLORS = [
        'white' => 'Putih',
        'dark'  => 'Gelap',
    ];

    // ── Scopes ──
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderBy('sort_order');
    }

    // ── Accessor ──
    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }
}
