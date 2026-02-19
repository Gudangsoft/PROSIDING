<!DOCTYPE html>
@php
    $siteLanguage = \App\Models\Setting::getValue('site_language', 'id');
    app()->setLocale($siteLanguage);
    $siteName = \App\Models\Setting::getValue('site_name', config('app.name'));
    $siteLogo = \App\Models\Setting::getValue('site_logo');
    $siteFavicon = \App\Models\Setting::getValue('site_favicon');
    $footerText = \App\Models\Setting::getValue('footer_text');
    $poweredBy = \App\Models\Setting::getValue('powered_by', 'Powered by Laravel');
@endphp
<html lang="{{ $siteLanguage }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->meta_title ?: $page->title }} — {{ $siteName }}</title>
    @if($siteFavicon)<link rel="icon" type="image/png" href="{{ asset('storage/' . $siteFavicon) }}">@endif
    @if($page->meta_description)
    <meta name="description" content="{{ $page->meta_description }}">
    @elseif($page->excerpt)
    <meta name="description" content="{{ $page->excerpt }}">
    @endif
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $page->meta_title ?: $page->title }}">
    <meta property="og:description" content="{{ $page->meta_description ?: $page->excerpt ?: Str::limit(strip_tags($page->content), 160) }}">
    <meta property="og:url" content="{{ request()->url() }}">
    @if($page->cover_image)
    <meta property="og:image" content="{{ asset('storage/' . $page->cover_image) }}">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','system-ui','sans-serif']}}}}</script>
    @include('templates.emerald.partials.theme-config')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak]{display:none!important}
        .glass{background:rgba(255,255,255,0.8);backdrop-filter:blur(20px)}
        .prose-content h1,.prose-content h2,.prose-content h3{font-weight:800;color:#1f2937;margin-bottom:0.75rem;margin-top:1.5rem}
        .prose-content h1{font-size:1.5rem}.prose-content h2{font-size:1.25rem}.prose-content h3{font-size:1.1rem}
        .prose-content p{margin-bottom:1rem;line-height:1.75;color:#4b5563}
        .prose-content ul,.prose-content ol{margin-bottom:1rem;padding-left:1.5rem;color:#4b5563}
        .prose-content li{margin-bottom:0.25rem}
        .prose-content ul li{list-style-type:disc}.prose-content ol li{list-style-type:decimal}
        .prose-content img{max-width:100%;height:auto;border-radius:12px;margin:1rem 0}
        .prose-content a{color:var(--theme-link-color, #0d9488);text-decoration:underline}
        .prose-content blockquote{border-left:3px solid var(--theme-primary-color, #14b8a6);padding-left:1rem;color:#6b7280;font-style:italic;margin:1rem 0}
        .prose-content table{width:100%;border-collapse:collapse;margin:1rem 0}
        .prose-content th,.prose-content td{padding:0.5rem;border:1px solid #e5e7eb;text-align:left;font-size:0.875rem}
        .prose-content th{background:#f9fafb;font-weight:600}
        .prose-content pre{background:#1f2937;color:#e5e7eb;padding:1rem;border-radius:0.75rem;overflow-x:auto;margin:1rem 0;font-size:0.875rem}
        .prose-content code{background:#f3f4f6;padding:0.15em 0.3em;border-radius:0.25rem;font-size:0.875em}
        .prose-content pre code{background:transparent;padding:0}
        .prose-content hr{border:none;border-top:1px solid #e5e7eb;margin:1.5rem 0}
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
    @if($page->layout !== 'blank')
    <div class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3">
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <a href="{{ url('/') }}" class="hover:text-teal-600 transition">{{ __('welcome.nav.beranda') }}</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-600">{{ $page->title }}</span>
            </div>
        </div>
    </div>
    @endif

    {{-- Hero Header --}}
    @if($page->layout !== 'blank')
    <div class="relative overflow-hidden" style="background: linear-gradient(135deg, var(--theme-hero-bg, #0f766e), var(--theme-secondary-color, #059669));">
        @if($page->cover_image)
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $page->cover_image) }}" alt="" class="w-full h-full object-cover opacity-15">
        </div>
        @endif
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-14 text-center">
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-3 leading-tight">{{ $page->title }}</h1>
            @if($page->excerpt)
            <p class="text-base text-white/75 max-w-2xl mx-auto">{{ $page->excerpt }}</p>
            @endif
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        @if($page->layout === 'sidebar')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <article class="lg:col-span-2">
                <div class="bg-white rounded-2xl border p-8 md:p-10 shadow-sm">
                    <div class="prose-content">
                        {!! $page->content !!}
                    </div>
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="space-y-6">
                @if($relatedPages->isNotEmpty())
                <div class="bg-white rounded-xl border p-5 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        Halaman Lainnya
                    </h3>
                    <div class="space-y-2">
                        @foreach($relatedPages as $rp)
                        <a href="{{ route('page.show', $rp->slug) }}"
                           class="group flex items-center gap-2 p-2 rounded-lg hover:bg-teal-50/60 transition text-sm {{ $rp->id === $page->id ? 'text-teal-700 font-semibold bg-teal-50/40' : 'text-gray-600' }}">
                            <svg class="w-3 h-3 shrink-0 {{ $rp->id === $page->id ? 'text-teal-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            {{ $rp->title }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
        </div>
        @else
        <article class="{{ $page->layout === 'narrow' ? 'max-w-3xl mx-auto' : '' }}">
            <div class="bg-white rounded-2xl border p-8 md:p-10 shadow-sm">
                @if($page->layout === 'blank')
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 leading-tight">{{ $page->title }}</h1>
                @endif
                <div class="prose-content">
                    {!! $page->content !!}
                </div>
            </div>
        </article>
        @endif
    </section>

    {{-- Footer --}}
    @if($page->layout !== 'blank')
    <footer class="bg-gray-900 text-gray-400 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-500">
            <p>{{ $footerText ?: '© ' . date('Y') . ' ' . $siteName }}</p>
            @if($poweredBy)<p class="mt-1 sm:mt-0">{!! $poweredBy !!}</p>@endif
        </div>
    </footer>
    @endif
</body>
</html>
