<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deliverable extends Model
{
    protected $fillable = [
        'paper_id', 'user_id', 'type', 'direction',
        'file_path', 'original_name', 'notes', 'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    const TYPE_LABELS = [
        'poster' => 'Poster',
        'ppt' => 'PPT Presentasi',
        'final_paper' => 'Full Paper Final',
        'prosiding_book' => 'Buku Prosiding',
        'certificate' => 'Sertifikat',
    ];

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
