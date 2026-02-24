<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'conference_id', 'paper_id', 'type',
        'certificate_number', 'recipient_name', 'file_path', 'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    const TYPE_LABELS = [
        'participant' => 'Peserta',
        'presenter'   => 'Presenter',
        'reviewer'    => 'Reviewer',
        'committee'   => 'Panitia',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function paper(): BelongsTo
    {
        return $this->belongsTo(Paper::class);
    }

    public static function generateNumber(string $prefix = 'CERT'): string
    {
        $year  = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return "{$prefix}/{$year}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
