<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 40px 30px; }
        .welcome-icon { font-size: 60px; text-align: center; margin-bottom: 20px; }
        .button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; padding: 14px 35px; border-radius: 25px; margin: 20px 0; font-weight: 600; text-align: center; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; }
        .info-box { background: #f8f9fa; border-left: 4px solid #667eea; padding: 15px; margin: 20px 0; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Selamat Datang!</h1>
        </div>
        <div class="content">
            <h2>Halo {{ $userName }}!</h2>
            <p>Selamat! Akun Anda telah berhasil didaftarkan di <strong>{{ config('app.name') }}</strong>.</p>
            
            <div class="info-box">
                <p style="margin: 0;"><strong>Status Pendaftaran:</strong> {{ $userRole === 'author' ? 'Author / Penulis' : 'Partisipan' }}</p>
            </div>

            @if($userRole === 'author')
                <p>Sebagai <strong>Author</strong>, Anda dapat:</p>
                <ul>
                    <li>Submit paper untuk konferensi</li>
                    <li>Upload revisi paper</li>
                    <li>Menerima LOA (Letter of Acceptance)</li>
                    <li>Melakukan pembayaran publikasi</li>
                </ul>
            @else
                <p>Sebagai <strong>Partisipan</strong>, Anda dapat:</p>
                <ul>
                    <li>Mengikuti kegiatan konferensi</li>
                    <li>Mengakses materi kegiatan</li>
                    <li>Mendapat sertifikat partisipasi</li>
                </ul>
                <p><em>Bukti pembayaran Anda sudah diterima dan akan segera diverifikasi oleh admin.</em></p>
            @endif

            <div style="text-align: center;">
                <a href="{{ $dashboardUrl }}" class="button">Masuk ke Dashboard</a>
            </div>

            <p style="margin-top: 30px;">Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
            
            <p>Salam hangat,<br><strong>Tim {{ config('app.name') }}</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
