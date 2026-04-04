<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Letter of Acceptance - {{ $loaNumber }}</title>
    <style>
        @page { margin: 2cm; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 18pt;
            margin: 0 0 5px 0;
            color: #1a365d;
        }
        .header h2 {
            font-size: 14pt;
            margin: 0 0 10px 0;
            font-weight: normal;
        }
        .header p {
            margin: 3px 0;
            font-size: 11pt;
            color: #666;
        }
        .loa-number {
            text-align: right;
            margin-bottom: 20px;
        }
        .loa-number span {
            font-weight: bold;
            color: #1a365d;
        }
        .content {
            text-align: justify;
        }
        .content p {
            margin-bottom: 12px;
        }
        .paper-details {
            background: #f8f9fa;
            border-left: 4px solid #1a365d;
            padding: 15px 20px;
            margin: 20px 0;
        }
        .paper-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .paper-details td {
            padding: 5px 0;
            vertical-align: top;
        }
        .paper-details td:first-child {
            width: 120px;
            font-weight: bold;
        }
        .signature {
            margin-top: 50px;
        }
        .signature-block {
            width: 200px;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
        }
        .signature-name {
            font-weight: bold;
        }
        .signature-title {
            font-size: 10pt;
            color: #666;
        }
        .footer {
            margin-top: 50px;
            font-size: 9pt;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
        }
        .seal {
            text-align: center;
            margin-top: 20px;
        }
        .seal-text {
            display: inline-block;
            border: 2px solid #1a365d;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            line-height: 80px;
            text-align: center;
            font-weight: bold;
            color: #1a365d;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $conference?->name ?? 'Konferensi' }}</h1>
        @if($conference?->theme)
        <h2>"{{ $conference->theme }}"</h2>
        @endif
        <p>{{ $conference?->venue ?? '' }} | {{ $conference?->start_date?->format('d F Y') ?? '' }}</p>
    </div>

    <div class="loa-number">
        No: <span>{{ $loaNumber }}</span><br>
        Tanggal: {{ now()->format('d F Y') }}
    </div>

    <div class="content">
        <p>Kepada Yth.<br>
        <strong>{{ $user->name }}</strong><br>
        {{ $user->institution ?? '' }}</p>

        <p>Dengan hormat,</p>

        <p>Berdasarkan hasil evaluasi tim reviewer, dengan ini kami menyatakan bahwa:</p>

        <div class="paper-details">
            <table>
                <tr>
                    <td>Judul Abstrak</td>
                    <td>: {{ $abstract->title }}</td>
                </tr>
                <tr>
                    <td>Topik</td>
                    <td>: {{ $abstract->topic ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Penulis</td>
                    <td>: 
                        @if($abstract->authors_meta)
                            @foreach($abstract->authors_meta as $i => $author)
                                {{ $author['name'] ?? '' }}{{ isset($author['institution']) ? ' ('.$author['institution'].')' : '' }}{{ $i < count($abstract->authors_meta) - 1 ? ', ' : '' }}
                            @endforeach
                        @else
                            {{ $user->name }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <p><strong>DINYATAKAN DITERIMA (ACCEPTED)</strong></p>

        <p>untuk dipresentasikan dan dipublikasikan dalam {{ $conference?->name ?? 'konferensi' }}.</p>

        <p>Surat ini merupakan bukti resmi bahwa abstrak telah disetujui dan pembayaran registrasi telah terverifikasi.</p>

        <p>Demikian surat ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
        <table width="100%">
            <tr>
                <td width="60%"></td>
                <td width="40%" align="center">
                    <div class="signature-block">
                        <p>Hormat kami,<br>Panitia {{ $conference?->acronym ?? 'Konferensi' }}</p>
                        <div class="signature-line"></div>
                        <p class="signature-name">{{ $conference?->organizer ?? 'Ketua Panitia' }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis dan sah tanpa tanda tangan basah.<br>
        Verifikasi: {{ url('/verify-loa/' . $loaNumber) }}</p>
    </div>
</body>
</html>
