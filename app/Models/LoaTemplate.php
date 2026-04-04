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

class LoaTemplate extends Model
{
    protected $fillable = [
        'conference_id', 'name', 'slug', 'type', 'html_template',
        'background_image', 'logo_image', 'letterhead_image',
        'signature_image', 'signature_name', 'signature_position',
        'signature2_image', 'signature2_name', 'signature2_position',
        'stamp_image', 'orientation', 'paper_size', 'settings', 'is_default', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    const TYPE_LABELS = [
        'all' => 'Semua (Paper & Abstrak)',
        'paper' => 'Paper',
        'abstract' => 'Abstrak',
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
     * Render the LOA HTML with data (for Paper)
     */
    public function renderPaper(Paper $paper): string
    {
        $html = $this->html_template;
        $conference = $paper->conference;
        $loaNumber = $paper->loa_number ?? 'LOA-' . str_pad($paper->id, 5, '0', STR_PAD_LEFT);

        // Replace placeholders
        $replacements = [
            '{{judul}}' => e($paper->title),
            '{{title}}' => e($paper->title),
            '{{nama}}' => e($paper->user?->name ?? ''),
            '{{author}}' => e($paper->user?->name ?? ''),
            '{{email}}' => e($paper->user?->email ?? ''),
            '{{institusi}}' => e($paper->user?->institution ?? ''),
            '{{institution}}' => e($paper->user?->institution ?? ''),
            '{{konferensi}}' => e($conference?->name ?? ''),
            '{{conference}}' => e($conference?->name ?? ''),
            '{{nomor_loa}}' => e($loaNumber),
            '{{loa_number}}' => e($loaNumber),
            '{{tanggal}}' => now()->isoFormat('D MMMM Y'),
            '{{date}}' => now()->format('F d, Y'),
            '{{tahun}}' => date('Y'),
            '{{year}}' => date('Y'),
            '{{tanggal_konferensi}}' => $conference?->event_date?->isoFormat('D MMMM Y') ?? '',
            '{{conference_date}}' => $conference?->event_date?->format('F d, Y') ?? '',
            '{{tempat}}' => e($conference?->location ?? ''),
            '{{location}}' => e($conference?->location ?? ''),
            '{{topic}}' => e($paper->topic ?? ''),
            '{{topik}}' => e($paper->topic ?? ''),
            '{{ttd_nama}}' => e($this->signature_name ?? ''),
            '{{ttd_jabatan}}' => e($this->signature_position ?? ''),
            '{{signature_name}}' => e($this->signature_name ?? ''),
            '{{signature_position}}' => e($this->signature_position ?? ''),
            '{{ttd2_nama}}' => e($this->signature2_name ?? ''),
            '{{ttd2_jabatan}}' => e($this->signature2_position ?? ''),
            '{{signature2_name}}' => e($this->signature2_name ?? ''),
            '{{signature2_position}}' => e($this->signature2_position ?? ''),
        ];

        $html = str_replace(array_keys($replacements), array_values($replacements), $html);

        // Handle images
        $html = $this->processImages($html);

        // Generate and add QR Code
        $qrCodeHtml = $this->generateQrCode($loaNumber);
        $html = str_replace(['{{qrcode}}', '{{qr_code}}'], $qrCodeHtml, $html);

        return $html;
    }

    /**
     * Render the LOA HTML with data (for Abstract)
     */
    public function renderAbstract(AbstractSubmission $abstract): string
    {
        $html = $this->html_template;
        $conference = $abstract->conference;
        $loaNumber = 'LOA-ABS-' . str_pad($abstract->id, 5, '0', STR_PAD_LEFT);

        // Replace placeholders
        $replacements = [
            '{{judul}}' => e($abstract->title),
            '{{title}}' => e($abstract->title),
            '{{nama}}' => e($abstract->user?->name ?? ''),
            '{{author}}' => e($abstract->user?->name ?? ''),
            '{{email}}' => e($abstract->user?->email ?? ''),
            '{{institusi}}' => e($abstract->user?->institution ?? ''),
            '{{institution}}' => e($abstract->user?->institution ?? ''),
            '{{konferensi}}' => e($conference?->name ?? ''),
            '{{conference}}' => e($conference?->name ?? ''),
            '{{nomor_loa}}' => $loaNumber,
            '{{loa_number}}' => $loaNumber,
            '{{tanggal}}' => now()->isoFormat('D MMMM Y'),
            '{{date}}' => now()->format('F d, Y'),
            '{{tahun}}' => date('Y'),
            '{{year}}' => date('Y'),
            '{{tanggal_konferensi}}' => $conference?->event_date?->isoFormat('D MMMM Y') ?? '',
            '{{conference_date}}' => $conference?->event_date?->format('F d, Y') ?? '',
            '{{tempat}}' => e($conference?->location ?? ''),
            '{{location}}' => e($conference?->location ?? ''),
            '{{topic}}' => e($abstract->topic ?? ''),
            '{{topik}}' => e($abstract->topic ?? ''),
            '{{ttd_nama}}' => e($this->signature_name ?? ''),
            '{{ttd_jabatan}}' => e($this->signature_position ?? ''),
            '{{signature_name}}' => e($this->signature_name ?? ''),
            '{{signature_position}}' => e($this->signature_position ?? ''),
            '{{ttd2_nama}}' => e($this->signature2_name ?? ''),
            '{{ttd2_jabatan}}' => e($this->signature2_position ?? ''),
            '{{signature2_name}}' => e($this->signature2_name ?? ''),
            '{{signature2_position}}' => e($this->signature2_position ?? ''),
        ];

        $html = str_replace(array_keys($replacements), array_values($replacements), $html);

        // Handle images
        $html = $this->processImages($html);

        // Generate and add QR Code
        $qrCodeHtml = $this->generateQrCode($loaNumber);
        $html = str_replace(['{{qrcode}}', '{{qr_code}}'], $qrCodeHtml, $html);

        return $html;
    }

    /**
     * Process and replace image placeholders
     */
    private function processImages(string $html): string
    {
        // Signature 1
        if ($this->signature_image) {
            $html = str_replace(['{{ttd}}', '{{signature}}'], $this->getImageTag($this->signature_image, 60), $html);
        } else {
            $html = str_replace(['{{ttd}}', '{{signature}}'], '', $html);
        }

        // Signature 2
        if ($this->signature2_image) {
            $html = str_replace(['{{ttd2}}', '{{signature2}}'], $this->getImageTag($this->signature2_image, 60), $html);
        } else {
            $html = str_replace(['{{ttd2}}', '{{signature2}}'], '', $html);
        }

        // Logo
        if ($this->logo_image) {
            $html = str_replace('{{logo}}', $this->getImageTag($this->logo_image, 80), $html);
        } else {
            $html = str_replace('{{logo}}', '', $html);
        }

        // Letterhead
        if ($this->letterhead_image) {
            $html = str_replace('{{kop_surat}}', $this->getImageTag($this->letterhead_image, 120), $html);
            $html = str_replace('{{letterhead}}', $this->getImageTag($this->letterhead_image, 120), $html);
        } else {
            $html = str_replace(['{{kop_surat}}', '{{letterhead}}'], '', $html);
        }

        // Stamp
        if ($this->stamp_image) {
            $html = str_replace(['{{stempel}}', '{{stamp}}'], $this->getImageTag($this->stamp_image, 80), $html);
        } else {
            $html = str_replace(['{{stempel}}', '{{stamp}}'], '', $html);
        }

        // Background
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
     * Generate QR Code for LOA verification
     */
    private function generateQrCode(string $loaNumber): string
    {
        // Build verification URL
        $verifyUrl = url('/verify-loa/' . $loaNumber);
        
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
            
            return "<img src=\"{$qrDataUri}\" style=\"width:70px; height:70px;\">";
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
            'formal-classic' => [
                'name' => 'Formal Classic',
                'preview' => 'Desain surat resmi klasik dengan kop surat',
                'html' => self::getFormalClassicTemplate(),
            ],
            'modern-simple' => [
                'name' => 'Modern Simple',
                'preview' => 'Desain modern minimalis dan profesional',
                'html' => self::getModernSimpleTemplate(),
            ],
            'academic-formal' => [
                'name' => 'Academic Formal',
                'preview' => 'Desain formal untuk kegiatan akademik',
                'html' => self::getAcademicFormalTemplate(),
            ],
        ];
    }

    private static function getFormalClassicTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 30px 40px; }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header .logo { margin-bottom: 10px; }
        .header h1 { margin: 5px 0; font-size: 16pt; }
        .header p { margin: 2px 0; font-size: 10pt; }
        .letter-number {
            text-align: right;
            margin-bottom: 20px;
            font-size: 11pt;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin: 25px 0;
            text-decoration: underline;
        }
        .content { text-align: justify; margin-bottom: 20px; }
        .content p { margin: 10px 0; }
        .paper-info {
            margin: 20px 0 20px 40px;
        }
        .paper-info table { border-collapse: collapse; }
        .paper-info td { padding: 3px 10px 3px 0; vertical-align: top; }
        .paper-info td:first-child { width: 100px; }
        .signature-section {
            margin-top: 40px;
            text-align: right;
            padding-right: 50px;
        }
        .signature-section .date { margin-bottom: 10px; }
        .signature-section .sign-area { height: 60px; margin: 10px 0; }
        .signature-section .name { font-weight: bold; }
        .signature-section .position { font-size: 10pt; }
    </style>
</head>
<body>
    <div class="header">
        {{logo}}
        <h1>{{konferensi}}</h1>
        <p>{{tempat}}</p>
    </div>

    <div class="letter-number">
        Nomor: {{nomor_loa}}<br>
        Tanggal: {{tanggal}}
    </div>

    <div class="title">LETTER OF ACCEPTANCE (LOA)</div>

    <div class="content">
        <p>Dengan ini kami menyatakan bahwa:</p>
        
        <div class="paper-info">
            <table>
                <tr><td><strong>Nama</strong></td><td>: {{nama}}</td></tr>
                <tr><td><strong>Email</strong></td><td>: {{email}}</td></tr>
                <tr><td><strong>Institusi</strong></td><td>: {{institusi}}</td></tr>
                <tr><td><strong>Judul Paper</strong></td><td>: {{judul}}</td></tr>
            </table>
        </div>

        <p>Telah dinyatakan <strong>DITERIMA</strong> untuk dipresentasikan pada <strong>{{konferensi}}</strong> yang akan dilaksanakan pada tanggal <strong>{{tanggal_konferensi}}</strong> di <strong>{{tempat}}</strong>.</p>

        <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature-section">
        <div class="date">{{tempat}}, {{tanggal}}</div>
        <div class="sign-area">{{ttd}}</div>
        <div class="name">{{ttd_nama}}</div>
        <div class="position">{{ttd_jabatan}}</div>
    </div>

    <div style="position: fixed; bottom: 30px; left: 40px; right: 40px; border-top: 1px solid #ddd; padding-top: 10px;">
        <table width="100%">
            <tr>
                <td style="vertical-align: middle;">{{qrcode}}</td>
                <td style="vertical-align: middle; padding-left: 10px; font-size: 9pt; color: #666;">
                    <strong>Verifikasi Keaslian Dokumen</strong><br>
                    Scan QR Code atau kunjungi:<br>
                    <span style="color: #3498db;">{{nomor_loa}}</span>
                </td>
                <td style="text-align: right; font-size: 8pt; color: #999; vertical-align: middle;">
                    Dokumen ini diterbitkan secara elektronik<br>dan sah tanpa tanda tangan basah.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
HTML;
    }

