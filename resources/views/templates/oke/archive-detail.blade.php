<!DOCTYPE html>
@php
    $siteLanguage = \App\Models\Setting::getValue('site_language', 'id');
    app()->setLocale($siteLanguage);
@endphp
<html lang="{{ $siteLanguage }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $conference->name }} — {{ $siteName }}</title>
    @if($siteLogo)<link rel="icon" type="image/png" href="{{ asset('storage/' . $siteLogo) }}">@endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','system-ui','sans-serif']}}}}</script>
    @include('templates.oke.partials.theme-config')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak]{display:none!important}.glass{background:rgba(255,255,255,0.8);backdrop-filter:blur(20px)}
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased text-gray-700">

    {{-- NAVBAR --}}
    <nav class="glass shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="h-9 w-9 object-contain rounded-lg">
                    @else
                        <div class="w-9 h-9 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"/></svg>
                        </div>
                    @endif
                    @include('partials.navbar-brand')
                </a>
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50/60 transition font-medium">{{ __('welcome.nav.beranda') }}</a>
                    <a href="{{ route('proceedings') }}" class="text-sm text-gray-500 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50/60 transition font-medium">{{ __('welcome.nav.publikasi') }}</a>
                    <a href="{{ route('archive') }}" class="text-sm text-teal-700 bg-teal-50 px-3 py-2 rounded-lg font-semibold">{{ __('welcome.nav.arsip') }}</a>
                    @if($headerMenus->count())
                    @include(\App\Helpers\Template::view('partials.menu-dropdown'), ['items' => $headerMenus, 'depth' => 0])
                    @endif
                    <div class="w-px h-5 bg-gray-200 mx-2"></div>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm bg-teal-600 text-white px-5 py-2 rounded-full hover:bg-teal-700 font-medium transition shadow-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-teal-700 px-3 py-2 font-medium transition">Login</a>
                        <a href="{{ route('register') }}" class="text-sm bg-teal-600 text-white px-5 py-2 rounded-full hover:bg-teal-700 font-medium transition shadow-sm">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Breadcrumb Header --}}
    <div class="bg-gradient-to-r from-teal-600 to-emerald-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAwIDEwIEwgNDAgMTAgTSAxMCAwIEwgMTAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] opacity-100"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-12">
            <div class="text-center">
                <h1 class="text-2xl md:text-3xl font-extrabold text-white mb-2">{{ $conference->name }}</h1>
                @if($conference->theme)
                <p class="text-teal-200 text-sm italic mb-3">"{{ $conference->theme }}"</p>
                @endif
                <div class="flex items-center justify-center gap-2 text-teal-200 text-sm">
                    <a href="{{ url('/') }}" class="hover:text-white transition">{{ __('welcome.nav.beranda') }}</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <a href="{{ route('archive') }}" class="hover:text-white transition">{{ __('welcome.nav.arsip') }}</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-white">Detail</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left: Conference Details --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Conference Info Card --}}
                <div class="bg-white rounded-2xl border p-6">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-5 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        Informasi Konferensi
                    </h2>
                    <div class="space-y-4">
                        @if($conference->start_date)
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-teal-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Tanggal</p>
                                <p class="text-sm font-bold text-gray-800">{{ $conference->start_date->translatedFormat('d F Y') }}@if($conference->end_date && $conference->end_date->ne($conference->start_date)) — {{ $conference->end_date->translatedFormat('d F Y') }}@endif</p>
                                @if($conference->formatted_time)
                                <p class="text-xs text-gray-500 mt-0.5">🕐 {{ $conference->formatted_time }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($conference->venue || $conference->venue_address)
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Lokasi</p>
                                <p class="text-sm font-bold text-gray-800">{{ $conference->venue_type_label }} — {{ $conference->venue_display }}</p>
                            </div>
                        </div>
                        @endif

                        @if($conference->organizer)
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-cyan-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Penyelenggara</p>
                                <p class="text-sm font-bold text-gray-800">{{ $conference->organizer }}</p>
                            </div>
                        </div>
                        @endif

                        @if($completedPapersCount > 0)
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Makalah</p>
                                <p class="text-sm font-bold text-gray-800">{{ $completedPapersCount }} makalah selesai</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                @if($conference->description)
                <div class="bg-white rounded-2xl border p-6">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Deskripsi
                    </h2>
                    <div class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($conference->description)) !!}</div>
                </div>
                @endif

                {{-- Topics --}}
                @if($conference->topics->count())
                <div class="bg-white rounded-2xl border p-6">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                        Topik / Bidang
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($conference->topics->sortBy('sort_order') as $topic)
                        <span class="text-sm bg-teal-50 text-teal-700 px-3 py-1.5 rounded-full border border-teal-200 font-medium">{{ $topic->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Keynote Speakers --}}
                @if($conference->keynoteSpeakers->count())
                <div class="bg-white rounded-2xl border p-6">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-5 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Keynote Speakers
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($conference->keynoteSpeakers->sortBy('sort_order') as $speaker)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            @if($speaker->photo)
                            <img src="{{ asset('storage/' . $speaker->photo) }}" alt="{{ $speaker->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-teal-100">
                            @else
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center text-white text-lg font-bold">
                                {{ strtoupper(substr($speaker->name, 0, 1)) }}
                            </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate">{{ $speaker->name }}</p>
                                @if($speaker->institution)
                                <p class="text-xs text-gray-400 truncate">{{ $speaker->institution }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Committees --}}
                @if($conference->committees->count())
                <div class="bg-white rounded-2xl border p-6">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-5 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                        Panitia
                    </h2>
                    @php $committeeGroups = $conference->committees->sortBy('sort_order')->groupBy('type'); @endphp
                    @foreach($committeeGroups as $type => $members)
                    <div class="@if(!$loop->first)mt-5 pt-5 border-t border-gray-100@endif">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">{{ \App\Models\Committee::TYPE_LABELS[$type] ?? ucfirst($type) }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($members as $member)
                            <div class="flex items-center gap-2 text-sm">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-teal-300 to-emerald-400 flex items-center justify-center text-white text-[10px] font-bold shrink-0">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <span class="text-gray-700 truncate">{{ $member->name }}{{ $member->title ? ', ' . $member->title : '' }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Right Sidebar --}}
            <div class="space-y-6">
                {{-- Poster --}}
                @php $poster = $conference->cover_image ?? $conference->brochure; @endphp
                @if($poster)
                <div class="bg-white rounded-2xl border overflow-hidden" x-data="{ show: false }">
                    <div class="cursor-pointer" @click="show = true">
                        <img src="{{ asset('storage/' . $poster) }}" alt="{{ $conference->name }}" class="w-full h-auto hover:opacity-90 transition">
                    </div>
                    <div x-show="show" x-cloak x-transition @click="show = false" @keydown.escape.window="show = false"
                         class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 cursor-zoom-out">
                        <img src="{{ asset('storage/' . $poster) }}" alt="{{ $conference->name }}" class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl" @click.stop>
                    </div>
                </div>
                @endif

                {{-- Quick Stats --}}
                <div class="bg-white rounded-2xl border p-5 space-y-4">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Ringkasan
                    </h3>
                    @if($conference->start_date)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Tanggal</span>
                        <span class="font-medium text-gray-800">{{ $conference->start_date->translatedFormat('d M Y') }}</span>
                    </div>
                    @endif
                    @if($completedPapersCount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Makalah</span>
                        <span class="font-medium text-teal-700">{{ $completedPapersCount }}</span>
                    </div>
                    @endif
                    @if($conference->topics->count())
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Topik</span>
                        <span class="font-medium text-gray-800">{{ $conference->topics->count() }}</span>
                    </div>
                    @endif
                    @if($conference->keynoteSpeakers->count())
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Speakers</span>
                        <span class="font-medium text-gray-800">{{ $conference->keynoteSpeakers->count() }}</span>
                    </div>
                    @endif
                </div>

                {{-- Links --}}
                <div class="bg-white rounded-2xl border p-5 space-y-3">
                    <a href="{{ route('proceedings.detail', $conference->slug ?? $conference->id) }}" class="flex items-center gap-3 text-sm font-medium text-teal-700 hover:text-teal-800 bg-teal-50 hover:bg-teal-100 rounded-xl px-4 py-3 transition group">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Lihat Prosiding
                        <svg class="w-4 h-4 ml-auto group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('archive') }}" class="flex items-center gap-3 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-xl px-4 py-3 transition group">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                        Kembali ke Arsip
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-500">
            <p>{{ $footerText ?: '© ' . date('Y') . ' ' . $siteName }}</p>
            @if($poweredBy)<p class="mt-1 sm:mt-0">{{ $poweredBy }}</p>@endif
        </div>
    </footer>
</body>
</html>
