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
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-12">
            <div class="text-center">
                <h1 class="text-2xl md:text-3xl font-extrabold text-white mb-2">{{ $conference->name }}</h1>
                @if($conference->theme)
                <p class="text-teal-200 text-sm italic mb-3">"{{ $conference->theme }}"</p>
                @endif
                <div class="flex items-center justify-center gap-2 text-teal-200 text-sm">
                    <a href="{{ url('/') }}" class="hover:text-white transition">{{ __('welcome.nav.beranda') }}</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <a href="{{ route('proceedings') }}" class="hover:text-white transition">{{ __('welcome.nav.publikasi') }}</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-white">Detail</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 -mt-6 relative z-10 mb-10">
        <div class="bg-white rounded-2xl shadow-sm border p-5 flex flex-wrap items-center justify-center gap-8">
            <div class="text-center">
                <p class="text-2xl font-extrabold text-teal-700">{{ $totalPapers }}</p>
                <p class="text-xs text-gray-400 font-medium">Makalah</p>
            </div>
            <div class="w-px h-8 bg-gray-200 hidden sm:block"></div>
            <div class="text-center">
                <p class="text-2xl font-extrabold text-teal-700">{{ $totalAuthors }}</p>
                <p class="text-xs text-gray-400 font-medium">Penulis</p>
            </div>
            @if($topics->count())
            <div class="w-px h-8 bg-gray-200 hidden sm:block"></div>
            <div class="text-center">
                <p class="text-2xl font-extrabold text-teal-700">{{ $topics->count() }}</p>
                <p class="text-xs text-gray-400 font-medium">Topik</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Search & Filter --}}
    <section x-data="{
        search: '',
        selectedTopic: '',
        sortBy: 'title_asc',
        get filteredPapers() { return true; }
    }" class="max-w-6xl mx-auto px-4 sm:px-6 mb-20">
        <div class="bg-white rounded-2xl border p-5 mb-8">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input x-model="search" type="text" placeholder="Cari judul, penulis, kata kunci..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition outline-none">
                </div>
                @if($topics->count())
                <select x-model="selectedTopic" class="bg-gray-50 border border-gray-200 rounded-xl text-sm px-4 py-2.5 focus:border-teal-400 focus:ring-2 focus:ring-teal-100 outline-none">
                    <option value="">Semua Topik</option>
                    @foreach($topics as $topic)
                    <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                    @endforeach
                </select>
                @endif
                <select x-model="sortBy" class="bg-gray-50 border border-gray-200 rounded-xl text-sm px-4 py-2.5 focus:border-teal-400 focus:ring-2 focus:ring-teal-100 outline-none">
                    <option value="title_asc">Judul A-Z</option>
                    <option value="title_desc">Judul Z-A</option>
                    <option value="date_desc">Terbaru</option>
                    <option value="date_asc">Terlama</option>
                </select>
            </div>
        </div>

        {{-- Papers List --}}
        <div class="space-y-3">
            @forelse($papers as $paper)
            <div class="bg-white rounded-xl border hover:shadow-md hover:border-teal-200 transition p-5 group"
                 x-show="
                    (search === '' || '{{ strtolower(addslashes($paper->title)) }}'.includes(search.toLowerCase()) || '{{ strtolower(addslashes(implode(' ', $paper->authors ?? []))) }}'.includes(search.toLowerCase()))
                    && (selectedTopic === '' || '{{ $paper->topic_id ?? '' }}' === selectedTopic)
                 "
                 x-transition>
                <div class="flex gap-4">
                    <div class="hidden sm:flex w-10 h-10 bg-teal-50 rounded-xl items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-800 group-hover:text-teal-700 transition mb-1.5 text-sm leading-relaxed">{{ $paper->title }}</h3>
                        <p class="text-xs text-gray-400 mb-3">
                            @foreach($paper->authors ?? [] as $author)
                                {{ $author }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                        @if($paper->abstract)
                        <details class="group/details mb-3">
                            <summary class="text-xs text-teal-600 cursor-pointer hover:text-teal-800 font-medium select-none">
                                <span class="group-open/details:hidden">Tampilkan Abstrak</span>
                                <span class="hidden group-open/details:inline">Sembunyikan Abstrak</span>
                            </summary>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed bg-gray-50 rounded-lg p-3">{{ $paper->abstract }}</p>
                        </details>
                        @endif
                        <div class="flex flex-wrap items-center gap-2">
                            @if($paper->doi)
                            <a href="https://doi.org/{{ $paper->doi }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium text-teal-600 hover:text-teal-800 bg-teal-50 px-2.5 py-1 rounded-full transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                DOI
                            </a>
                            @endif
                            @if($paper->topic)
                            <span class="text-xs bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full">{{ $paper->topic->name ?? $paper->topic }}</span>
                            @endif
                            @if($paper->pages)
                            <span class="text-xs text-gray-400">pp. {{ $paper->pages }}</span>
                            @endif
                            @if($paper->keywords)
                            @foreach(array_slice($paper->keywords, 0, 3) as $kw)
                            <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">{{ $kw }}</span>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9.75m3 0h3.75M9 15h3M5.625 21h12.75c.621 0 1.125-.504 1.125-1.125V8.625a.375.375 0 00-.375-.375h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5a.375.375 0 00-.375-.375H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                <p class="text-gray-400 font-medium">Belum ada makalah yang dipublikasikan.</p>
            </div>
            @endforelse
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-500">
            <p>{{ $footerText ?: '© ' . date('Y') . ' ' . $siteName }}</p>
            @if($poweredBy)<p class="mt-1 sm:mt-0">{{ $poweredBy }}</p>@endif
        </div>
    </footer>
</body>
</html>
