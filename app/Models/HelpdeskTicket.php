<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HelpdeskTicket extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'category',
        'priority',
        'status',
        'assigned_to',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // ─── Constants ───
    public const CATEGORIES = [
        'teknis' => 'Teknis / Sistem',
        'paper' => 'Paper / Submission',
        'pembayaran' => 'Pembayaran',
        'akun' => 'Akun / Login',
        'lainnya' => 'Lainnya',
    ];

    public const PRIORITIES = [
        'rendah' => 'Rendah',
        'normal' => 'Normal',
        'tinggi' => 'Tinggi',
        'urgent' => 'Urgent',
    ];

    public const PRIORITY_COLORS = [
        'rendah' => 'gray',
        'normal' => 'blue',
        'tinggi' => 'orange',
        'urgent' => 'red',
    ];

    public const STATUSES = [
        'open' => 'Open',
        'in_progress' => 'Diproses',
        'resolved' => 'Selesai',
        'closed' => 'Ditutup',
    ];

    public const STATUS_COLORS = [
        'open' => 'yellow',
        'in_progress' => 'blue',
        'resolved' => 'green',
        'closed' => 'gray',
    ];

    // ─── Relations ───
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(HelpdeskReply::class, 'ticket_id');
    }

    // ─── Accessors ───
    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    public function getPriorityColorAttribute(): string
    {
        return self::PRIORITY_COLORS[$this->priority] ?? 'gray';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    // ─── Scopes ───
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }
}
