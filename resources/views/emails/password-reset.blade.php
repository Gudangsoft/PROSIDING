<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Berhasil Direset</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 40px 30px; }
        .button { display: inline-block; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; text-decoration: none; padding: 14px 35px; border-radius: 25px; margin: 20px 0; font-weight: 600; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; }
        .alert-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Password Berhasil Direset</h1>
        </div>
        <div class="content">
            <h2>Halo {{ $userName }}!</h2>
            <p>Password akun Anda di <strong>{{ config('app.name') }}</strong> telah berhasil diubah.</p>

            <div class="alert-box">
                <p style="margin: 0;"><strong>⚠️ Catatan Keamanan:</strong><br>
                Jika Anda tidak melakukan reset password ini, segera hubungi tim kami dan ganti password Anda.</p>
            </div>

            <p>Gunakan password baru Anda untuk login ke sistem.</p>

            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="button">Login Sekarang</a>
            </div>

            <p style="margin-top: 30px;"><strong>Tips Keamanan:</strong></p>
            <ul>
                <li>Jangan bagikan password Anda kepada siapapun</li>
                <li>Gunakan password yang kuat dan unik</li>
                <li>Aktifkan two-factor authentication jika tersedia</li>
            </ul>

            <p>Salam,<br><strong>Tim {{ config('app.name') }}</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
