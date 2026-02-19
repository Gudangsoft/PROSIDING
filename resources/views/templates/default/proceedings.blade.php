<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publikasi - {{ $siteName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('templates.default.partials.theme-config')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />
    <style>
        [x-cloak]{display:none!important} body{font-family:'Inter',sans-serif;}
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" x-data="{ mobileOpen: false }">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2">
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" class="h-8" alt="{{ $siteName }}">
                    @endif
                    @include('partials.navbar-brand')
                </a>
                <div class="hidden md:flex items-center space-x-1">
                    <a href="/" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition font-medium">Beranda</a>
                    <a href="{{ route('proceedings') }}" class="text-sm text-blue-700 bg-blue-50 px-3 py-2 rounded-lg font-medium">Publikasi</a>
                    <a href="{{ route('archive') }}" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition font-medium">Arsip</a>
                    @if($headerMenus->count())
                    @include(\App\Helpers\Template::view('partials.menu-dropdown'), ['items' => $headerMenus, 'depth' => 0])
                    @endif
                    <div class="w-px h-6 bg-gray-200 mx-2"></div>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-medium transition shadow-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="text-sm bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-medium transition shadow-sm">Register</a>
                    @endauth
                </div>
                <button @click="mobileOpen=!mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
            <div x-show="mobileOpen" x-cloak x-transition class="md:hidden pb-4 border-t space-y-1 pt-2">
                <a href="/" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg">Beranda</a>
                <a href="{{ route('proceedings') }}" class="block px-3 py-2 text-sm text-blue-700 bg-blue-50 rounded-lg font-medium">Publikasi</a>
                <a href="{{ route('archive') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg">Arsip</a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <div class="bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
            <h1 class="text-3xl md:text-4xl font-extrabold mb-3">Publikasi Prosiding</h1>
            <p class="text-blue-200 text-lg max-w-2xl mx-auto">Kumpulan paper yang telah dipublikasikan per kegiatan prosiding</p>
            <div class="mt-8 flex justify-center gap-8">
                <div class="text-center">
                    <p class="text-3xl font-extrabold">{{ $totalPapers }}</p>
                    <p class="text-blue-200 text-sm">Total Paper</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-extrabold">{{ $totalConferences }}</p>
                    <p class="text-blue-200 text-sm">Kegiatan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-1">

        {{-- ══════════════════════════════════════════════════
             EDISI TERBARU — Current / Latest Conference
             ══════════════════════════════════════════════════ --}}
        @if($currentConference)
        <section class="mb-12">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-800">Edisi Terbaru</h2>
                    <p class="text-sm text-gray-500">Kegiatan prosiding terkini</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                {{-- Conference Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 md:p-8 text-white">
                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        @if($currentConference->cover_image)
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $currentConference->cover_image) }}" alt="{{ $currentConference->name }}" class="w-36 h-48 object-cover rounded-xl shadow-lg border-2 border-white/20">
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur rounded-full px-3 py-1 text-xs font-semibold mb-3">
                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                Edisi Terbaru
                            </div>
                            <h3 class="text-2xl md:text-3xl font-extrabold leading-tight">{{ $currentConference->name }}</h3>
                            @if($currentConference->theme)
                            <p class="text-blue-100 mt-2 text-sm md:text-base italic">"{{ $currentConference->theme }}"</p>
                            @endif
                            <div class="flex flex-wrap gap-4 mt-4 text-sm text-blue-100">
                                @if($currentConference->start_date)
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $currentConference->date_range }}
                                </div>
                                @endif
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ \App\Models\Conference::VENUE_TYPE_ICONS[$currentConference->venue_type ?? 'offline'] ?? '' }}"/></svg>
                                    <span class="bg-white/15 px-1.5 py-0.5 rounded text-xs font-medium">{{ $currentConference->venue_type_label }}</span>
                                    {{ $currentConference->venue_display }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    {{ $currentPapers->count() }} Paper
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table of Contents — Papers List --}}
                <div class="divide-y divide-gray-100">
                    <div class="px-6 md:px-8 py-4 bg-gray-50 border-b">
                        <h4 class="text-sm font-bold text-gray-600 uppercase tracking-wider">Daftar Paper</h4>
                    </div>

                    @forelse($currentPapers->take(10) as $paper)
                    <div class="px-6 md:px-8 py-5 hover:bg-blue-50/30 transition">
                        <div class="flex items-start gap-4">
                            <div class="flex-1 min-w-0">
                                <h5 class="text-base font-bold text-gray-800 leading-snug">{{ $paper->title }}</h5>
                                <div class="flex items-center gap-1.5 mt-1.5 text-sm text-gray-500">
                                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>
                                        @if($paper->authors_meta && count($paper->authors_meta))
                                            {{ collect($paper->authors_meta)->pluck('name')->join(', ') }}
                                        @else
                                            {{ $paper->user->name }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    @if($paper->topic)
                                    <span class="inline-flex items-center text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-medium">{{ $paper->topic }}</span>
                                    @endif
                                    @if($paper->accepted_at)
                                    <span class="text-xs text-gray-400">{{ $paper->accepted_at->format('d M Y') }}</span>
                                    @endif
                                </div>

                                @if($paper->abstract)
                                <div x-data="{ open: false }" class="mt-2">
                                    <button @click="open = !open" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                        <svg class="w-3 h-3 transition-transform" :class="open && 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        Lihat Abstrak
                                    </button>
                                    <div x-show="open" x-cloak x-transition class="mt-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-4 leading-relaxed">{{ $paper->abstract }}</div>
                                </div>
                                @endif
                            </div>

                            @php $fullPaper = $paper->files->whereIn('type', ['full_paper', 'revision'])->sortByDesc('created_at')->first(); @endphp
                            @if($fullPaper)
                            <div class="flex-shrink-0">
                                <a href="{{ asset('storage/' . $fullPaper->file_path) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 hover:bg-green-100 px-3 py-1.5 rounded-lg text-xs font-medium transition border border-green-200" title="Download PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    PDF
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="px-6 md:px-8 py-8 text-center text-gray-400">Belum ada paper yang dipublikasikan.</div>
                    @endforelse
                </div>

                @if($currentPapers->count() > 0)
                <div class="px-6 md:px-8 py-4 bg-gray-50 border-t text-center">
                    <a href="{{ route('proceedings.show', $currentConference) }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center gap-1">
                        Lihat Semua Paper ({{ $currentPapers->count() }})
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                @endif
            </div>
        </section>
        @endif

        {{-- ══════════════════════════════════════════════════
             EDISI SEBELUMNYA — Past Conferences
             ══════════════════════════════════════════════════ --}}
        @if($pastConferences->count())
        <section class="mb-12">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1.5 h-8 bg-gray-400 rounded-full"></div>
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-800">Edisi Sebelumnya</h2>
                    <p class="text-sm text-gray-500">Kegiatan prosiding yang telah lalu</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($pastConferences as $conf)
                <a href="{{ route('proceedings.show', $conf) }}" class="bg-white rounded-xl shadow-sm border hover:shadow-md hover:border-blue-200 transition group overflow-hidden">
                    @if($conf->cover_image)
                    <div class="h-40 overflow-hidden">
                        <img src="{{ asset('storage/' . $conf->cover_image) }}" alt="{{ $conf->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    @else
                    <div class="h-32 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    @endif
                    <div class="p-5">
                        <h3 class="font-bold text-gray-800 group-hover:text-blue-700 transition leading-snug">{{ $conf->name }}</h3>
                        @if($conf->theme)
                        <p class="text-xs text-gray-500 italic mt-1 line-clamp-2">"{{ $conf->theme }}"</p>
                        @endif
                        <div class="flex flex-wrap gap-3 mt-3 text-xs text-gray-400">
                            @if($conf->start_date)
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $conf->date_range }}
                            </div>
                            @endif
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                {{ $conf->completed_papers_count }} Paper
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ══════════════════════════════════════════════════
             PAPER LAINNYA — Unassigned Papers (legacy)
             ══════════════════════════════════════════════════ --}}
        @if($unassignedPapers->count())
        <section class="mb-12">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1.5 h-8 bg-amber-400 rounded-full"></div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Paper Lainnya</h2>
                    <p class="text-sm text-gray-500">Paper yang telah dipublikasikan</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden divide-y divide-gray-100">
                @foreach($unassignedPapers as $paper)
                <div class="px-6 md:px-8 py-5 hover:bg-blue-50/30 transition">
                    <div class="flex items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <h5 class="text-base font-bold text-gray-800 leading-snug">{{ $paper->title }}</h5>
                            <div class="flex items-center gap-1.5 mt-1.5 text-sm text-gray-500">
                                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>
                                    @if($paper->authors_meta && count($paper->authors_meta))
                                        {{ collect($paper->authors_meta)->pluck('name')->join(', ') }}
                                    @else
                                        {{ $paper->user->name }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                @if($paper->topic)
                                <span class="inline-flex items-center text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-medium">{{ $paper->topic }}</span>
                                @endif
                                @if($paper->accepted_at)
                                <span class="text-xs text-gray-400">{{ $paper->accepted_at->format('d M Y') }}</span>
                                @endif
                            </div>
                        </div>
                        @php $fullPaper = $paper->files->whereIn('type', ['full_paper', 'revision'])->sortByDesc('created_at')->first(); @endphp
                        @if($fullPaper)
                        <a href="{{ asset('storage/' . $fullPaper->file_path) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 hover:bg-green-100 px-3 py-1.5 rounded-lg text-xs font-medium transition border border-green-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            PDF
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Empty State --}}
        @if(!$currentConference && $pastConferences->count() === 0 && $unassignedPapers->count() === 0)
        <div class="bg-white rounded-xl shadow-sm border p-16 text-center">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <h3 class="text-xl font-bold text-gray-600 mb-2">Belum ada publikasi</h3>
            <p class="text-gray-400">Paper yang telah selesai diproses akan ditampilkan di halaman ini per kegiatan.</p>
        </div>
        @endif
    </div>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
            <p>{{ $footerText ?? '© ' . date('Y') . ' ' . $siteName }}</p>
            @if($poweredBy)<p class="mt-1 text-gray-500 text-xs">{!! $poweredBy !!}</p>@endif
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
