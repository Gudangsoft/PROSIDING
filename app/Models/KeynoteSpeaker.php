<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeynoteSpeaker extends Model
{
    protected $fillable = [
        'conference_id', 'type', 'name', 'title', 'institution',
        'bio', 'photo', 'topic', 'schedule', 'sort_order', 'show_on_web',
    ];

    protected $casts = [
        'schedule' => 'datetime',
        'show_on_web' => 'boolean',
    ];

    const TYPE_LABELS = [
        'opening_speech' => 'Opening Speech',
        'keynote_speaker' => 'Keynote Speaker',
        'narasumber' => 'Narasumber',
        'moderator_host' => 'Moderator & Host',
    ];

    const TYPE_COLORS = [
        'opening_speech' => 'purple',
        'keynote_speaker' => 'blue',
        'narasumber' => 'green',
        'moderator_host' => 'orange',
    ];

    const TYPE_ICONS = [
        'opening_speech' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
        'keynote_speaker' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z',
        'narasumber' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'moderator_host' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }
}
