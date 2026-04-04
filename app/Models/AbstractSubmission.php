<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbstractSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'conference_id', 'title', 'abstract', 'keywords',
        'topic', 'authors_meta', 'abstract_file_path', 'abstract_file_name',
        'status', 'reviewer_notes', 'reviewed_by', 'reviewed_at', 'paper_id',
    ];

    protected $casts = [
        'authors_meta' => 'array',
        'reviewed_at'  => 'datetime',
    ];

    const STATUS_LABELS = [
        'pending'           => 'Menunggu Review',
        'approved'          => 'Disetujui',
        'rejected'          => 'Ditolak',
        'revision_required' => 'Perlu Revisi',
    ];

    const STATUS_COLORS = [
        'pending'           => 'yellow',
        'approved'          => 'green',
        'rejected'          => 'red',
        'revision_required' => 'orange',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class, 'abstract_id');
    }
}
