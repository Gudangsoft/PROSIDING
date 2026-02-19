<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Terverifikasi</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 8px 0 0; font-size: 15px; opacity: 0.9; }
        .content { padding: 40px 30px; }
        .badge-success { display: inline-block; background-color: #f0fff4; color: #276749; border: 1.5px solid #9ae6b4; border-radius: 20px; padding: 6px 18px; font-size: 14px; font-weight: 600; margin-bottom: 16px; }
        .detail-box { background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; border: 2px solid #c6f6d5; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e2e8f0; }
        .detail-row:last-child { border-bottom: none; font-weight: 700; }
        .amount { font-size: 22px; color: #38a169; font-weight: bold; }
        .button { display: inline-block; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white !important; text-decoration: none; padding: 14px 35px; border-radius: 25px; margin: 20px 0; font-weight: 600; font-size: 15px; }
        .login-note { background: #ebf8ff; border-left: 4px solid #63b3ed; padding: 14px 18px; border-radius: 0 8px 8px 0; margin: 20px 0; font-size: 14px; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Pembayaran Lunas!</h1>
            <p>Pembayaran Anda telah berhasil diverifikasi</p>
        </div>
        <div class="content">
            <h2>Halo {{ $userName }}!</h2>

            <span class="badge-success">Status: LUNAS / TERVERIFIKASI</span>

            <p>
                Kami dengan senang hati menginformasikan bahwa pembayaran Anda telah
                <strong>diverifikasi</strong> oleh tim {{ config('app.name') }}.
                Akun Anda sekarang <strong>sudah aktif dan dapat digunakan</strong>.
            </p>

            <div class="detail-box">
                <h3 style="margin-top: 0; color: #276749;">Detail Pembayaran</h3>

                <div class="detail-row">
                    <span>No. Invoice:</span>
                    <strong style="font-family: monospace;">{{ $invoiceNumber }}</strong>
                </div>

                @if($paymentType === 'paper' && $paperTitle)
                <div class="detail-row">
                    <span>Paper:</span>
                    <strong style="max-width: 300px; text-align: right;">{{ $paperTitle }}</strong>
                </div>
                @endif

                @if($paymentType === 'participant')
                <div class="detail-row">
                    <span>Jenis:</span>
                    <strong>Registrasi Peserta</strong>
                </div>
                @endif

                <div class="detail-row">
                    <span>Jumlah Dibayar:</span>
                    <span class="amount">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                </div>

                <div class="detail-row">
                    <span>Status:</span>
                    <strong style="color: #276749;">✅ Terverifikasi (Lunas)</strong>
                </div>
            </div>

            <div class="login-note">
                🔐 <strong>Akun Anda sudah aktif!</strong> Silakan login ke dashboard untuk
                {{ $paymentType === 'participant' ? 'mengakses seluruh informasi konferensi' : 'melihat detail submission dan proses selanjutnya' }}.
            </div>

            <div style="text-align: center;">
                <a href="{{ $dashboardUrl }}" class="button">Buka Dashboard Saya</a>
            </div>

            <p style="margin-top: 30px; font-size: 13px; color: #6c757d;">
                Jika Anda belum login, silakan <a href="{{ $loginUrl }}" style="color: #38a169;">klik di sini untuk login</a>.
            </p>

            <p>Terima kasih telah berpartisipasi!<br><strong>Tim {{ config('app.name') }}</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
