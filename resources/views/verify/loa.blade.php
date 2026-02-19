<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi LOA - {{ $code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-lg max-w-md w-full p-8">
        {{-- Header --}}
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4
                {{ $paper ? 'bg-green-100' : 'bg-red-100' }}">
                @if($paper)
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @else
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
            </div>
            
            <h1 class="text-2xl font-bold {{ $paper ? 'text-green-700' : 'text-red-700' }}">
                {{ $paper ? 'LOA Valid ✓' : 'LOA Tidak Ditemukan' }}
            </h1>
            <p class="text-gray-500 text-sm mt-1">Verifikasi Letter of Acceptance</p>
        </div>
        
        @if($paper)
        {{-- Valid LOA Info --}}
        <div class="space-y-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-xs font-bold text-green-800 uppercase tracking-wide mb-3">Informasi LOA</p>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nomor LOA</span>
                        <span class="font-mono font-bold text-gray-800">{{ $code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal Diterima</span>
                        <span class="text-gray-800">{{ $paper->accepted_at?->format('d F Y') ?? '-' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-xs font-bold text-gray-600 uppercase tracking-wide mb-3">Data Paper</p>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-500 block">Judul</span>
                        <span class="text-gray-800 font-medium">{{ $paper->title }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Author</span>
                        <span class="text-gray-800">{{ $paper->user->name }}</span>
                    </div>
                    @if($paper->topic)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Topik</span>
                        <span class="text-gray-800">{{ $paper->topic }}</span>
                    </div>
                    @endif
                </div>
            </div>
            
            @if($paper->conference)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-xs font-bold text-blue-800 uppercase tracking-wide mb-3">Penyelenggara</p>
                <div class="space-y-1 text-sm">
                    <p class="text-gray-800 font-medium">{{ $paper->conference->name }}</p>
                    @if($paper->conference->start_date)
                    <p class="text-gray-500">
                        {{ $paper->conference->start_date->format('d M Y') }}
                        @if($paper->conference->end_date)
                            — {{ $paper->conference->end_date->format('d M Y') }}
                        @endif
                    </p>
                    @endif
                    @if($paper->conference->venue)
                    <p class="text-gray-500">{{ $paper->conference->venue }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @else
        {{-- Invalid Code --}}
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
            <p class="text-sm text-red-700">
                Kode LOA <code class="font-mono bg-red-100 px-1 rounded">{{ $code }}</code> 
                tidak ditemukan dalam sistem kami.
            </p>
            <p class="text-xs text-red-600 mt-2">
                Kemungkinan: dokumen tidak valid, kode salah, atau belum terdaftar.
            </p>
        </div>

        <p class="text-xs text-gray-500 text-center">
            Jika Anda merasa ini adalah kesalahan, hubungi panitia penyelenggara.
        </p>
        @endif
        
        {{-- Footer --}}
        <div class="mt-6 pt-4 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-400">
                Verifikasi dilakukan pada {{ now()->format('d F Y, H:i') }} WIB
            </p>
            <a href="{{ url('/') }}" class="text-xs text-blue-600 hover:underline mt-1 block">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
