<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaperStatusLog extends Model
{
    protected $fillable = [
        'paper_id', 'user_id', 'from_status', 'to_status', 'action', 'notes', 'meta', 'occurred_at',
    ];

    protected $casts = [
        'meta'        => 'array',
        'occurred_at' => 'datetime',
    ];

    const ACTIONS = [
        'status_changed'    => 'Status Diubah',
        'review_submitted'  => 'Review Diserahkan',
        'payment_uploaded'  => 'Bukti Bayar Diupload',
        'payment_verified'  => 'Pembayaran Diverifikasi',
        'payment_rejected'  => 'Pembayaran Ditolak',
        'comment_added'     => 'Komentar Ditambah',
        'file_uploaded'     => 'File Diupload',
        'assigned_reviewer' => 'Reviewer Ditugaskan',
        'submitted'         => 'Paper Disubmit',
        'ojs_submitted'     => 'Dikirim ke OJS',
    ];

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return self::ACTIONS[$this->action] ?? ($this->action ?? 'Perubahan');
    }

    /**
     * Log a status change for a paper.
     */
    public static function log(
        int     $paperId,
        ?string $fromStatus,
        string  $toStatus,
        string  $action = 'status_changed',
        ?string $notes = null,
        array   $meta = [],
        ?int    $userId = null
    ): self {
        return self::create([
            'paper_id'    => $paperId,
            'user_id'     => $userId ?? auth()->id(),
            'from_status' => $fromStatus,
            'to_status'   => $toStatus,
            'action'      => $action,
            'notes'       => $notes,
            'meta'        => $meta ?: null,
            'occurred_at' => now(),
        ]);
    }
}