    private static function getModernSimpleTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 40px; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #2c3e50;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header .logo img { height: 60px; }
        .header .info { text-align: right; }
        .header .info h2 { color: #3498db; margin: 0 0 5px; font-size: 14pt; }
        .header .info p { margin: 2px 0; font-size: 9pt; color: #7f8c8d; }
        .loa-badge {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 8px 25px;
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
            margin: 20px 0;
        }
        .recipient-box {
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px 20px;
            margin: 20px 0;
        }
        .recipient-box h4 { margin: 0 0 10px; color: #3498db; }
        .recipient-box p { margin: 5px 0; }
        .content { text-align: justify; }
        .paper-title {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            font-style: italic;
        }
        .signature-area {
            margin-top: 40px;
            text-align: right;
        }
        .signature-area .date { color: #7f8c8d; font-size: 10pt; }
        .signature-area .sign { height: 50px; margin: 10px 0; }
        .signature-area .name { font-weight: bold; color: #2c3e50; }
        .signature-area .position { font-size: 9pt; color: #7f8c8d; }
        .footer {
            position: fixed;
            bottom: 30px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 8pt;
            color: #bdc3c7;
            border-top: 1px solid #ecf0f1;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <table width="100%" style="border-bottom: 2px solid #3498db; padding-bottom: 20px; margin-bottom: 30px;">
        <tr>
            <td>{{logo}}</td>
            <td style="text-align: right;">
                <h2 style="color: #3498db; margin: 0 0 5px; font-size: 14pt;">{{konferensi}}</h2>
                <p style="margin: 2px 0; font-size: 9pt; color: #7f8c8d;">{{tempat}}</p>
                <p style="margin: 2px 0; font-size: 9pt; color: #7f8c8d;">No: {{nomor_loa}}</p>
            </td>
        </tr>
    </table>

    <div class="loa-badge">LETTER OF ACCEPTANCE</div>

    <div class="recipient-box">
        <h4>Kepada Yth:</h4>
        <p><strong>{{nama}}</strong></p>
        <p>{{institusi}}</p>
        <p>{{email}}</p>
    </div>

    <div class="content">
        <p>Dengan hormat,</p>
        <p>Berdasarkan hasil review, kami dengan senang hati memberitahukan bahwa paper Anda:</p>
        
        <div class="paper-title">
            "{{judul}}"
        </div>

        <p>Telah <strong>DITERIMA</strong> untuk dipresentasikan pada {{konferensi}} yang akan dilaksanakan pada {{tanggal_konferensi}} di {{tempat}}.</p>

        <p>Mohon untuk segera melakukan konfirmasi kehadiran dan menyelesaikan prosedur administrasi yang diperlukan.</p>

        <p>Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
    </div>

    <div class="signature-area">
        <div class="date">{{tanggal}}</div>
        <div class="sign">{{ttd}}</div>
        <div class="name">{{ttd_nama}}</div>
        <div class="position">{{ttd_jabatan}}</div>
    </div>

    <div class="footer">
        <table width="100%" style="border: none;">
            <tr>
                <td style="vertical-align: middle; width: 80px;">{{qrcode}}</td>
                <td style="text-align: left; padding-left: 10px; vertical-align: middle;">
                    <strong style="color: #3498db;">Verifikasi Dokumen</strong><br>
                    <span style="color: #7f8c8d;">Scan QR Code untuk verifikasi keaslian</span>
                </td>
                <td style="text-align: right; vertical-align: middle; color: #bdc3c7;">
                    Dokumen ini diterbitkan secara elektronik<br>dan sah tanpa tanda tangan basah.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
HTML;
    }

    private static function getAcademicFormalTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 25mm 20mm; }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.8;
            color: #1a1a1a;
        }
        .letterhead {
            text-align: center;
            margin-bottom: 20px;
        }
        .letterhead img { max-height: 100px; }
        .divider {
            border-bottom: 3px solid #1a1a1a;
            margin: 10px 0 25px;
        }
        .document-info {
            text-align: center;
            margin-bottom: 25px;
        }
        .document-info .number {
            font-size: 11pt;
            margin-bottom: 5px;
        }
        .document-info .title {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 15px;
        }
        .content { text-align: justify; }
        .content p { margin: 12px 0; text-indent: 40px; }
        .highlight-box {
            border: 1px solid #333;
            padding: 15px 20px;
            margin: 20px 0;
        }
        .highlight-box table { width: 100%; }
        .highlight-box td { padding: 5px 0; vertical-align: top; }
        .highlight-box td:first-child { width: 120px; font-weight: bold; }
        .closing { margin-top: 30px; }
        .signature-block {
            float: right;
            width: 250px;
            text-align: center;
            margin-top: 30px;
        }
        .signature-block .place-date { margin-bottom: 10px; }
        .signature-block .sign-image { height: 55px; margin: 5px 0; }
        .signature-block .name { font-weight: bold; text-decoration: underline; }
        .signature-block .nip { font-size: 10pt; }
        .clearfix { clear: both; }
    </style>
</head>
<body>
    <div class="letterhead">
        {{kop_surat}}
    </div>
    
    <div class="divider"></div>

    <div class="document-info">
        <div class="number">Nomor: {{nomor_loa}}</div>
        <div class="title">Surat Keterangan Penerimaan Paper</div>
        <div class="title" style="font-size: 12pt;">(Letter of Acceptance)</div>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Panitia {{konferensi}}, dengan ini menerangkan bahwa:</p>

        <div class="highlight-box">
            <table>
                <tr><td>Nama</td><td>: {{nama}}</td></tr>
                <tr><td>Institusi</td><td>: {{institusi}}</td></tr>
                <tr><td>Email</td><td>: {{email}}</td></tr>
                <tr><td>Judul Paper</td><td>: {{judul}}</td></tr>
                <tr><td>Topik</td><td>: {{topik}}</td></tr>
            </table>
        </div>

        <p>Berdasarkan hasil seleksi dan review yang dilakukan oleh tim reviewer, paper tersebut di atas dinyatakan <strong>DITERIMA</strong> untuk dipresentasikan pada {{konferensi}} yang akan dilaksanakan pada:</p>

        <div style="margin-left: 40px;">
            <p style="text-indent: 0; margin: 5px 0;">Tanggal : {{tanggal_konferensi}}</p>
            <p style="text-indent: 0; margin: 5px 0;">Tempat : {{tempat}}</p>
        </div>

        <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature-block">
        <div class="place-date">{{tempat}}, {{tanggal}}</div>
        <div>Ketua Panitia,</div>
        <div class="sign-image">{{ttd}} {{stempel}}</div>
        <div class="name">{{ttd_nama}}</div>
        <div class="nip">{{ttd_jabatan}}</div>
    </div>

    <div class="clearfix"></div>

    <div style="position: fixed; bottom: 20px; left: 20mm; right: 20mm; border-top: 2px solid #1a1a1a; padding-top: 10px; margin-top: 30px;">
        <table width="100%">
            <tr>
                <td style="vertical-align: middle; width: 75px;">{{qrcode}}</td>
                <td style="vertical-align: middle; padding-left: 15px; font-size: 9pt;">
                    <strong>Verifikasi Keaslian Surat</strong><br>
                    Scan QR Code di samping atau kunjungi website resmi<br>
                    untuk memverifikasi keaslian dokumen ini.
                </td>
                <td style="text-align: right; font-size: 8pt; color: #666; vertical-align: middle;">
                    No. Dokumen: {{nomor_loa}}<br>
                    Tanggal Terbit: {{tanggal}}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
HTML;
    }
}
