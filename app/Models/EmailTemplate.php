<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTemplate extends Model
{
    protected $fillable = [
        'conference_id',
        'key',
        'subject',
        'body',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Email type definitions: key => [label, description, default_subject, variables]
    public const TYPES = [
        'welcome' => [
            'label'   => 'Selamat Datang',
            'desc'    => 'Dikirim saat user mendaftar akun baru',
            'icon'    => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            'color'   => 'blue',
            'default_subject' => 'Selamat Datang di {{ $conference_name }}',
            'vars'    => ['{{ $name }}', '{{ $email }}', '{{ $conference_name }}', '{{ $login_url }}'],
        ],
        'payment_verified' => [
            'label'   => 'Pembayaran Diverifikasi',
            'desc'    => 'Dikirim saat admin memverifikasi pembayaran peserta',
            'icon'    => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'color'   => 'green',
            'default_subject' => 'Pembayaran Anda Telah Diverifikasi - {{ $conference_name }}',
            'vars'    => ['{{ $name }}', '{{ $conference_name }}', '{{ $package_name }}', '{{ $amount }}', '{{ $dashboard_url }}'],
        ],
        'payment_reminder' => [
            'label'   => 'Pengingat Pembayaran',
            'desc'    => 'Dikirim sebagai pengingat pembayaran yang belum selesai',
            'icon'    => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'color'   => 'yellow',
            'default_subject' => 'Pengingat: Selesaikan Pembayaran Anda - {{ $conference_name }}',
            'vars'    => ['{{ $name }}', '{{ $conference_name }}', '{{ $package_name }}', '{{ $amount }}', '{{ $payment_url }}'],
        ],
        'invoice_created' => [
            'label'   => 'Invoice Dibuat',
            'desc'    => 'Dikirim saat invoice/tagihan baru dibuat untuk peserta',
            'icon'    => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'color'   => 'indigo',
            'default_subject' => 'Invoice Pendaftaran - {{ $conference_name }}',
            'vars'    => ['{{ $name }}', '{{ $conference_name }}', '{{ $invoice_number }}', '{{ $amount }}', '{{ $due_date }}', '{{ $dashboard_url }}'],
        ],
        'paper_submitted' => [
            'label'   => 'Paper Diterima',
            'desc'    => 'Dikirim saat penulis berhasil submit paper',
            'icon'    => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12',
            'color'   => 'purple',
            'default_subject' => 'Paper Anda Berhasil Dikirim - {{ $conference_name }}',
            'vars'    => ['{{ $name }}', '{{ $conference_name }}', '{{ $paper_title }}', '{{ $submission_id }}', '{{ $dashboard_url }}'],
        ],
        'paper_accepted' => [
            'label'   => 'Paper Diterima (Accept)',
            'desc'    => 'Dikirim saat paper penulis diterima/disetujui reviewer',
            'icon'    => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
            'color'   => 'emerald',
            'default_subject' => 'Selamat! Paper Anda Diterima - {{ $conference_name }}',
            'vars'    => ['{{ $name }}', '{{ $conference_name }}', '{{ $paper_title }}', '{{ $loa_url }}', '{{ $dashboard_url }}'],
        ],
        'paper_rejected' => [
            'label'   => 'Paper Ditolak',
            'desc'    => 'Dikirim saat paper penulis ditolak oleh reviewer',
            'icon'    => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            'color'   => 'red',
            'default_subject' => 'Update Status Paper Anda - {{ $conference_name }}',
            'vars'    => ['{{ $name }}', '{{ $conference_name }}', '{{ $paper_title }}', '{{ $review_notes }}', '{{ $dashboard_url }}'],
        ],
        'new_event' => [
            'label'   => 'Kegiatan Baru',
            'desc'    => 'Dikirim ke subscriber saat kegiatan/seminar baru dipublikasikan',
            'icon'    => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
            'color'   => 'orange',
            'default_subject' => 'Kegiatan Baru: {{ $conference_name }}',
            'vars'    => ['{{ $conference_name }}', '{{ $conference_date }}', '{{ $conference_venue }}', '{{ $register_url }}'],
        ],
    ];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    /**
     * Get template for a given conference and key, or return null if not customized.
     */
    public static function forConference(?int $conferenceId, string $key): ?self
    {
        if (!$conferenceId) return null;
        return static::where('conference_id', $conferenceId)
            ->where('key', $key)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Render the body replacing template variables.
     * Variables can be written as {{ $varName }} or {varName} in the template.
     */
    public function render(array $vars = []): string
    {
        return $this->replaceVars($this->body, $vars);
    }

    /**
     * Render the subject replacing template variables.
     */
    public function renderSubject(array $vars = []): string
    {
        return $this->replaceVars($this->subject, $vars);
    }

    private function replaceVars(string $text, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $text = str_replace('{{ $' . $key . ' }}', $value ?? '', $text);
            $text = str_replace('{' . $key . '}', $value ?? '', $text);
        }
        return $text;
    }
}
