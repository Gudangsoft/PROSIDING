<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionGuideline extends Model
{
    protected $fillable = [
        'conference_id', 'content', 'template_file',
        'max_pages', 'min_pages', 'paper_format',
        'citation_style', 'additional_notes',
    ];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }
}
