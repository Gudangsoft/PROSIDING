<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'conference_id', 'name', 'slug', 'type', 'html_template',
        'background_image', 'signature_image', 'stamp_image', 'logo_image',
        'signature1_name', 'signature1_position',
        'signature2_image', 'signature2_name', 'signature2_position',
        'orientation', 'paper_size', 'settings', 'is_default', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    const TYPE_LABELS = [
        'all' => 'Semua Tipe',
        'participant' => 'Peserta',
        'presenter' => 'Presenter',
        'reviewer' => 'Reviewer',
        'committee' => 'Panitia',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($template) {
            if (empty($template->slug)) {
                $template->slug = Str::slug($template->name) . '-' . Str::random(6);
            }
        });
    }

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForType($query, string $type)
    {
        return $query->where(function ($q) use ($type) {
            $q->where('type', $type)->orWhere('type', 'all');
        });
    }

    /**
     * Render the certificate HTML with data
     */
    public function render(Certificate $certificate): string
    {
        $html = $this->html_template;

        // Replace placeholders
        $replacements = [
            '{{nama}}' => e($certificate->recipient_name),
            '{{nomor}}' => e($certificate->certificate_number),
            '{{tipe}}' => e($certificate->type_label),
            '{{konferensi}}' => e($certificate->conference?->name ?? ''),
            '{{tanggal}}' => $certificate->issued_at?->isoFormat('D MMMM Y') ?? date('d F Y'),
            '{{tahun}}' => date('Y'),
            '{{email}}' => e($certificate->user?->email ?? ''),
            '{{institusi}}' => e($certificate->user?->institution ?? ''),
            '{{ttd1_nama}}' => e($this->signature1_name ?? ''),
            '{{ttd1_jabatan}}' => e($this->signature1_position ?? ''),
            '{{ttd2_nama}}' => e($this->signature2_name ?? ''),
            '{{ttd2_jabatan}}' => e($this->signature2_position ?? ''),
        ];

        $html = str_replace(array_keys($replacements), array_values($replacements), $html);

        // Add images using base64 encoding for DomPDF compatibility
        if ($this->signature_image) {
            $html = str_replace('{{ttd}}', $this->getImageTag($this->signature_image, 55), $html);
            $html = str_replace('{{ttd1}}', $this->getImageTag($this->signature_image, 55), $html);
        } else {
            $html = str_replace(['{{ttd}}', '{{ttd1}}'], '', $html);
        }

        if ($this->signature2_image) {
            $html = str_replace('{{ttd2}}', $this->getImageTag($this->signature2_image, 55), $html);
        } else {
            $html = str_replace('{{ttd2}}', '', $html);
        }

        // Generate QR Code for verification
        $qrCodeHtml = $this->generateQrCode($certificate);
        $html = str_replace('{{qrcode}}', $qrCodeHtml, $html);

        // Remove stempel placeholder (deprecated)
        $html = str_replace('{{stempel}}', '', $html);

        if ($this->logo_image) {
            $html = str_replace('{{logo}}', $this->getImageTag($this->logo_image, 50), $html);
        } else {
            $html = str_replace('{{logo}}', '', $html);
        }

        if ($this->background_image) {
            $bgPath = storage_path('app/public/' . $this->background_image);
            if (file_exists($bgPath)) {
                $bgData = base64_encode(file_get_contents($bgPath));
                $bgMime = mime_content_type($bgPath);
                $html = str_replace('{{background}}', "data:{$bgMime};base64,{$bgData}", $html);
            }
        }

        return $html;
    }

    /**
     * Generate image tag with base64 encoded image for DomPDF
     */
    private function getImageTag(string $imagePath, int $maxHeight = 80): string
    {
        $fullPath = storage_path('app/public/' . $imagePath);
        
        if (!file_exists($fullPath)) {
            return '';
        }

        $imageData = base64_encode(file_get_contents($fullPath));
        $mimeType = mime_content_type($fullPath);
        
        return "<img src=\"data:{$mimeType};base64,{$imageData}\" style=\"max-height:{$maxHeight}px;\">";
    }

    /**
     * Generate QR Code for certificate verification
     */
    private function generateQrCode(Certificate $certificate): string
    {
        // Build verification URL
        $verifyUrl = url('/verify-certificate/' . $certificate->certificate_number);
        
        try {
            $qrCode = new QrCode(
                data: $verifyUrl,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Medium,
                size: 100,
                margin: 3,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                foregroundColor: new Color(0, 0, 0),
                backgroundColor: new Color(255, 255, 255)
            );

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            
            $qrDataUri = $result->getDataUri();
            
            return "<img src=\"{$qrDataUri}\" style=\"width:65px; height:65px;\">";
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Get predefined templates
     */
    public static function getPredefinedTemplates(): array
    {
        return [
            'elegant-gold' => [
                'name' => 'Elegant Gold',
                'preview' => 'Desain elegan dengan aksen emas dan border mewah',
                'html' => self::getElegantGoldTemplate(),
            ],
            'modern-blue' => [
                'name' => 'Modern Blue',
                'preview' => 'Desain modern minimalis dengan warna biru profesional',
                'html' => self::getModernBlueTemplate(),
            ],
            'classic-formal' => [
                'name' => 'Classic Formal',
                'preview' => 'Desain klasik formal cocok untuk acara akademik',
                'html' => self::getClassicFormalTemplate(),
            ],
            'gradient-purple' => [
                'name' => 'Gradient Purple',
                'preview' => 'Desain kontemporer dengan gradasi ungu',
                'html' => self::getGradientPurpleTemplate(),
            ],
            'minimal-clean' => [
                'name' => 'Minimal Clean',
                'preview' => 'Desain bersih dan simpel',
                'html' => self::getMinimalCleanTemplate(),
            ],
        ];
    }

    private static function getElegantGoldTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(135deg, #fffef0 0%, #fff8e1 100%);
            min-height: 100vh;
            box-sizing: border-box;
        }
        .certificate {
            border: 3px solid #c9a227;
            padding: 40px;
            position: relative;
            background: white;
            box-shadow: inset 0 0 0 1px #c9a227, inset 0 0 0 4px white, inset 0 0 0 5px #c9a227;
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: 10px; left: 10px; right: 10px; bottom: 10px;
            border: 1px solid #e8d9a0;
            pointer-events: none;
        }
        .corner {
            position: absolute;
            width: 60px;
            height: 60px;
            border: 3px solid #c9a227;
        }
        .corner-tl { top: 20px; left: 20px; border-right: none; border-bottom: none; }
        .corner-tr { top: 20px; right: 20px; border-left: none; border-bottom: none; }
        .corner-bl { bottom: 20px; left: 20px; border-right: none; border-top: none; }
        .corner-br { bottom: 20px; right: 20px; border-left: none; border-top: none; }
        .logo-section { text-align: center; margin-bottom: 20px; }
        .title {
            font-size: 42px;
            color: #c9a227;
            text-transform: uppercase;
            letter-spacing: 8px;
            margin: 20px 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .subtitle {
            font-size: 16px;
            color: #666;
            letter-spacing: 3px;
            margin-bottom: 30px;
        }
        .recipient-label {
            font-size: 14px;
            color: #888;
            margin-bottom: 10px;
        }
        .recipient-name {
            font-size: 36px;
            color: #2c3e50;
            font-weight: bold;
            border-bottom: 2px solid #c9a227;
            padding-bottom: 10px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .description {
            font-size: 16px;
            color: #555;
            line-height: 1.8;
            max-width: 600px;
            margin: 0 auto 30px;
        }
        .event-name {
            font-size: 20px;
            color: #c9a227;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
            padding: 0 40px;
        }
        .signature-block {
            text-align: center;
            min-width: 180px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
            font-size: 12px;
            color: #555;
        }
        .stamp-area {
            text-align: center;
        }
        .cert-number {
            font-size: 11px;
            color: #999;
            margin-top: 20px;
            letter-spacing: 1px;
        }
        .date {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>
        
        <div style="text-align: center;">
            <div class="logo-section">{{logo}}</div>
            <h1 class="title">Sertifikat</h1>
            <p class="subtitle">Certificate of {{tipe}}</p>
            
            <p class="recipient-label">Dengan bangga diberikan kepada:</p>
            <h2 class="recipient-name">{{nama}}</h2>
            
            <p class="description">
                Atas partisipasi dan kontribusinya sebagai <strong>{{tipe}}</strong> dalam kegiatan:
            </p>
            
            <p class="event-name">{{konferensi}}</p>
            <p class="date">{{tanggal}}</p>
        </div>
        
        <table style="width:100%; margin-top:40px; border-collapse:collapse;">
            <tr>
                <td style="width:33%; text-align:center; vertical-align:bottom; padding:10px;">
                    {{ttd1}}
                    <div style="border-top:1px solid #333; margin-top:10px; padding-top:5px; font-size:12px; color:#555;">
                        {{ttd1_nama}}<br><small>{{ttd1_jabatan}}</small>
                    </div>
                </td>
                <td style="width:34%; text-align:center; vertical-align:bottom; padding:10px;">
                    {{qrcode}}<br>
                    <small style="font-size:9px;color:#888;">Scan untuk verifikasi</small>
                </td>
                <td style="width:33%; text-align:center; vertical-align:bottom; padding:10px;">
                    {{ttd2}}
                    <div style="border-top:1px solid #333; margin-top:10px; padding-top:5px; font-size:12px; color:#555;">
                        {{ttd2_nama}}<br><small>{{ttd2_jabatan}}</small>
                    </div>
                </td>
            </tr>
        </table>
        
        <p class="cert-number" style="text-align:center;">No: {{nomor}}</p>
    </div>
</body>
</html>
HTML;
    }

    private static function getModernBlueTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Segoe UI', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f4f8;
            min-height: 100vh;
        }
        .certificate {
            background: white;
            position: relative;
            overflow: hidden;
            padding: 50px;
            min-height: calc(100vh - 100px);
        }
        .header-stripe {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 120px;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
        }
        .header-stripe::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 60px;
            background: white;
            transform: skewY(-2deg);
        }
        .content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding-top: 60px;
        }
        .logo-section {
            margin-bottom: 30px;
        }
        .badge {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 8px 24px;
            border-radius: 30px;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .title {
            font-size: 48px;
            font-weight: 300;
            color: #1e3a5f;
            margin: 0 0 10px;
            letter-spacing: 4px;
        }
        .divider {
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #2563eb, #60a5fa);
            margin: 20px auto;
            border-radius: 2px;
        }
        .recipient-label {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 15px;
        }
        .recipient-name {
            font-size: 38px;
            font-weight: 600;
            color: #1e293b;
            margin: 0 0 30px;
        }
        .description {
            font-size: 16px;
            color: #475569;
            line-height: 1.8;
            max-width: 550px;
            margin: 0 auto 20px;
        }
        .event-name {
            font-size: 22px;
            font-weight: 600;
            color: #2563eb;
            margin: 20px 0;
        }
        .date-badge {
            display: inline-block;
            background: #f1f5f9;
            padding: 10px 25px;
            border-radius: 8px;
            font-size: 14px;
            color: #475569;
        }
        .footer {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            margin-top: 50px;
        }
        .signature-block {
            text-align: center;
        }
        .signature-img {
            height: 60px;
            margin-bottom: 10px;
        }
        .signature-line {
            width: 150px;
            border-top: 2px solid #cbd5e1;
            padding-top: 8px;
            font-size: 12px;
            color: #64748b;
        }
        .stamp-area {
            margin: 0 30px;
        }
        .cert-number {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 30px;
            letter-spacing: 1px;
        }
        .side-accent {
            position: absolute;
            top: 150px;
            width: 5px;
            height: 200px;
            background: linear-gradient(180deg, #2563eb, transparent);
        }
        .side-accent.left { left: 30px; }
        .side-accent.right { right: 30px; }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header-stripe"></div>
        <div class="side-accent left"></div>
        <div class="side-accent right"></div>
        
        <div class="content">
            <div class="logo-section">{{logo}}</div>
            <span class="badge">Certificate of Achievement</span>
            <h1 class="title">SERTIFIKAT</h1>
            <div class="divider"></div>
            
            <p class="recipient-label">Diberikan kepada:</p>
            <h2 class="recipient-name">{{nama}}</h2>
            
            <p class="description">
                Sebagai <strong>{{tipe}}</strong> yang telah berpartisipasi aktif dalam:
            </p>
            
            <p class="event-name">{{konferensi}}</p>
            <div class="date-badge">📅 {{tanggal}}</div>
            
            <table style="width:100%; margin-top:40px; border-collapse:collapse;">
                <tr>
                    <td style="width:33%; text-align:center; vertical-align:bottom; padding:10px;">
                        {{ttd1}}
                        <div style="border-top:1px solid #333; margin-top:10px; padding-top:5px; font-size:12px; color:#555;">
                            {{ttd1_nama}}<br><small>{{ttd1_jabatan}}</small>
                        </div>
                    </td>
                    <td style="width:34%; text-align:center; vertical-align:bottom; padding:10px;">
                        {{qrcode}}<br>
                        <small style="font-size:9px;color:#94a3b8;">Scan untuk verifikasi</small>
                    </td>
                    <td style="width:33%; text-align:center; vertical-align:bottom; padding:10px;">
                        {{ttd2}}
                        <div style="border-top:1px solid #333; margin-top:10px; padding-top:5px; font-size:12px; color:#555;">
                            {{ttd2_nama}}<br><small>{{ttd2_jabatan}}</small>
                        </div>
                    </td>
                </tr>
            </table>
            
            <p class="cert-number">Certificate No: {{nomor}}</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private static function getClassicFormalTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            margin: 0;
            padding: 30px;
            background: #fefefe;
            min-height: 100vh;
            box-sizing: border-box;
        }
        .certificate {
            border: 2px solid #1a365d;
            padding: 40px;
            background: linear-gradient(to bottom, #ffffff 0%, #f7f9fc 100%);
            position: relative;
        }
        .inner-border {
            border: 1px solid #cbd5e0;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px double #1a365d;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-bottom: 15px;
        }
        .org-name {
            font-size: 18px;
            color: #1a365d;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .title {
            font-size: 40px;
            color: #1a365d;
            margin: 20px 0 10px;
            font-weight: normal;
            letter-spacing: 6px;
        }
        .type-badge {
            display: inline-block;
            background: #1a365d;
            color: white;
            padding: 5px 20px;
            font-size: 12px;
            letter-spacing: 2px;
        }
        .body-content {
            text-align: center;
            padding: 20px 0;
        }
        .presented-to {
            font-size: 14px;
            color: #4a5568;
            font-style: italic;
            margin-bottom: 15px;
        }
        .recipient-name {
            font-size: 34px;
            color: #1a365d;
            font-weight: bold;
            margin: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #1a365d;
            display: inline-block;
        }
        .description {
            font-size: 15px;
            color: #4a5568;
            line-height: 1.8;
            margin: 25px auto;
            max-width: 500px;
        }
        .event-name {
            font-size: 20px;
            color: #2c5282;
            font-weight: bold;
            margin: 15px 0;
            font-style: italic;
        }
        .date {
            font-size: 14px;
            color: #718096;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding: 0 20px;
        }
        .signature-block {
            text-align: center;
            flex: 1;
        }
        .signature-space {
            height: 50px;
            margin-bottom: 5px;
        }
        .signature-line {
            border-top: 1px solid #1a365d;
            padding-top: 5px;
            font-size: 12px;
            color: #4a5568;
            width: 150px;
            margin: 0 auto;
        }
        .signature-title {
            font-size: 11px;
            color: #718096;
            margin-top: 3px;
        }
        .stamp-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .cert-number {
            text-align: center;
            font-size: 10px;
            color: #a0aec0;
            margin-top: 20px;
            letter-spacing: 1px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="inner-border">
            <div class="header">
                <div class="logo-row">{{logo}}</div>
                <h1 class="title">SERTIFIKAT</h1>
                <span class="type-badge">{{tipe}}</span>
            </div>
            
            <div class="body-content">
                <p class="presented-to">Dengan ini menerangkan bahwa:</p>
                <h2 class="recipient-name">{{nama}}</h2>
                
                <p class="description">
                    Telah berpartisipasi sebagai <strong>{{tipe}}</strong> dan memberikan kontribusi yang berharga dalam kegiatan:
                </p>
                
                <p class="event-name">"{{konferensi}}"</p>
                <p class="date">Diselenggarakan pada {{tanggal}}</p>
            </div>
            
            <table style="width:100%; margin-top:30px; border-collapse:collapse;">
                <tr>
                    <td style="width:33%; text-align:center; vertical-align:bottom; padding:10px;">
                        {{ttd1}}
                        <div style="border-top:1px solid #333; margin-top:10px; padding-top:5px;">
                            <div style="font-size:12px;">{{ttd1_nama}}</div>
                            <div style="font-size:10px;color:#666;">{{ttd1_jabatan}}</div>
                        </div>
                    </td>
                    <td style="width:34%; text-align:center; vertical-align:bottom; padding:10px;">
                        {{qrcode}}<br>
                        <small style="font-size:9px;color:#666;">Scan untuk verifikasi</small>
                    </td>
                    <td style="width:33%; text-align:center; vertical-align:bottom; padding:10px;">
                        {{ttd2}}
                        <div style="border-top:1px solid #333; margin-top:10px; padding-top:5px;">
                            <div style="font-size:12px;">{{ttd2_nama}}</div>
                            <div style="font-size:10px;color:#666;">{{ttd2_jabatan}}</div>
                        </div>
                    </td>
                </tr>
            </table>
            
            <p class="cert-number">Nomor Sertifikat: {{nomor}}</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private static function getGradientPurpleTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .certificate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            padding: 30px;
            box-sizing: border-box;
        }
        .card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            text-align: center;
        }
        .ribbon {
            background: linear-gradient(90deg, #667eea, #764ba2);
            color: white;
            padding: 10px 40px;
            display: inline-block;
            border-radius: 30px;
            font-size: 12px;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .logo-section { margin-bottom: 20px; }
        .title {
            font-size: 46px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 10px 0 20px;
            letter-spacing: 3px;
        }
        .decorative-line {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #f093fb);
            margin: 0 auto 30px;
            border-radius: 2px;
        }
        .recipient-label {
            font-size: 14px;
            color: #718096;
            margin-bottom: 10px;
        }
        .recipient-name {
            font-size: 36px;
            font-weight: 600;
            color: #2d3748;
            margin: 0 0 25px;
            padding-bottom: 15px;
            border-bottom: 2px dashed #e2e8f0;
            display: inline-block;
        }
        .description {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.8;
            max-width: 500px;
            margin: 0 auto 20px;
        }
        .event-name {
            font-size: 22px;
            font-weight: 600;
            color: #764ba2;
            margin: 15px 0;
        }
        .date-box {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            padding: 12px 25px;
            border-radius: 10px;
            font-size: 14px;
            color: #4a5568;
        }
        .footer {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }
        .signature-block { text-align: center; }
        .signature-img { height: 50px; margin-bottom: 10px; }
        .signature-line {
            width: 140px;
            border-top: 2px solid #cbd5e0;
            padding-top: 8px;
            font-size: 12px;
            color: #718096;
        }
        .stamp-area { text-align: center; }
        .cert-id {
            margin-top: 30px;
            font-size: 11px;
            color: #a0aec0;
            letter-spacing: 1px;
        }
        .corner-deco {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(240,147,251,0.1));
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="card">
            <div class="logo-section">{{logo}}</div>
            <span class="ribbon">Certificate of {{tipe}}</span>
            <h1 class="title">SERTIFIKAT</h1>
            <div class="decorative-line"></div>
            
            <p class="recipient-label">Dengan hormat diberikan kepada:</p>
            <h2 class="recipient-name">{{nama}}</h2>
            
            <p class="description">
                Atas dedikasi dan partisipasinya sebagai <strong>{{tipe}}</strong> dalam:
            </p>
            
            <p class="event-name">{{konferensi}}</p>
            <div class="date-box">
                <span>📅</span>
                <span>{{tanggal}}</span>
            </div>
            
            <table style="width:100%; margin-top:30px; border-collapse:collapse;">
                <tr>
                    <td style="width:33%; text-align:center; vertical-align:bottom; padding:10px;">
                        {{ttd1}}
                        <div style="border-top:1px solid #333; margin-top:10px; padding-top:5px; font-size:12px; color:#555;">
                            {{ttd1_nama}}<br><small>{{ttd1_jabatan}}</small>
                        </div>
                    </td>
                    <td style="width:34%; text-align:center; vertical-align:bottom; padding:10px;">
                        {{qrcode}}<br>
                        <small style="font-size:9px;color:#a78bfa;">Scan untuk verifikasi</small>
                    </td>
                    <td style="width:33%; text-align:center; vertical-align:bottom; padding:10px;">
                        {{ttd2}}
                        <div style="border-top:1px solid #333; margin-top:10px; padding-top:5px; font-size:12px; color:#555;">
                            {{ttd2_nama}}<br><small>{{ttd2_jabatan}}</small>
                        </div>
                    </td>
                </tr>
            </table>
            
            <p class="cert-id">ID: {{nomor}}</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private static function getMinimalCleanTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 50px;
            background: white;
            min-height: 100vh;
            box-sizing: border-box;
        }
        .certificate {
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
        }
        .top-line {
            width: 100%;
            height: 4px;
            background: #111;
            margin-bottom: 40px;
        }
        .logo-section { margin-bottom: 30px; }
        .title {
            font-size: 14px;
            letter-spacing: 8px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .main-title {
            font-size: 52px;
            font-weight: 300;
            color: #111;
            margin: 0 0 30px;
            letter-spacing: 2px;
        }
        .recipient-label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
        }
        .recipient-name {
            font-size: 32px;
            font-weight: 500;
            color: #111;
            margin: 0 0 40px;
        }
        .description {
            font-size: 15px;
            color: #555;
            line-height: 1.8;
            max-width: 500px;
            margin: 0 auto 30px;
        }
        .event-name {
            font-size: 18px;
            font-weight: 500;
            color: #333;
            margin: 20px 0;
        }
        .date {
            font-size: 13px;
            color: #888;
            margin-bottom: 40px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            max-width: 600px;
            margin: 50px auto 0;
        }
        .sig-block { text-align: center; }
        .sig-img { height: 50px; margin-bottom: 5px; }
        .sig-line {
            width: 120px;
            border-top: 1px solid #333;
            padding-top: 8px;
            font-size: 11px;
            color: #666;
        }
        .stamp-block { text-align: center; }
        .bottom-info {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .cert-number {
            font-size: 10px;
            color: #aaa;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="top-line"></div>
        <div class="logo-section">{{logo}}</div>
        
        <p class="title">Certificate of Achievement</p>
        <h1 class="main-title">Sertifikat</h1>
        
        <p class="recipient-label">Diberikan kepada</p>
        <h2 class="recipient-name">{{nama}}</h2>
        
        <p class="description">
            Sebagai <strong>{{tipe}}</strong> yang telah berkontribusi dalam:
        </p>
        
        <p class="event-name">{{konferensi}}</p>
        <p class="date">{{tanggal}}</p>
        
        <table style="width:100%; margin-top:30px; border-collapse:collapse;">
            <tr>
                <td style="width:33%; text-align:center; vertical-align:bottom; padding:10px;">
                    {{ttd1}}
                    <div style="border-top:1px solid #333; margin-top:10px; padding-top:5px; font-size:12px; color:#555;">
                        {{ttd1_nama}}<br><small>{{ttd1_jabatan}}</small>
                    </div>
                </td>
                <td style="width:34%; text-align:center; vertical-align:bottom; padding:10px;">
                    {{qrcode}}<br>
                    <small style="font-size:9px;color:#888;">Scan untuk verifikasi</small>
                </td>
                <td style="width:33%; text-align:center; vertical-align:bottom; padding:10px;">
                    {{ttd2}}
                    <div style="border-top:1px solid #333; margin-top:10px; padding-top:5px; font-size:12px; color:#555;">
                        {{ttd2_nama}}<br><small>{{ttd2_jabatan}}</small>
                    </div>
                </td>
            </tr>
        </table>
        
        <div class="bottom-info">
            <p class="cert-number">{{nomor}}</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
