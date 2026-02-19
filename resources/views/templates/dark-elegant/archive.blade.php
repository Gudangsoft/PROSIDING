<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Kegiatan - {{ $siteName }}</title>
    <meta name="description" content="Arsip kegiatan prosiding yang telah dilaksanakan">
    <script src="https://cdn.tailwindcss.com"></script>
    @include('templates.dark-elegant.partials.theme-config')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="h-10 w-10 object-contain rounded">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                    @endif
                    @include('partials.navbar-brand')
                </div>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition font-medium">Beranda</a>
                    <a href="{{ route('archive') }}" class="text-sm text-blue-700 bg-blue-50 px-3 py-2 rounded-lg font-medium">Arsip</a>

                    @if($headerMenus->count())
                    @include(\App\Helpers\Template::view('partials.menu-dropdown'), ['items' => $headerMenus, 'depth' => 0])
                    @endif

                    <div class="w-px h-6 bg-gray-200 mx-2"></div>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-medium transition shadow-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 font-medium transition">Login</a>
                        <a href="{{ route('register') }}" class="text-sm bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-medium transition shadow-sm">Register</a>
                    @endauth
                </div>

                {{-- Mobile hamburger --}}
                <div class="md:hidden flex items-center" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-600 hover:text-blue-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false" class="absolute top-16 left-0 right-0 bg-white shadow-lg border-t p-4 space-y-2">
                        <a href="{{ url('/') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg">Beranda</a>
                        <a href="{{ route('archive') }}" class="block px-3 py-2 text-sm text-blue-700 bg-blue-50 rounded-lg font-medium">Arsip</a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block px-3 py-2 text-sm bg-blue-600 text-white rounded-lg text-center font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg">Login</a>
                            <a href="{{ route('register') }}" class="block px-3 py-2 text-sm bg-blue-600 text-white rounded-lg text-center font-medium">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO HEADER --}}
    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur rounded-full px-4 py-1.5 text-sm font-medium mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    Arsip Kegiatan
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-3">Arsip Kegiatan Prosiding</h1>
                <p class="text-blue-100 max-w-2xl mx-auto">Daftar kegiatan prosiding yang telah dilaksanakan sebelumnya</p>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($conferences->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($conferences as $conf)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col">
                {{-- Cover Image --}}
                @if($conf->cover_image)
                <div class="h-48 bg-gray-100 overflow-hidden">
                    <img src="{{ asset('storage/' . $conf->cover_image) }}" alt="{{ $conf->name }}" class="w-full h-full object-cover">
                </div>
                @else
                <div class="h-48 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif

                <div class="p-5 flex-1 flex flex-col">
                    {{-- Acronym Badge --}}
                    <div class="flex items-start justify-between mb-2">
                        @if($conf->acronym)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">{{ $conf->acronym }}</span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $conf->start_date?->format('Y') }}</span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 leading-snug">{{ $conf->name }}</h3>

                    {{-- Theme --}}
                    @if($conf->theme)
                    <p class="text-sm text-gray-500 mb-3 italic line-clamp-2">"{{ $conf->theme }}"</p>
                    @endif

                    {{-- Details --}}
                    <div class="space-y-2 text-sm text-gray-600 mb-4 flex-1">
                        @if($conf->start_date)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ $conf->date_range }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ \App\Models\Conference::VENUE_TYPE_ICONS[$conf->venue_type ?? 'offline'] ?? '' }}"/></svg>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="px-1.5 py-0.5 rounded text-xs font-medium {{ \App\Models\Conference::VENUE_TYPE_COLORS[$conf->venue_type ?? 'offline'] ?? '' }}">{{ $conf->venue_type_label }}</span>
                                {{ $conf->venue_display }}
                            </span>
                        </div>
                        @if($conf->organizer)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>{{ $conf->organizer }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                        @if($conf->topics->count())
                        <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            {{ $conf->topics->count() }} Topik
                        </span>
                        @endif
                        @if($conf->keynoteSpeakers->count())
                        <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $conf->keynoteSpeakers->count() }} Speaker
                        </span>
                        @endif
                        @if($conf->committees->count())
                        <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $conf->committees->count() }} Panitia
                        </span>
                        @endif
                    </div>

                    {{-- View Detail Button --}}
                    <div class="mt-4">
                        <a href="{{ route('archive.show', $conf) }}" class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $conferences->links() }}
        </div>
        @else
        <div class="text-center py-20">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Arsip</h3>
            <p class="text-gray-400">Belum ada kegiatan prosiding yang diarsipkan.</p>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Beranda
            </a>
        </div>
        @endif
    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row justify-between items-center text-sm">
                <p>{{ $footerText ?: '© ' . date('Y') . ' ' . $siteName . '. All rights reserved.' }}</p>
                @if($poweredBy)
                <p class="text-gray-600 mt-1 sm:mt-0">{{ $poweredBy }}</p>
                @endif
            </div>
        </div>
    </footer>

</body>
</html>
