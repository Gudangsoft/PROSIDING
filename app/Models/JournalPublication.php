<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalPublication extends Model
{
    protected $fillable = [
        'conference_id', 'name', 'sinta_rank',
        'logo', 'url', 'description',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getSintaBadgeColorAttribute(): string
    {
        return match ($this->sinta_rank) {
            'SINTA 1' => 'bg-red-100 text-red-700 border-red-200',
            'SINTA 2' => 'bg-orange-100 text-orange-700 border-orange-200',
            'SINTA 3' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'SINTA 4' => 'bg-green-100 text-green-700 border-green-200',
            'SINTA 5' => 'bg-blue-100 text-blue-700 border-blue-200',
            'SINTA 6' => 'bg-gray-100 text-gray-700 border-gray-200',
            default => 'bg-purple-100 text-purple-700 border-purple-200',
        };
    }
}
