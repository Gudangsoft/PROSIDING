<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappLog extends Model
{
    protected $fillable = [
        'setting_id', 'to', 'recipient_name', 'type', 'message',
        'status', 'api_response', 'user_id', 'paper_id', 'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    const TYPES = [
        'payment_reminder'  => 'Reminder Pembayaran',
        'payment_verified'  => 'Pembayaran Terverifikasi',
        'paper_status'      => 'Status Paper',
        'review_assigned'   => 'Assigned Review',
        'abstract_status'   => 'Status Abstrak',
        'test'              => 'Test',
        'manual'            => 'Manual',
    ];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(WhatsappSetting::class, 'setting_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
