<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Pembayaran</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #f6ad55 0%, #dd6b20 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 8px 0 0; font-size: 15px; opacity: 0.9; }
        .content { padding: 40px 30px; }
        .warning-box { background: #fffbeb; border-left: 4px solid #f6ad55; padding: 14px 18px; border-radius: 0 8px 8px 0; margin: 16px 0; }
        .detail-box { background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; border: 2px solid #fed7aa; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e2e8f0; }
        .detail-row:last-child { border-bottom: none; }
        .amount { font-size: 22px; color: #dd6b20; font-weight: bold; }
        .button { display: inline-block; background: linear-gradient(135deg, #f6ad55 0%, #dd6b20 100%); color: white !important; text-decoration: none; padding: 14px 35px; border-radius: 25px; margin: 20px 0; font-weight: 600; font-size: 15px; }
        .steps { counter-reset: step; margin: 20px 0; }
        .step { display: flex; align-items: flex-start; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .step:last-child { border-bottom: none; }
        .step-num { background: #dd6b20; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; flex-shrink: 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏰ Pembayaran Menunggu</h1>
            <p>Akun Anda belum aktif — segera upload bukti bayar</p>
        </div>
        <div class="content">
            <h2>Halo {{ $userName }}!</h2>

            <div class="warning-box">
                ⚠️ <strong>Akun Anda belum dapat digunakan.</strong> Untuk mengaktifkan akun dan bisa login ke sistem,
                Anda perlu melunasi pembayaran registrasi dan mengupload bukti transfer.
            </div>

            <div class="detail-box">
                <h3 style="margin-top: 0; color: #92400e;">Detail Tagihan</h3>
                <div class="detail-row">
                    <span>No. Invoice:</span>
                    <strong style="font-family: monospace;">{{ $invoiceNumber }}</strong>
                </div>
                <div class="detail-row">
                    <span>Jenis:</span>
                    <strong>Registrasi Peserta</strong>
                </div>
                <div class="detail-row">
                    <span>Jumlah Tagihan:</span>
                    <span class="amount">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span>Status:</span>
                    <strong style="color: #92400e;">⏳ Menunggu Bukti Bayar</strong>
                </div>
            </div>

            <h3 style="color: #374151;">Langkah Yang Harus Dilakukan:</h3>
            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div>
                        <strong>Lakukan Transfer</strong><br>
                        <span style="color: #6b7280; font-size: 14px;">Transfer sesuai nominal tagihan ke rekening yang tertera di halaman pembayaran.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div>
                        <strong>Login ke Sistem</strong><br>
                        <span style="color: #6b7280; font-size: 14px;">Masuk menggunakan email dan password yang telah Anda daftarkan.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div>
                        <strong>Upload Bukti Transfer</strong><br>
                        <span style="color: #6b7280; font-size: 14px;">Upload foto/scan bukti transfer melalui menu "Bukti Pembayaran" di dashboard.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">4</div>
                    <div>
                        <strong>Tunggu Verifikasi Admin</strong><br>
                        <span style="color: #6b7280; font-size: 14px;">Admin akan memverifikasi dalam 1×24 jam. Anda akan mendapat notifikasi email saat akun aktif.</span>
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 28px;">
                <a href="{{ $loginUrl }}" class="button">Login & Upload Bukti Bayar</a>
            </div>

            <p style="margin-top: 20px; font-size: 13px; color: #6c757d; text-align: center;">
                Butuh bantuan? Hubungi panitia melalui halaman kontak website kami.
            </p>

            <p>Terima kasih,<br><strong>Tim {{ config('app.name') }}</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
