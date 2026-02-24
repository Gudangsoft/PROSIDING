<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaperSimilarity extends Model
{
    protected $fillable = [
        'paper_id', 'similar_paper_id', 'similarity_percent', 'compared_field', 'checked_at',
    ];

    protected $casts = [
        'checked_at'         => 'datetime',
        'similarity_percent' => 'float',
    ];

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    public function similarPaper(): BelongsTo
    {
        return $this->belongsTo(Paper::class, 'similar_paper_id');
    }
}
