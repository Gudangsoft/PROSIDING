<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevisionRequest extends Model
{
    protected $fillable = [
        'paper_id', 'created_by', 'note', 'deadline',
        'author_response', 'revised_file_path', 'resolved_at',
    ];

    protected $casts = [
        'deadline'    => 'date',
        'resolved_at' => 'datetime',
    ];

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isResolved(): bool
    {
        return $this->resolved_at !== null;
    }
}
