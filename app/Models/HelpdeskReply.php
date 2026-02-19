<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpdeskReply extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'attachment',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(HelpdeskTicket::class, 'ticket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getIsAdminReplyAttribute(): bool
    {
        return $this->user && in_array($this->user->role, ['admin', 'editor']);
    }
}
