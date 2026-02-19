<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── General ──
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Prosiding LPKD-APJI', 'type' => 'text', 'label' => 'Nama Website', 'description' => 'Nama website yang ditampilkan di header dan title', 'sort_order' => 1],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Sistem Prosiding Online', 'type' => 'text', 'label' => 'Tagline', 'description' => 'Tagline atau deskripsi singkat website', 'sort_order' => 2],
            ['group' => 'general', 'key' => 'site_logo', 'value' => null, 'type' => 'image', 'label' => 'Logo Website', 'description' => 'Logo utama website (format PNG/SVG, maks 2MB)', 'sort_order' => 3],
            ['group' => 'general', 'key' => 'site_favicon', 'value' => null, 'type' => 'image', 'label' => 'Favicon', 'description' => 'Ikon browser (format ICO/PNG, 32x32px)', 'sort_order' => 4],
            ['group' => 'general', 'key' => 'site_description', 'value' => 'Sistem manajemen prosiding online untuk pengelolaan paper, review, dan publikasi.', 'type' => 'textarea', 'label' => 'Deskripsi Website', 'description' => 'Deskripsi website untuk SEO', 'sort_order' => 5],
            ['group' => 'general', 'key' => 'site_keywords', 'value' => 'prosiding, konferensi, paper, jurnal, publikasi', 'type' => 'text', 'label' => 'Keywords SEO', 'description' => 'Kata kunci untuk SEO, pisahkan dengan koma', 'sort_order' => 6],
            ['group' => 'general', 'key' => 'contact_email', 'value' => 'info@prosiding.test', 'type' => 'text', 'label' => 'Email Kontak', 'description' => 'Email kontak yang ditampilkan di website', 'sort_order' => 7],
            ['group' => 'general', 'key' => 'contact_phone', 'value' => '', 'type' => 'text', 'label' => 'No. Telepon', 'description' => 'Nomor telepon kontak', 'sort_order' => 8],
            ['group' => 'general', 'key' => 'contact_address', 'value' => '', 'type' => 'textarea', 'label' => 'Alamat', 'description' => 'Alamat kantor / sekretariat', 'sort_order' => 9],
            ['group' => 'general', 'key' => 'footer_text', 'value' => '© 2026 Prosiding LPKD-APJI. All rights reserved.', 'type' => 'text', 'label' => 'Teks Footer', 'description' => 'Teks copyright di footer', 'sort_order' => 10],
            ['group' => 'general', 'key' => 'social_facebook', 'value' => '', 'type' => 'text', 'label' => 'Facebook URL', 'sort_order' => 11],
            ['group' => 'general', 'key' => 'social_instagram', 'value' => '', 'type' => 'text', 'label' => 'Instagram URL', 'sort_order' => 12],
            ['group' => 'general', 'key' => 'social_twitter', 'value' => '', 'type' => 'text', 'label' => 'Twitter/X URL', 'sort_order' => 13],
            ['group' => 'general', 'key' => 'social_youtube', 'value' => '', 'type' => 'text', 'label' => 'YouTube URL', 'sort_order' => 14],
            ['group' => 'general', 'key' => 'google_analytics', 'value' => '', 'type' => 'text', 'label' => 'Google Analytics ID', 'description' => 'Contoh: G-XXXXXXXXXX', 'sort_order' => 15],
            ['group' => 'general', 'key' => 'publication_info', 'value' => 'Setiap makalah yang diterima dipublikasikan di Prosiding Seminar Nasional.', 'type' => 'textarea', 'label' => 'Info Publikasi Prosiding', 'description' => 'Teks deskripsi publikasi prosiding di halaman utama (termasuk e-ISSN dll)', 'sort_order' => 16],
            ['group' => 'general', 'key' => 'selected_papers_info', 'value' => 'Makalah terpilih akan diterbitkan pada jurnal-jurnal terindeks SINTA dan Google Scholar.', 'type' => 'textarea', 'label' => 'Info Makalah Terpilih', 'description' => 'Teks deskripsi makalah terpilih di halaman utama (termasuk info jurnal dll)', 'sort_order' => 17],
            ['group' => 'general', 'key' => 'payment_bank_name', 'value' => '', 'type' => 'text', 'label' => 'Nama Bank', 'description' => 'Nama bank untuk pembayaran pendaftaran', 'sort_order' => 18],
            ['group' => 'general', 'key' => 'payment_bank_account', 'value' => '', 'type' => 'text', 'label' => 'No. Rekening', 'description' => 'Nomor rekening bank', 'sort_order' => 19],
            ['group' => 'general', 'key' => 'payment_account_holder', 'value' => '', 'type' => 'text', 'label' => 'Atas Nama Rekening', 'description' => 'Nama pemilik rekening bank', 'sort_order' => 20],
            ['group' => 'general', 'key' => 'payment_contact_phone', 'value' => '', 'type' => 'text', 'label' => 'Kontak Pembayaran (Telp/WA)', 'description' => 'Nomor telepon/WA yang bisa dihubungi terkait pembayaran', 'sort_order' => 21],
            ['group' => 'general', 'key' => 'payment_instructions', 'value' => '', 'type' => 'textarea', 'label' => 'Instruksi Pembayaran', 'description' => 'Catatan atau instruksi tambahan terkait pembayaran', 'sort_order' => 22],
            ['group' => 'general', 'key' => 'powered_by', 'value' => 'Powered by Laravel', 'type' => 'text', 'label' => 'Powered By', 'description' => 'Teks "Powered by" yang ditampilkan di footer website', 'sort_order' => 23],
            ['group' => 'general', 'key' => 'active_template', 'value' => 'default', 'type' => 'select', 'label' => 'Template Aktif', 'description' => 'Template/tema yang digunakan untuk halaman publik website', 'sort_order' => 24],
            ['group' => 'general', 'key' => 'site_language', 'value' => 'id', 'type' => 'select', 'label' => 'Bahasa Website', 'description' => 'Bahasa default website', 'options' => json_encode(['id' => 'Bahasa Indonesia', 'en' => 'English']), 'sort_order' => 25],

            // ── Email ──
            ['group' => 'email', 'key' => 'mail_mailer', 'value' => 'smtp', 'type' => 'select', 'label' => 'Mail Driver', 'description' => 'Metode pengiriman email', 'options' => json_encode(['smtp' => 'SMTP', 'sendmail' => 'Sendmail', 'mailgun' => 'Mailgun', 'ses' => 'Amazon SES', 'log' => 'Log (Testing)']), 'sort_order' => 1],
            ['group' => 'email', 'key' => 'mail_host', 'value' => 'smtp.gmail.com', 'type' => 'text', 'label' => 'SMTP Host', 'description' => 'Contoh: smtp.gmail.com, smtp.mailtrap.io', 'sort_order' => 2],
            ['group' => 'email', 'key' => 'mail_port', 'value' => '587', 'type' => 'number', 'label' => 'SMTP Port', 'description' => 'Port umum: 587 (TLS), 465 (SSL), 25', 'sort_order' => 3],
            ['group' => 'email', 'key' => 'mail_username', 'value' => '', 'type' => 'text', 'label' => 'SMTP Username', 'description' => 'Username atau email untuk autentikasi SMTP', 'sort_order' => 4],
            ['group' => 'email', 'key' => 'mail_password', 'value' => '', 'type' => 'text', 'label' => 'SMTP Password', 'description' => 'Password SMTP (untuk Gmail gunakan App Password)', 'sort_order' => 5],
            ['group' => 'email', 'key' => 'mail_encryption', 'value' => 'tls', 'type' => 'select', 'label' => 'Enkripsi', 'description' => 'Protokol enkripsi email', 'options' => json_encode(['tls' => 'TLS', 'ssl' => 'SSL', '' => 'Tidak Ada']), 'sort_order' => 6],
            ['group' => 'email', 'key' => 'mail_from_address', 'value' => 'noreply@prosiding.test', 'type' => 'text', 'label' => 'Alamat Pengirim', 'description' => 'Email address pengirim (From)', 'sort_order' => 7],
            ['group' => 'email', 'key' => 'mail_from_name', 'value' => 'Prosiding LPKD-APJI', 'type' => 'text', 'label' => 'Nama Pengirim', 'description' => 'Nama yang muncul sebagai pengirim email', 'sort_order' => 8],
            ['group' => 'email', 'key' => 'mail_notify_submission', 'value' => '1', 'type' => 'boolean', 'label' => 'Notifikasi Submission', 'description' => 'Kirim email saat ada paper baru', 'sort_order' => 9],
            ['group' => 'email', 'key' => 'mail_notify_review', 'value' => '1', 'type' => 'boolean', 'label' => 'Notifikasi Review', 'description' => 'Kirim email saat review selesai', 'sort_order' => 10],
            ['group' => 'email', 'key' => 'mail_notify_payment', 'value' => '1', 'type' => 'boolean', 'label' => 'Notifikasi Pembayaran', 'description' => 'Kirim email saat pembayaran diverifikasi', 'sort_order' => 11],
            ['group' => 'email', 'key' => 'mail_notify_status', 'value' => '1', 'type' => 'boolean', 'label' => 'Notifikasi Status Paper', 'description' => 'Kirim email saat status paper berubah', 'sort_order' => 12],

            // ── Theme ──
            ['group' => 'theme', 'key' => 'theme_primary_color', 'value' => '#2563eb', 'type' => 'color', 'label' => 'Warna Utama (Primary)', 'description' => 'Warna utama untuk tombol, link, dan elemen aktif', 'sort_order' => 1],
            ['group' => 'theme', 'key' => 'theme_secondary_color', 'value' => '#4f46e5', 'type' => 'color', 'label' => 'Warna Sekunder', 'description' => 'Warna sekunder untuk aksen dan elemen pendukung', 'sort_order' => 2],
            ['group' => 'theme', 'key' => 'theme_accent_color', 'value' => '#0891b2', 'type' => 'color', 'label' => 'Warna Aksen', 'description' => 'Warna aksen untuk highlight dan badge', 'sort_order' => 3],
            ['group' => 'theme', 'key' => 'theme_sidebar_bg', 'value' => '#ffffff', 'type' => 'color', 'label' => 'Background Sidebar', 'description' => 'Warna latar sidebar navigasi', 'sort_order' => 4],
            ['group' => 'theme', 'key' => 'theme_sidebar_text', 'value' => '#374151', 'type' => 'color', 'label' => 'Teks Sidebar', 'description' => 'Warna teks di sidebar', 'sort_order' => 5],
            ['group' => 'theme', 'key' => 'theme_header_bg', 'value' => '#ffffff', 'type' => 'color', 'label' => 'Background Header', 'description' => 'Warna latar header/topbar', 'sort_order' => 6],
            ['group' => 'theme', 'key' => 'theme_body_bg', 'value' => '#f3f4f6', 'type' => 'color', 'label' => 'Background Body', 'description' => 'Warna latar belakang halaman', 'sort_order' => 7],
            ['group' => 'theme', 'key' => 'theme_font_family', 'value' => 'Inter', 'type' => 'select', 'label' => 'Font Utama', 'description' => 'Jenis huruf utama website', 'options' => json_encode(['Inter' => 'Inter', 'Poppins' => 'Poppins', 'Roboto' => 'Roboto', 'Open Sans' => 'Open Sans', 'Nunito' => 'Nunito', 'Montserrat' => 'Montserrat', 'Lato' => 'Lato']), 'sort_order' => 8],
            ['group' => 'theme', 'key' => 'theme_border_radius', 'value' => '8', 'type' => 'select', 'label' => 'Border Radius', 'description' => 'Kelengkungan sudut elemen UI', 'options' => json_encode(['0' => 'Tajam (0px)', '4' => 'Sedikit (4px)', '8' => 'Sedang (8px)', '12' => 'Bulat (12px)', '16' => 'Sangat Bulat (16px)']), 'sort_order' => 9],
            ['group' => 'theme', 'key' => 'theme_login_bg_image', 'value' => null, 'type' => 'image', 'label' => 'Background Login', 'description' => 'Gambar latar halaman login/register', 'sort_order' => 10],
            ['group' => 'theme', 'key' => 'theme_custom_css', 'value' => '', 'type' => 'textarea', 'label' => 'Custom CSS', 'description' => 'CSS tambahan yang akan di-inject di semua halaman', 'sort_order' => 11],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
