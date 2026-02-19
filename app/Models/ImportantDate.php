<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportantDate extends Model
{
    protected $fillable = [
        'conference_id', 'title', 'description', 'date', 'type', 'sort_order',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    const TYPE_LABELS = [
        'submission_deadline' => 'Batas Submission',
        'review_deadline' => 'Batas Review',
        'notification' => 'Notifikasi Hasil',
        'camera_ready' => 'Camera Ready',
        'early_bird' => 'Early Bird',
        'registration_deadline' => 'Batas Registrasi',
        'conference_date' => 'Tanggal Kegiatan',
        'other' => 'Lainnya',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function getIsPastAttribute(): bool
    {
        return $this->date->isPast();
    }
}
