<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kegiatan Baru</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 40px 30px; }
        .button { display: inline-block; background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; text-decoration: none; padding: 14px 35px; border-radius: 25px; margin: 20px 0; font-weight: 600; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; }
        .event-box { background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%); padding: 25px; margin: 20px 0; border-radius: 8px; border-left: 5px solid #4299e1; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📢 Kegiatan Baru Dipublikasikan!</h1>
        </div>
        <div class="content">
            <h2>Halo {{ $userName }}!</h2>
            <p>Kami dengan senang hati mengumumkan kegiatan baru yang telah dipublikasikan!</p>

            <div class="event-box">
                <h3 style="margin-top: 0; color: #2c5282;">{{ $eventName }}</h3>
                @if($eventDate)
                <p style="margin: 10px 0;"><strong>📅 Tanggal:</strong> {{ $eventDate }}</p>
                @endif
                <p style="margin: 10px 0; font-size: 14px; color: #4a5568;">Jangan lewatkan kesempatan untuk berpartisipasi dalam kegiatan ini!</p>
            </div>

            <p>Segera daftarkan diri Anda dan submit paper untuk kesempatan publikasi ilmiah.</p>

            <div style="text-align: center;">
                <a href="{{ $eventUrl }}" class="button">Lihat Detail Kegiatan</a>
            </div>

            <p style="margin-top: 30px;"><strong>Manfaat mengikuti kegiatan:</strong></p>
            <ul>
                <li>Publikasi paper di prosiding</li>
                <li>Networking dengan para ahli</li>
                <li>Sertifikat resmi</li>
                <li>Pengembangan profesional</li>
            </ul>

            <p>Salam,<br><strong>Tim {{ config('app.name') }}</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
