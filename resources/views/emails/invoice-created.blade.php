<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan Pembayaran</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 40px 30px; }
        .button { display: inline-block; background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%); color: white; text-decoration: none; padding: 14px 35px; border-radius: 25px; margin: 20px 0; font-weight: 600; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; }
        .invoice-box { background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; border: 2px solid #e2e8f0; }
        .invoice-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e2e8f0; }
        .invoice-row:last-child { border-bottom: none; }
        .amount { font-size: 24px; color: #ed8936; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💳 Tagihan Pembayaran</h1>
        </div>
        <div class="content">
            <h2>Halo {{ $userName }}!</h2>
            <p>Tagihan pembayaran untuk paper Anda telah dibuat.</p>

            <div class="invoice-box">
                <h3 style="margin-top: 0;">Detail Invoice</h3>
                <div class="invoice-row">
                    <span>Paper:</span>
                    <strong>{{ $paperTitle }}</strong>
                </div>
                <div class="invoice-row">
                    <span>No. Invoice:</span>
                    <strong style="font-family: monospace;">{{ $invoiceNumber }}</strong>
                </div>
                <div class="invoice-row">
                    <span>Jumlah Tagihan:</span>
                    <span class="amount">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <p><strong>Silakan lakukan pembayaran sesuai nominal di atas.</strong></p>
            
            <p>Setelah melakukan pembayaran, upload bukti pembayaran melalui dashboard Anda untuk verifikasi.</p>

            <div style="text-align: center;">
                <a href="{{ $paymentUrl }}" class="button">Lihat & Bayar Invoice</a>
            </div>

            <p style="margin-top: 30px; font-size: 13px; color: #6c757d;">
                <em>Invoice ini dibuat secara otomatis. Pastikan Anda melakukan pembayaran sebelum batas waktu yang ditentukan.</em>
            </p>

            <p>Terima kasih,<br><strong>Tim {{ config('app.name') }}</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
