<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Committee extends Model
{
    protected $fillable = [
        'conference_id', 'name', 'title', 'institution',
        'email', 'photo', 'type', 'role', 'sort_order',
    ];

    const TYPE_LABELS = [
        'steering' => 'Steering Committee',
        'organizing' => 'Organizing Committee',
        'scientific' => 'Scientific Committee',
        'advisory' => 'Advisory Board',
        'reviewer_committee' => 'Reviewer Committee',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }
}
