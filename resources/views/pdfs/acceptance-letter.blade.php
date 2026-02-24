<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Acceptance Letter</title>
<style>
    @page { size: A4; margin: 0; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DejaVu Serif', serif; font-size: 12pt; color: #1a1a1a; background: #fff; }

    .letterhead { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); padding: 32px 50px 24px; color: #fff; }
    .letterhead-inner { display: flex; align-items: center; gap: 20px; }
    .logo-box { width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28pt; font-weight: 900; color: #fff; }
    .org-name { font-size: 18pt; font-weight: 700; line-height: 1.2; }
    .org-sub  { font-size: 10pt; opacity: 0.8; margin-top: 3px; }

    .gold-bar { height: 5px; background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b); }

    .body { padding: 40px 50px; }

    .letter-number { font-size: 10pt; color: #6b7280; margin-bottom: 28px; }
    .letter-number span { font-weight: 600; color: #374151; }

    h1.letter-title { font-size: 20pt; font-weight: 900; color: #1e3a5f; text-align: center; text-transform: uppercase;
        letter-spacing: 2px; margin-bottom: 6px; }
    .subtitle { text-align: center; font-size: 10pt; color: #6b7280; margin-bottom: 36px; }

    p { line-height: 1.8; margin-bottom: 14px; text-align: justify; }

    .paper-box { background: #f0f4ff; border-left: 4px solid #2563eb; border-radius: 6px; padding: 18px 22px; margin: 24px 0; }
    .paper-box .paper-title { font-size: 13pt; font-weight: 700; color: #1e3a5f; margin-bottom: 10px; }
    .paper-box table { font-size: 10pt; color: #374151; width: 100%; }
    .paper-box td { padding: 2px 0; }
    .paper-box td:first-child { width: 140px; color: #6b7280; font-weight: 600; }

    .seal-area { text-align: right; margin-top: 40px; }
    .seal-area p { text-align: right; margin: 0; line-height: 1.6; }
    .signature-line { width: 160px; border-top: 2px solid #1a1a1a; margin-top: 60px; margin-left: auto; }
    .signature-name { font-weight: 700; font-size: 12pt; }

    .footer { margin-top: 48px; border-top: 1px solid #e5e7eb; padding-top: 16px; text-align: center;
        font-size: 9pt; color: #9ca3af; }

    .badge { display: inline-block; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;
        padding: 4px 14px; border-radius: 20px; font-size: 10pt; font-weight: 700; }
    .number-badge { font-size: 9pt; color: #6b7280; text-align: center; margin-bottom: 30px; font-style: italic; }
</style>
</head>
<body>

<div class="letterhead">
    <div class="letterhead-inner">
        <div class="org-name">
            {{ $siteName }}<br>
            <span class="org-sub">{{ $conference->name }}</span>
        </div>
    </div>
</div>
<div class="gold-bar"></div>

<div class="body">
    <div class="letter-number">
        No: <span>LOA/{{ $conference->id }}/{{ str_pad($paper->id, 6, '0', STR_PAD_LEFT) }}</span>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Tanggal: <span>{{ $issuedAt }}</span>
    </div>

    <h1 class="letter-title">Letter of Acceptance</h1>
    <p class="subtitle">Surat Penerimaan Paper — Official Document</p>

    <p>Yth.<br>
    <strong>{{ $paper->user->name }}</strong><br>
    {{ $paper->user->email }}</p>

    <p>Dengan hormat,</p>

    <p>Atas nama Panitia <strong>{{ $conference->name }}</strong>, kami dengan bangga memberitahukan bahwa paper Anda yang berjudul:</p>

    <div class="paper-box">
        <div class="paper-title">"{{ $paper->title }}"</div>
        <table>
            <tr>
                <td>Penulis</td>
                <td>: {{ $paper->user->name }}</td>
            </tr>
            @if($paper->topic)
            <tr>
                <td>Topik</td>
                <td>: {{ $paper->topic }}</td>
            </tr>
            @endif
            @if($paper->doi)
            <tr>
                <td>DOI</td>
                <td>: {{ $paper->doi }}</td>
            </tr>
            @endif
            <tr>
                <td>Konferensi</td>
                <td>: {{ $conference->name }}</td>
            </tr>
            @if($conference->start_date)
            <tr>
                <td>Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($conference->start_date)->format('d F Y') }}</td>
            </tr>
            @endif
            <tr>
                <td>Status</td>
                <td>: <span class="badge">ACCEPTED</span></td>
            </tr>
        </table>
    </div>

    <p>telah diterima dan akan dipresentasikan pada <strong>{{ $conference->name }}</strong>. Paper Anda telah melalui proses <em>peer review</em> dan memenuhi standar ilmiah yang ditetapkan oleh panitia.</p>

    <p>Kami mengucapkan selamat atas penerimaan paper Anda dan berharap dapat bertemu di konferensi. Informasi lebih lanjut mengenai jadwal presentasi dan registrasi akan disampaikan melalui email.</p>

    <p>Atas perhatian dan partisipasi Bapak/Ibu, kami ucapkan terima kasih.</p>

    <div class="seal-area">
        <p>{{ $siteAddress ? $siteAddress . ', ' : '' }}{{ $issuedAt }}</p>
        <p>Panitia {{ $conference->name }}</p>
        <div class="signature-line"></div>
        <p class="signature-name">{{ $siteName }}</p>
        <p style="font-size:10pt; color:#6b7280;">Ketua Panitia</p>
    </div>

    <div class="footer">
        Dokumen ini diterbitkan secara elektronik oleh sistem {{ $siteName }}.<br>
        Verifikasi keaslian dokumen di: {{ config('app.url') }}/verify-loa/{{ $paper->loa_number ?? 'N/A' }}
    </div>
</div>

</body>
</html>
