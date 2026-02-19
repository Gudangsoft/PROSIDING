<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'paper_id', 'reviewer_id', 'assigned_by', 'status',
        'comments', 'comments_for_editor', 'recommendation', 'score',
        'review_file_path', 'review_file_name', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    const RECOMMENDATION_LABELS = [
        'accept' => 'Accept',
        'minor_revision' => 'Minor Revision',
        'major_revision' => 'Major Revision',
        'reject' => 'Reject',
    ];

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
