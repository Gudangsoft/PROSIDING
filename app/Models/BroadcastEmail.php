<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BroadcastEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sent_by', 'subject', 'body', 'filter',
        'recipient_count', 'status', 'sent_at',
    ];

    protected $casts = [
        'filter'  => 'array',
        'sent_at' => 'datetime',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
