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
        @if($certificate)
        {{-- Valid Certificate --}}
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-green-700">Sertifikat Valid</h1>
            <p class="text-gray-500 text-sm mt-1">Nomor: <code class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $code }}</code></p>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4 space-y-3">
            <div class="flex justify-between items-start">
                <span class="text-sm text-gray-600">Penerima:</span>
                <span class="text-sm font-semibold text-gray-800 text-right">{{ $certificate->recipient_name }}</span>
            </div>
            <div class="flex justify-between items-start">
                <span class="text-sm text-gray-600">Tipe:</span>
                <span class="text-sm font-semibold text-gray-800">{{ $certificate->type_label }}</span>
            </div>
            <div class="flex justify-between items-start">
                <span class="text-sm text-gray-600">Event:</span>
                <span class="text-sm font-semibold text-gray-800 text-right">{{ $certificate->conference?->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-start">
                <span class="text-sm text-gray-600">Tanggal Terbit:</span>
                <span class="text-sm font-semibold text-gray-800">{{ $certificate->issued_at?->format('d M Y') ?? '-' }}</span>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4 text-center">
            <p class="text-xs text-blue-800">
                ✅ Sertifikat ini <strong>ASLI</strong> dan terdaftar dalam sistem.
            </p>
        </div>
        @else
        {{-- Invalid Certificate --}}
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-red-700">Sertifikat Tidak Valid</h1>
            <p class="text-gray-500 text-sm mt-1">Nomor: <code class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $code }}</code></p>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
            <p class="text-sm text-red-800">
                ⚠️ Sertifikat dengan nomor <strong>{{ $code }}</strong> 
                <strong>TIDAK DITEMUKAN</strong> dalam sistem.
            </p>
            <p class="text-xs text-red-700 mt-2">
                Pastikan nomor sertifikat yang Anda masukkan benar, atau hubungi panitia penyelenggara.
            </p>
        </div>
        @endif
        
        <div class="mt-6 pt-4 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-400">Verifikasi pada {{ now()->format('d F Y, H:i') }} WIB</p>
            <a href="{{ url('/') }}" class="text-xs text-blue-600 hover:underline mt-1 block">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
