<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsappSetting extends Model
{
    protected $fillable = [
        'name', 'provider', 'api_key', 'api_url', 'device_id', 'sender_number',
        'is_active', 'test_number',
        'tpl_payment_reminder', 'tpl_payment_verified',
        'tpl_paper_accepted', 'tpl_paper_rejected', 'tpl_paper_revision',
        'tpl_review_assigned', 'tpl_abstract_approved', 'tpl_abstract_rejected',
        'notify_payment_reminder', 'notify_payment_verified', 'notify_paper_status',
        'notify_review_assigned', 'notify_abstract_status',
    ];

    protected $casts = [
        'is_active'               => 'boolean',
        'notify_payment_reminder' => 'boolean',
        'notify_payment_verified' => 'boolean',
        'notify_paper_status'     => 'boolean',
        'notify_review_assigned'  => 'boolean',
        'notify_abstract_status'  => 'boolean',
    ];

    const PROVIDERS = [
        'fonnte' => 'Fonnte',
        'wablas' => 'Wablas',
        'custom' => 'Custom / Other',
    ];

    const TEMPLATES = [
        'tpl_payment_reminder'  => 'Reminder Pembayaran',
        'tpl_payment_verified'  => 'Pembayaran Terverifikasi',
        'tpl_paper_accepted'    => 'Paper Diterima',
        'tpl_paper_rejected'    => 'Paper Ditolak',
        'tpl_paper_revision'    => 'Revisi Paper',
        'tpl_review_assigned'   => 'Assigned Review',
        'tpl_abstract_approved' => 'Abstrak Disetujui',
        'tpl_abstract_rejected' => 'Abstrak Ditolak',
    ];

    const DEFAULT_TEMPLATES = [
        'tpl_payment_reminder' =>
            "Yth. *{nama}*,\n\nKami mengingatkan bahwa pembayaran untuk paper *{judul_paper}* pada *{konferensi}* sebesar *Rp {nominal}* belum kami terima.\n\nBatas waktu pembayaran: *{deadline}*\n\nSilakan upload bukti pembayaran melalui sistem kami.\n\nTerima kasih.",

        'tpl_payment_verified' =>
            "Yth. *{nama}*,\n\nPembayaran Anda untuk paper *{judul_paper}* pada *{konferensi}* telah *TERVERIFIKASI* ✅\n\nNo. Invoice: {invoice}\nJumlah: Rp {nominal}\n\nTerima kasih atas pembayaran Anda.",

        'tpl_paper_accepted' =>
            "Yth. *{nama}*,\n\nSelamat! 🎉 Paper Anda *{judul_paper}* telah *DITERIMA* pada *{konferensi}*.\n\nSelanjutnya, Anda akan mendapatkan invoice untuk pembayaran. Silakan pantau sistem kami.\n\nTerima kasih.",

        'tpl_paper_rejected' =>
            "Yth. *{nama}*,\n\nKami mohon maaf, paper Anda *{judul_paper}* pada *{konferensi}* tidak dapat diterima setelah melalui proses review.\n\nUntuk informasi lebih lanjut silakan login ke sistem kami.\n\nTerima kasih atas partisipasinya.",

        'tpl_paper_revision' =>
            "Yth. *{nama}*,\n\nPaper Anda *{judul_paper}* pada *{konferensi}* memerlukan *REVISI* ✏️\n\nSilakan login ke sistem untuk melihat catatan revisi dari reviewer dan upload versi revisi Anda.\n\nTerima kasih.",

        'tpl_review_assigned' =>
            "Yth. *{nama}*,\n\nAnda mendapatkan tugas review paper baru pada *{konferensi}*:\n📄 *{judul_paper}*\n\nSilakan login ke sistem untuk melakukan review.\n\nTerima kasih.",

        'tpl_abstract_approved' =>
            "Yth. *{nama}*,\n\nAbstrak Anda *{judul}* pada *{konferensi}* telah *DISETUJUI* ✅\n\nSilakan segera submit full paper Anda melalui sistem.\n\nTerima kasih.",

        'tpl_abstract_rejected' =>
            "Yth. *{nama}*,\n\nAbstrak Anda *{judul}* pada *{konferensi}* *tidak dapat diterima*.\n\nCatatan: {catatan}\n\nTerima kasih atas partisipasinya.",
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(WhatsappLog::class, 'setting_id');
    }

    /** Get the currently active setting */
    public static function active(): ?self
    {
        return self::where('is_active', true)->first();
    }

    public function getProviderLabelAttribute(): string
    {
        return self::PROVIDERS[$this->provider] ?? $this->provider;
    }

    public function getApiEndpointAttribute(): string
    {
        return match ($this->provider) {
            'fonnte' => 'https://api.fonnte.com/send',
            'wablas' => $this->api_url ?: 'https://solo.wablas.com/api/send-message',
            default  => $this->api_url ?: '',
        };
    }
}
