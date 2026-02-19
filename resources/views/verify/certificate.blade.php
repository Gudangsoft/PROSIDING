<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat - {{ $code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-lg max-w-md w-full p-8">
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-blue-700">Verifikasi Sertifikat</h1>
            <p class="text-gray-500 text-sm mt-1">Nomor: <code class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $code }}</code></p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
            <p class="text-sm text-yellow-800">
                Dokumen sertifikat dengan kode <strong>{{ $code }}</strong> 
                terdaftar dalam sistem.
            </p>
            <p class="text-xs text-yellow-700 mt-2">
                Untuk konfirmasi lebih lanjut, hubungi panitia penyelenggara.
            </p>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-400">Verifikasi pada {{ now()->format('d F Y, H:i') }} WIB</p>
            <a href="{{ url('/') }}" class="text-xs text-blue-600 hover:underline mt-1 block">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
