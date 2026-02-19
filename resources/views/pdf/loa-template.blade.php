<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letter of Acceptance - {{ $loaNumber }}</title>
    <style>
        @page {
            margin: 2cm 2.5cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #333;
            padding-bottom: 20px;
        }
        
        .header img.logo {
            height: 80px;
            margin-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 10px 0 5px 0;
            color: #1a5490;
        }
        
        .header p {
            margin: 3px 0;
            font-size: 11pt;
        }
        
        .meta-info {
            margin: 25px 0;
        }
        
        .meta-info table {
            width: 100%;
        }
        
        .meta-info td {
            padding: 3px 0;
        }
        
        .meta-info td:first-child {
            width: 150px;
            font-weight: bold;
        }
        
        .recipient {
            margin: 20px 0;
        }
        
        .title-doc {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 30px 0;
            text-decoration: underline;
            color: #1a5490;
        }
        
        .content {
            text-align: justify;
            margin: 20px 0;
        }
        
        .paper-title {
            text-align: center;
            font-style: italic;
            font-weight: bold;
            margin: 20px 40px;
            font-size: 13pt;
        }
        
        .signature-section {
            margin-top: 50px;
        }
        
        .signature-box {
            float: right;
            width: 45%;
            text-align: center;
        }
        
        .signature-image {
            height: 60px;
            margin: 10px 0;
        }
        
        .qr-section {
            clear: both;
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .qr-section img {
            width: 100px;
            height: 100px;
        }
        
        .qr-text {
            font-size: 9pt;
            color: #666;
            margin-top: 5px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #888;
        }
    </style>
</head>
<body>
    {{-- Header with Logo and Conference Info --}}
    <div class="header">
        @if($conference->logo)
            <img src="{{ public_path('storage/' . $conference->logo) }}" class="logo" alt="Logo">
        @endif
        <h1>{{ $conference->name }}</h1>
        <p>{{ $conference->theme }}</p>
        <p>
            @if($conference->start_date && $conference->end_date)
                {{ $conference->start_date->format('d') }} - {{ $conference->end_date->format('d F Y') }}
            @endif
        </p>
        <p>{{ $conference->venue }} @if($conference->city), {{ $conference->city }}@endif</p>
    </div>
    
    {{-- Document Metadata --}}
    <div class="meta-info">
        <table>
            <tr>
                <td>Nomor</td>
                <td>: <strong>{{ $loaNumber }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ $generatedDate->format('d F Y') }}</td>
            </tr>
        </table>
    </div>
    
    {{-- Recipient --}}
    <div class="recipient">
        <p>Kepada Yth,<br>
        <strong>{{ $author->name }}</strong><br>
        @if($author->institution){{ $author->institution }}<br>@endif
        @if($author->email){{ $author->email }}@endif
        </p>
    </div>
    
    {{-- Document Title --}}
    <div class="title-doc">
        LETTER OF ACCEPTANCE
    </div>
    
    {{-- Content --}}
    <div class="content">
        <p>Dengan hormat,</p>
        
        <p>Panitia {{ $conference->name }} dengan senang hati menyampaikan bahwa naskah yang Anda ajukan dengan data sebagai berikut:</p>
        
        <table style="margin: 15px 0; width: 100%;">
            <tr>
                <td style="width: 120px; vertical-align: top;"><strong>Judul</strong></td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $paper->title }}</td>
            </tr>
            @if($paper->authors_meta && is_array($paper->authors_meta))
            <tr>
                <td style="vertical-align: top;"><strong>Penulis</strong></td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">
                    @foreach($paper->authors_meta as $index => $authorMeta)
                        {{ $authorMeta['name'] ?? '' }}@if(isset($authorMeta['institution'])) ({{ $authorMeta['institution'] }})@endif@if(!$loop->last), @endif
                    @endforeach
                </td>
            </tr>
            @endif
            @if($paper->topic)
            <tr>
                <td style="vertical-align: top;"><strong>Topik</strong></td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $paper->topic }}</td>
            </tr>
            @endif
            <tr>
                <td style="vertical-align: top;"><strong>Tanggal Submit</strong></td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $paper->submitted_at ? $paper->submitted_at->format('d F Y') : '-' }}</td>
            </tr>
        </table>
        
        <p>telah melalui <strong>proses review</strong> oleh tim reviewer dan dinyatakan <strong style="color: #1a5490;">DITERIMA (ACCEPTED)</strong> untuk dipresentasikan pada {{ $conference->name }}.</p>
        
        <p>Untuk kelancaran proses selanjutnya, kami mohon Anda untuk:</p>
        <ol>
            <li>Melakukan pembayaran biaya publikasi sesuai invoice yang telah dikirimkan</li>
            <li>Mengunggah bukti pembayaran melalui sistem</li>
            <li>Menyiapkan dan mengunggah luaran yang diperlukan (poster, PPT, full paper final, dll) sesuai panduan</li>
        </ol>
        
        <p>Kami ucapkan selamat dan terima kasih atas partisipasi Anda dalam {{ $conference->name }}. Kontribusi Anda sangat berharga bagi kemajuan ilmu pengetahuan.</p>
        
        <p>Hormat kami,</p>
    </div>
    
    {{-- Signature Section --}}
    <div class="signature-section">
        <div class="signature-box">
            <p style="margin-bottom: 5px;">Ketua Panitia Pelaksana</p>
            <p style="margin-bottom: 5px;">{{ $conference->name }}</p>
            
            @if(file_exists(public_path('storage/signatures/chairman.png')))
                <img src="{{ public_path('storage/signatures/chairman.png') }}" class="signature-image" alt="Signature">
            @else
                <div style="height: 60px;"></div>
            @endif
            
            <p style="margin-top: 5px; font-weight: bold; text-decoration: underline;">
                {{ $conference->organizer ?? 'Ketua Panitia' }}
            </p>
        </div>
    </div>
    
    {{-- QR Code Section --}}
    <div class="qr-section">
        <img src="{{ $qrCode }}" alt="QR Code Verification">
        <p class="qr-text">Scan QR Code untuk verifikasi keaslian dokumen<br>{{ $loaNumber }}</p>
    </div>
    
    {{-- Footer --}}
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem dan sah tanpa tanda tangan basah</p>
    </div>
</body>
</html>
