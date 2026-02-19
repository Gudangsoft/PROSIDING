<!DOCTYPE html>
@php
    $siteLanguage = \App\Models\Setting::getValue('site_language', 'id');
    app()->setLocale($siteLanguage);
@endphp
<html lang="{{ $siteLanguage }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('welcome.nav.publikasi') }} — {{ $siteName }}</title>
    @if($siteLogo)<link rel="icon" type="image/png" href="{{ asset('storage/' . $siteLogo) }}">@endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','system-ui','sans-serif']}}}}</script>
    @include('templates.emerald.partials.theme-config')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak]{display:none!important}.glass{background:rgba(255,255,255,0.8);backdrop-filter:blur(20px)}.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
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
                    <a href="{{ route('proceedings') }}" class="text-sm text-teal-700 bg-teal-50 px-3 py-2 rounded-lg font-semibold">{{ __('welcome.nav.publikasi') }}</a>
                    <a href="{{ route('archive') }}" class="text-sm text-gray-500 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50/60 transition font-medium">{{ __('welcome.nav.arsip') }}</a>
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
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-12 text-center">
            <h1 class="text-3xl font-extrabold text-white mb-2">{{ __('welcome.nav.publikasi') }}</h1>
            <div class="flex items-center justify-center gap-2 text-teal-200 text-sm">
                <a href="{{ url('/') }}" class="hover:text-white transition">{{ __('welcome.nav.beranda') }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white">{{ __('welcome.nav.publikasi') }}</span>
            </div>
        </div>
    </div>

    {{-- Stats Strip --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 -mt-6 relative z-10 mb-10">
        <div class="bg-white rounded-2xl shadow-sm border p-5 flex flex-wrap items-center justify-center gap-8">
            <div class="text-center">
                <p class="text-2xl font-extrabold text-teal-700">{{ $totalPapers }}</p>
                <p class="text-xs text-gray-400 font-medium">Total Makalah</p>
            </div>
            <div class="w-px h-8 bg-gray-200 hidden sm:block"></div>
            <div class="text-center">
                <p class="text-2xl font-extrabold text-teal-700">{{ $totalConferences }}</p>
                <p class="text-xs text-gray-400 font-medium">Total Edisi</p>
            </div>
            @if($currentConference)
            <div class="w-px h-8 bg-gray-200 hidden sm:block"></div>
            <div class="text-center">
                <p class="text-sm font-bold text-gray-800">{{ $currentConference->name }}</p>
                <p class="text-xs text-teal-600 font-medium">Edisi Aktif</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Current Conference Papers --}}
    @if($currentConference && $currentPapers->count())
    <section class="max-w-6xl mx-auto px-4 sm:px-6 mb-16">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-1.5 h-8 bg-teal-500 rounded-full"></div>
            <div>
                <h2 class="text-xl font-extrabold text-gray-900">{{ $currentConference->name }}</h2>
                <p class="text-sm text-gray-400">{{ $currentConference->date_time_display }}</p>
            </div>
        </div>
        <div class="space-y-3">
            @foreach($currentPapers as $paper)
            <div class="bg-white rounded-xl border hover:shadow-md hover:border-teal-200 transition p-5 group">
                <h3 class="font-bold text-gray-800 group-hover:text-teal-700 transition mb-2 text-sm leading-relaxed">{{ $paper->title }}</h3>
                <p class="text-xs text-gray-400 mb-3">
                    @foreach($paper->authors ?? [] as $i => $author)
                        {{ $author }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </p>
                <div class="flex flex-wrap items-center gap-3">
                    @if($paper->doi)
                    <a href="https://doi.org/{{ $paper->doi }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-medium text-teal-600 hover:text-teal-800 bg-teal-50 px-3 py-1 rounded-full transition">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        DOI
                    </a>
                    @endif
                    @if($paper->topic)
                    <span class="text-xs bg-gray-100 text-gray-500 px-3 py-1 rounded-full">{{ $paper->topic->name ?? $paper->topic }}</span>
                    @endif
                    @if($paper->pages)
                    <span class="text-xs text-gray-400">pp. {{ $paper->pages }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Unassigned Papers --}}
    @if($unassignedPapers->count())
    <section class="max-w-6xl mx-auto px-4 sm:px-6 mb-16">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-1.5 h-8 bg-gray-400 rounded-full"></div>
            <h2 class="text-xl font-extrabold text-gray-900">Makalah Lainnya</h2>
        </div>
        <div class="space-y-3">
            @foreach($unassignedPapers as $paper)
            <div class="bg-white rounded-xl border hover:shadow-md hover:border-teal-200 transition p-5 group">
                <h3 class="font-bold text-gray-800 group-hover:text-teal-700 transition mb-2 text-sm leading-relaxed">{{ $paper->title }}</h3>
                <p class="text-xs text-gray-400">
                    @foreach($paper->authors ?? [] as $author)
                        {{ $author }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </p>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Past Conferences --}}
    @if($pastConferences->count())
    <section class="max-w-6xl mx-auto px-4 sm:px-6 mb-20">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
            <h2 class="text-xl font-extrabold text-gray-900">Edisi Sebelumnya</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($pastConferences as $conf)
            <a href="{{ route('proceedings.detail', $conf->slug ?? $conf->id) }}" class="group bg-white rounded-2xl border hover:shadow-lg hover:border-teal-200 transition-all p-6">
                <div class="flex items-start gap-4">
                    @if($conf->logo)
                    <img src="{{ asset('storage/' . $conf->logo) }}" alt="{{ $conf->name }}" class="w-14 h-14 object-contain rounded-xl border bg-gray-50 shrink-0">
                    @else
                    <div class="w-14 h-14 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    @endif
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-gray-800 group-hover:text-teal-700 transition line-clamp-2 mb-1">{{ $conf->name }}</h3>
                        <p class="text-xs text-gray-400">{{ $conf->start_date?->translatedFormat('d M Y') }}</p>
                        @if($conf->completed_papers_count ?? false)
                        <p class="text-xs text-teal-600 font-medium mt-1">{{ $conf->completed_papers_count }} makalah</p>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-500">
            <p>{{ $footerText ?: '© ' . date('Y') . ' ' . $siteName }}</p>
            @if($poweredBy)<p class="mt-1 sm:mt-0">{{ $poweredBy }}</p>@endif
        </div>
    </footer>
</body>
</html>
