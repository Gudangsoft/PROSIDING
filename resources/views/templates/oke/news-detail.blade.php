<!DOCTYPE html>
@php
    $siteLanguage = \App\Models\Setting::getValue('site_language', 'id');
    app()->setLocale($siteLanguage);
    $siteName = \App\Models\Setting::getValue('site_name', config('app.name'));
    $siteLogo = \App\Models\Setting::getValue('site_logo');
    $siteFavicon = \App\Models\Setting::getValue('site_favicon');
@endphp
<html lang="{{ $siteLanguage }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }} — {{ $siteName }}</title>
    @if($siteFavicon)<link rel="icon" type="image/png" href="{{ asset('storage/' . $siteFavicon) }}">@endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','system-ui','sans-serif']}}}}</script>
    @include('templates.oke.partials.theme-config')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak]{display:none!important}
        .glass{background:rgba(255,255,255,0.8);backdrop-filter:blur(20px)}
        .prose-content h1,.prose-content h2,.prose-content h3{font-weight:800;color:#1f2937;margin-bottom:0.75rem;margin-top:1.5rem}
        .prose-content h1{font-size:1.5rem}.prose-content h2{font-size:1.25rem}.prose-content h3{font-size:1.1rem}
        .prose-content p{margin-bottom:1rem;line-height:1.75;color:#4b5563}
        .prose-content ul,.prose-content ol{margin-bottom:1rem;padding-left:1.5rem;color:#4b5563}
        .prose-content li{margin-bottom:0.25rem}
        .prose-content img{max-width:100%;height:auto;border-radius:12px;margin:1rem 0}
        .prose-content a{color:var(--theme-link-color, #0d9488);text-decoration:underline}
        .prose-content blockquote{border-left:3px solid var(--theme-primary-color, #14b8a6);padding-left:1rem;color:#6b7280;font-style:italic;margin:1rem 0}
        .prose-content table{width:100%;border-collapse:collapse;margin:1rem 0}
        .prose-content th,.prose-content td{padding:0.5rem;border:1px solid #e5e7eb;text-align:left;font-size:0.875rem}
        .prose-content th{background:#f9fafb;font-weight:600}
        .line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
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
                    <a href="{{ route('archive') }}" class="text-sm text-gray-500 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50/60 transition font-medium">{{ __('welcome.nav.arsip') }}</a>
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

    {{-- Breadcrumb --}}
    <div class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3">
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <a href="{{ url('/') }}" class="hover:text-teal-600 transition">{{ __('welcome.nav.beranda') }}</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-600">{{ __('welcome.news.badge') }}</span>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- Article --}}
            <article class="lg:col-span-2">
                {{-- Cover Image --}}
                @if($news->cover_image)
                <div class="rounded-2xl overflow-hidden mb-8 shadow-sm">
                    <img src="{{ asset('storage/' . $news->cover_image) }}" alt="{{ $news->title }}" class="w-full h-auto max-h-[400px] object-cover">
                </div>
                @endif

                {{-- Meta --}}
                <div class="flex flex-wrap items-center gap-3 mb-5">
                    @if($news->category)
                    <span class="bg-teal-100 text-teal-700 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">{{ $news->category }}</span>
                    @endif
                    <span class="text-xs text-gray-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $news->published_at?->translatedFormat('d F Y') }}
                    </span>
                    @if($news->author)
                    <span class="text-xs text-gray-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ $news->author->name ?? $news->author }}
                    </span>
                    @endif
                </div>

                {{-- Title --}}
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-8 leading-tight">{{ $news->title }}</h1>

                {{-- Content --}}
                <div class="prose-content">
                    {!! $news->content !!}
                </div>

                {{-- Share --}}
                <div class="mt-10 pt-6 border-t border-gray-200">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Bagikan</p>
                    <div class="flex items-center gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                           class="w-9 h-9 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-full flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}" target="_blank"
                           class="w-9 h-9 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-full flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->url()) }}" target="_blank"
                           class="w-9 h-9 bg-green-50 hover:bg-green-100 text-green-600 rounded-full flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href)" title="Salin link"
                                class="w-9 h-9 bg-gray-50 hover:bg-gray-100 text-gray-500 rounded-full flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        </button>
                    </div>
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="space-y-8">
                {{-- Related News --}}
                @if($relatedNews->count())
                <div>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        Berita Terkait
                    </h3>
                    <div class="space-y-3">
                        @foreach($relatedNews as $related)
                        <a href="{{ route('news.detail', $related->slug) }}" class="group flex items-start gap-3 p-3 bg-white rounded-xl border hover:shadow-sm hover:border-teal-200 transition">
                            @if($related->cover_image)
                            <img src="{{ asset('storage/' . $related->cover_image) }}" class="w-16 h-16 rounded-lg object-cover shrink-0" alt="">
                            @else
                            <div class="w-16 h-16 bg-teal-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-gray-800 group-hover:text-teal-700 transition line-clamp-2 mb-1">{{ $related->title }}</h4>
                                <p class="text-[10px] text-gray-400">{{ $related->published_at?->translatedFormat('d M Y') }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Latest News --}}
                @if($latestNews->count())
                <div>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Berita Terbaru
                    </h3>
                    <div class="space-y-3">
                        @foreach($latestNews as $latest)
                        <a href="{{ route('news.detail', $latest->slug) }}" class="group flex items-start gap-3 p-3 bg-white rounded-xl border hover:shadow-sm hover:border-teal-200 transition">
                            @if($latest->cover_image)
                            <img src="{{ asset('storage/' . $latest->cover_image) }}" class="w-16 h-16 rounded-lg object-cover shrink-0" alt="">
                            @else
                            <div class="w-16 h-16 bg-emerald-50 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-gray-800 group-hover:text-teal-700 transition line-clamp-2 mb-1">{{ $latest->title }}</h4>
                                <p class="text-[10px] text-gray-400">{{ $latest->published_at?->translatedFormat('d M Y') }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
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
