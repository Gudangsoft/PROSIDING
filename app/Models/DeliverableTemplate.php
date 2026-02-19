<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliverableTemplate extends Model
{
    protected $fillable = [
        'conference_id', 'type', 'label', 'file_path',
        'original_name', 'description', 'sort_order',
    ];

    const TYPE_OPTIONS = [
        'poster' => 'Template Poster',
        'ppt' => 'Template PPT Presentasi',
        'final_paper' => 'Template Full Paper Final',
        'prosiding_book' => 'Template Buku Prosiding',
        'certificate' => 'Template Sertifikat',
        'other' => 'Lainnya',
    ];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }
}
