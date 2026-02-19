<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'conference_id', 'title', 'content', 'type', 'priority',
        'audience', 'status', 'is_pinned', 'attachment',
        'published_at', 'expires_at', 'created_by', 'views_count',
    ];

    protected $casts = [
        'audience' => 'array',
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    const TYPE_LABELS = [
        'info' => 'Informasi',
        'warning' => 'Peringatan',
        'success' => 'Sukses',
        'danger' => 'Penting',
        'deadline' => 'Deadline',
        'result' => 'Hasil',
    ];

    const TYPE_COLORS = [
        'info' => 'blue',
        'warning' => 'yellow',
        'success' => 'green',
        'danger' => 'red',
        'deadline' => 'orange',
        'result' => 'indigo',
    ];

    const PRIORITY_LABELS = [
        'low' => 'Rendah',
        'normal' => 'Normal',
        'high' => 'Tinggi',
        'urgent' => 'Mendesak',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    public function getTypeColorAttribute(): string
    {
        return self::TYPE_COLORS[$this->type] ?? 'gray';
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITY_LABELS[$this->priority] ?? $this->priority;
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopeForAudience($query, string $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->whereJsonContains('audience', 'all')
              ->orWhereJsonContains('audience', $role);
        });
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
