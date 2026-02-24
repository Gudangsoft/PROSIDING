<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RubricCriterion extends Model
{
    protected $fillable = [
        'review_rubric_id', 'label', 'description', 'weight', 'max_score', 'sort_order',
    ];

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(ReviewRubric::class, 'review_rubric_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(ReviewCriterionScore::class, 'rubric_criterion_id');
    }
}
