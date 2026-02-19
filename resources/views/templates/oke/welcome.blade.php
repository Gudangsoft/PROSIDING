<!DOCTYPE html>
@php
    $siteLanguage = \App\Models\Setting::getValue('site_language', 'id');
    app()->setLocale($siteLanguage);
@endphp
<html lang="{{ $siteLanguage }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteName = \App\Models\Setting::getValue('site_name', config('app.name'));
        $siteTagline = \App\Models\Setting::getValue('site_tagline', 'Sistem Prosiding Online');
        $siteLogo = \App\Models\Setting::getValue('site_logo');
        $siteDescription = \App\Models\Setting::getValue('site_description');
        $siteKeywords = \App\Models\Setting::getValue('site_keywords');
        $googleAnalytics = \App\Models\Setting::getValue('google_analytics');
        $contactEmail = \App\Models\Setting::getValue('contact_email');
        $contactPhone = \App\Models\Setting::getValue('contact_phone');
        $contactAddress = \App\Models\Setting::getValue('contact_address');
        $footerText = \App\Models\Setting::getValue('footer_text');
        $socialFacebook = \App\Models\Setting::getValue('social_facebook');
        $socialInstagram = \App\Models\Setting::getValue('social_instagram');
        $socialTwitter = \App\Models\Setting::getValue('social_twitter');
        $socialYoutube = \App\Models\Setting::getValue('social_youtube');
        $publicationInfo = \App\Models\Setting::getValue('publication_info', 'Setiap makalah yang diterima dipublikasikan di Prosiding Seminar Nasional.');
        $selectedPapersInfo = \App\Models\Setting::getValue('selected_papers_info', 'Makalah terpilih akan diterbitkan pada jurnal-jurnal terindeks.');
        $poweredBy = \App\Models\Setting::getValue('powered_by', 'Powered by Laravel');
        $siteFavicon = \App\Models\Setting::getValue('site_favicon');
    @endphp
    <title>{{ $siteName }}</title>
    @if($siteDescription)<meta name="description" content="{{ $siteDescription }}">@endif
    @if($siteKeywords)<meta name="keywords" content="{{ $siteKeywords }}">@endif
    @if($googleAnalytics)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalytics }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $googleAnalytics }}');</script>
    @endif
    @if($siteFavicon)<link rel="icon" type="image/png" href="{{ asset('storage/' . $siteFavicon) }}">@endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','system-ui','sans-serif']}}}}</script>
    @include('templates.oke.partials.theme-config')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak]{display:none!important}
        html{scroll-behavior:smooth}
        .glass{background:rgba(255,255,255,0.8);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px)}
        .line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .line-clamp-3{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
    </style>
</head>
<body class="bg-white min-h-screen font-sans antialiased text-gray-700">

    {{-- ════════════════════════════════════════════════════
         NAVBAR — Glassmorphism, minimal, elegant
    ════════════════════════════════════════════════════ --}}
    <nav x-data="{ scrolled: false, mobileOpen: false }"
         @scroll.window="scrolled = (window.scrollY > 40)"
         :class="scrolled ? 'glass shadow-sm border-b border-gray-100' : 'bg-transparent'"
         class="fixed top-0 w-full z-50 transition-all duration-500">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-16 items-center">
                {{-- Logo --}}
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

                {{-- Desktop nav --}}
                <div class="hidden md:flex items-center gap-1">
                    @if($activeConference)
                    <a href="#about" class="text-sm text-gray-500 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50/60 transition font-medium">{{ __('welcome.nav.tentang') }}</a>
                    <a href="#dates" class="text-sm text-gray-500 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50/60 transition font-medium">{{ __('welcome.nav.tanggal_penting') }}</a>
                    @if($activeConference->keynoteSpeakers->count())
                    <a href="#speakers" class="text-sm text-gray-500 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50/60 transition font-medium">{{ __('welcome.nav.speaker') }}</a>
                    @endif
                    @if($activeConference->journalPublications->where('is_active', true)->count())
                    <a href="#journals" class="text-sm text-gray-500 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50/60 transition font-medium">{{ __('welcome.nav.jurnal') }}</a>
                    @endif
                    @endif
                    @if($latestNews->count())
                    <a href="#news" class="text-sm text-gray-500 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50/60 transition font-medium">{{ __('welcome.nav.berita') }}</a>
                    @endif
                    <a href="{{ route('proceedings') }}" class="text-sm text-gray-500 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50/60 transition font-medium">{{ __('welcome.nav.publikasi') }}</a>
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

                {{-- Mobile hamburger --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden text-gray-600 hover:text-teal-700 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile menu --}}
            <div x-show="mobileOpen" x-cloak x-transition class="md:hidden pb-4 border-t border-gray-100 space-y-1 pt-2">
                @if($activeConference)
                <a href="#about" @click="mobileOpen=false" class="block px-3 py-2 text-sm text-gray-600 hover:bg-teal-50 rounded-lg">{{ __('welcome.nav.tentang') }}</a>
                <a href="#dates" @click="mobileOpen=false" class="block px-3 py-2 text-sm text-gray-600 hover:bg-teal-50 rounded-lg">{{ __('welcome.nav.tanggal_penting') }}</a>
                @endif
                @if($latestNews->count())
                <a href="#news" @click="mobileOpen=false" class="block px-3 py-2 text-sm text-gray-600 hover:bg-teal-50 rounded-lg">{{ __('welcome.nav.berita') }}</a>
                @endif
                <a href="{{ route('proceedings') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-teal-50 rounded-lg">{{ __('welcome.nav.publikasi') }}</a>
                <a href="{{ route('archive') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-teal-50 rounded-lg">{{ __('welcome.nav.arsip') }}</a>
                @if($headerMenus->count())
                <div class="border-t border-gray-100 pt-1 mt-1">
                    @include(\App\Helpers\Template::view('partials.menu-mobile'), ['items' => $headerMenus, 'depth' => 0])
                </div>
                @endif
                <div class="flex gap-2 pt-2 px-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm bg-teal-600 text-white px-4 py-2 rounded-full font-medium w-full text-center">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm border border-gray-300 text-gray-700 px-4 py-2 rounded-full font-medium flex-1 text-center">Login</a>
                        <a href="{{ route('register') }}" class="text-sm bg-teal-600 text-white px-4 py-2 rounded-full font-medium flex-1 text-center">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <div class="h-16"></div>


    {{-- ═══════════════════════════════════════════════════════════════════
         DYNAMIC SECTIONS — Rendered based on ThemePreset sections_config
         Sections can be reordered and toggled via admin Theme Settings
    ═══════════════════════════════════════════════════════════════════ --}}
    @php
        $__themePreset = \App\Models\ThemePreset::getActive();
        $__sections = $__themePreset ? $__themePreset->getVisibleSections() : \App\Models\ThemePreset::DEFAULT_SECTIONS_CONFIG;
    @endphp
    @foreach($__sections as $__section)
        @include('templates.oke.sections.' . str_replace('_', '-', $__section['id']))
    @endforeach
    {{-- ════════════════════════════════════════════════════
         FOOTER — Clean, minimal
    ════════════════════════════════════════════════════ --}}
    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="h-9 w-9 object-contain rounded-lg">
                        @else
                            <div class="w-9 h-9 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"/></svg>
                            </div>
                        @endif
                        <span class="text-lg font-bold text-white">{{ $siteName }}</span>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed mb-4">{{ $siteTagline }}</p>
                    @if($socialFacebook || $socialInstagram || $socialTwitter || $socialYoutube)
                    <div class="flex items-center gap-2.5">
                        @if($socialFacebook)<a href="{{ $socialFacebook }}" target="_blank" class="w-8 h-8 bg-gray-800 hover:bg-teal-600 rounded-full flex items-center justify-center transition"><svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>@endif
                        @if($socialInstagram)<a href="{{ $socialInstagram }}" target="_blank" class="w-8 h-8 bg-gray-800 hover:bg-pink-600 rounded-full flex items-center justify-center transition"><svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>@endif
                        @if($socialTwitter)<a href="{{ $socialTwitter }}" target="_blank" class="w-8 h-8 bg-gray-800 hover:bg-gray-600 rounded-full flex items-center justify-center transition"><svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>@endif
                        @if($socialYoutube)<a href="{{ $socialYoutube }}" target="_blank" class="w-8 h-8 bg-gray-800 hover:bg-red-600 rounded-full flex items-center justify-center transition"><svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>@endif
                    </div>
                    @endif
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">{{ __('welcome.footer.link_cepat') }}</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Register</a></li>
                        <li><a href="{{ route('proceedings') }}" class="hover:text-white transition">{{ __('welcome.nav.publikasi') }}</a></li>
                        <li><a href="{{ route('archive') }}" class="hover:text-white transition">{{ __('welcome.nav.arsip') }}</a></li>
                        @foreach($footerMenus as $fMenu)
                        <li><a href="{{ $fMenu->url ?: '#' }}" target="{{ $fMenu->target }}" class="hover:text-white transition">{{ $fMenu->title }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-wider mb-4">{{ __('welcome.footer.kontak') }}</h4>
                    <ul class="space-y-2.5 text-sm">
                        @if($contactAddress)<li class="flex items-start gap-2"><svg class="w-4 h-4 shrink-0 mt-0.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg><span>{{ $contactAddress }}</span></li>@endif
                        @if($contactPhone)<li class="flex items-center gap-2"><svg class="w-4 h-4 shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg><span>{{ $contactPhone }}</span></li>@endif
                        @if($contactEmail)<li class="flex items-center gap-2"><svg class="w-4 h-4 shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg><span>{{ $contactEmail }}</span></li>@endif
                    </ul>
                </div>
            </div>
        </div>

        {{-- Supporters --}}
        @if($supporters->count())
        <div class="border-t border-gray-800">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
                @php $supporterGroups = $supporters->groupBy('type'); @endphp
                @foreach($supporterGroups as $type => $group)
                <div class="@if(!$loop->last) mb-8 @endif">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest text-center mb-5">{{ \App\Models\Supporter::TYPE_LABELS[$type] ?? ucfirst($type) }}</h4>
                    <div class="flex flex-wrap items-center justify-center gap-6">
                        @foreach($group as $supporter)
                        @if($supporter->logo)
                            @if($supporter->url)
                            <a href="{{ $supporter->url }}" target="_blank" class="opacity-60 hover:opacity-100 transition" title="{{ $supporter->name }}">
                                <img src="{{ asset('storage/' . $supporter->logo) }}" alt="{{ $supporter->name }}" class="h-12 max-w-[140px] object-contain">
                            </a>
                            @else
                            <img src="{{ asset('storage/' . $supporter->logo) }}" alt="{{ $supporter->name }}" class="h-12 max-w-[140px] object-contain opacity-60" title="{{ $supporter->name }}">
                            @endif
                        @else
                            @if($supporter->url)
                            <a href="{{ $supporter->url }}" target="_blank" class="text-sm text-gray-500 hover:text-white transition px-4 py-2 bg-gray-800 rounded-lg">{{ $supporter->name }}</a>
                            @else
                            <span class="text-sm text-gray-500 px-4 py-2 bg-gray-800 rounded-lg">{{ $supporter->name }}</span>
                            @endif
                        @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Copyright --}}
        <div class="border-t border-gray-800">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-5 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-500">
                <p>{{ $footerText ?: '© ' . date('Y') . ' ' . $siteName . '. ' . __('welcome.footer.all_rights') }}</p>
                @if($poweredBy)<p class="mt-1 sm:mt-0">{{ $poweredBy }}</p>@endif
            </div>
        </div>
    </footer>

</body>
</html>
