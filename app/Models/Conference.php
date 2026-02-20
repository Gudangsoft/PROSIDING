<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conference extends Model
{
    protected $fillable = [
        'name', 'acronym', 'theme', 'description',
        'start_date', 'start_time', 'end_date', 'end_time',
        'venue', 'venue_type', 'online_url', 'city',
        'organizer', 'cover_image', 'logo', 'brochure',
        'payment_bank_name', 'payment_bank_account', 'payment_account_holder',
        'payment_contact_phone', 'payment_instructions', 'payment_methods',
        'status', 'is_active', 'created_by',
        'loa_generation_mode', 'certificate_generation_mode',
        'visible_sections', 'hidden_speaker_types',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'payment_methods' => 'array',
        'visible_sections' => 'array',
        'hidden_speaker_types' => 'array',
    ];

    /**
     * All available sections that can be toggled on/off.
     */
    public const SECTIONS = [
        'hero'          => 'Hero / Slider',
        'info_cards'    => 'Info Cards (Tanggal Penting)',
        'about'         => 'Tentang Konferensi',
        'speakers'      => 'Keynote Speakers',
        'committees'    => 'Komite / Panitia',
        'news'          => 'Berita & Pengumuman',
        'registration'  => 'Registrasi / Paket Harga',
        'journals'      => 'Jurnal Publikasi',
        'cta'           => 'Call to Action',
    ];

    /**
     * Check if a specific section should be visible on the public website.
     * Returns true if visible_sections is null (all visible) or the section ID is in the list.
     */
    public function isSectionVisible(string $sectionId): bool
    {
        if ($this->visible_sections === null) {
            return true;
        }
        return in_array($sectionId, $this->visible_sections, true);
    }

    /**
     * Check if a speaker type block should be visible on the public website.
     * Returns true if hidden_speaker_types is null/empty or the type is NOT in the hidden list.
     */
    public function isSpeakerTypeVisible(string $type): bool
    {
        if (empty($this->hidden_speaker_types)) {
            return true;
        }
        return !in_array($type, $this->hidden_speaker_types, true);
    }

    const STATUS_LABELS = [
        'draft' => 'Draft',
        'published' => 'Dipublikasikan',
        'archived' => 'Diarsipkan',
    ];

    const VENUE_TYPE_LABELS = [
        'offline' => 'Offline (Luring)',
        'online' => 'Online (Daring)',
        'hybrid' => 'Hybrid',
    ];

    const VENUE_TYPE_ICONS = [
        'offline' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z',
        'online' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'hybrid' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9',
    ];

    const VENUE_TYPE_COLORS = [
        'offline' => 'bg-green-100 text-green-700',
        'online' => 'bg-blue-100 text-blue-700',
        'hybrid' => 'bg-purple-100 text-purple-700',
    ];

    public function getVenueTypeLabelAttribute(): string
    {
        return self::VENUE_TYPE_LABELS[$this->venue_type] ?? ucfirst($this->venue_type ?? 'offline');
    }

    public function getVenueDisplayAttribute(): string
    {
        $parts = [];
        if ($this->venue_type === 'online') {
            $parts[] = '(Link tersedia di Dashboard)';
        } elseif ($this->venue_type === 'hybrid') {
            if ($this->venue) $parts[] = $this->venue;
            if ($this->city) $parts[] = ($this->venue ? ', ' : '') . $this->city;
        } else {
            if ($this->venue) $parts[] = $this->venue;
            if ($this->city) $parts[] = ($this->venue ? ', ' : '') . $this->city;
        }
        return implode('', $parts) ?: '';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getFormattedTimeAttribute(): ?string
    {
        if (!$this->start_time) return null;
        $start = \Carbon\Carbon::parse($this->start_time)->format('H:i');
        if ($this->end_time) {
            $end = \Carbon\Carbon::parse($this->end_time)->format('H:i');
            return "{$start} - {$end} WIB";
        }
        return "{$start} WIB";
    }

    public function getDateTimeDisplayAttribute(): string
    {
        $parts = [];
        if ($this->start_date) {
            $dateStr = $this->start_date->translatedFormat('d F Y');
            if ($this->end_date && $this->end_date->ne($this->start_date)) {
                $dateStr .= ' — ' . $this->end_date->translatedFormat('d F Y');
            }
            $parts[] = $dateStr;
        }
        if ($this->formatted_time) {
            $parts[] = $this->formatted_time;
        }
        return implode(', ', $parts) ?: '-';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function importantDates(): HasMany
    {
        return $this->hasMany(ImportantDate::class)->orderBy('sort_order')->orderBy('date');
    }

    public function committees(): HasMany
    {
        return $this->hasMany(Committee::class)->orderBy('sort_order');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class)->orderBy('sort_order');
    }

    public function keynoteSpeakers(): HasMany
    {
        return $this->hasMany(KeynoteSpeaker::class)->orderBy('sort_order');
    }

    public function registrationPackages(): HasMany
    {
        return $this->hasMany(RegistrationPackage::class)->orderBy('sort_order');
    }

    public function guideline(): HasOne
    {
        return $this->hasOne(SubmissionGuideline::class);
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function deliverableTemplates(): HasMany
    {
        return $this->hasMany(DeliverableTemplate::class)->orderBy('sort_order');
    }

    public function papers(): HasMany
    {
        return $this->hasMany(Paper::class);
    }

    public function journalPublications(): HasMany
    {
        return $this->hasMany(JournalPublication::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getDateRangeAttribute(): string
    {
        if (!$this->start_date) return '-';
        if (!$this->end_date || $this->start_date->eq($this->end_date)) {
            $date = $this->start_date->format('d F Y');
        } elseif ($this->start_date->month === $this->end_date->month) {
            $date = $this->start_date->format('d') . ' - ' . $this->end_date->format('d F Y');
        } else {
            $date = $this->start_date->format('d M') . ' - ' . $this->end_date->format('d M Y');
        }
        if ($this->formatted_time) {
            return $date . ', ' . $this->formatted_time;
        }
        return $date;
    }

    public function getStatisticsAttribute(): array
    {
        return [
            'dates' => $this->importantDates()->count(),
            'committees' => $this->committees()->count(),
            'topics' => $this->topics()->count(),
            'speakers' => $this->keynoteSpeakers()->count(),
        ];
    }
}
