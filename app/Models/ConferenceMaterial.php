<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConferenceMaterial extends Model
{
    protected $fillable = [
        'conference_id',
        'title',
        'description',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'is_active',
        'sort_order',
        'uploaded_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'materi'     => 'Materi',
            'ppt'        => 'Presentasi (PPT)',
            'sertifikat' => 'Sertifikat',
            'lainnya'    => 'Lainnya',
            default      => ucfirst($this->type),
        };
    }

    public function getTypeIcon(): string
    {
        return match ($this->type) {
            'materi'     => '📄',
            'ppt'        => '📊',
            'sertifikat' => '🏆',
            'lainnya'    => '📎',
            default      => '📁',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
