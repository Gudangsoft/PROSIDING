<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReviewRubric extends Model
{
    protected $fillable = [
        'conference_id', 'name', 'description', 'is_active', 'passing_score',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(RubricCriterion::class)->orderBy('sort_order');
    }

    /** Total max score considering all criteria */
    public function getTotalMaxScoreAttribute(): int
    {
        return $this->criteria->sum('max_score');
    }

    /** Weighted max score */
    public function getWeightedMaxAttribute(): float
    {
        return $this->criteria->sum(fn($c) => $c->weight * $c->max_score);
    }
}
