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
        $siteName = \App\Models\Setting::getValue('site_name', 'Prosiding LPKD-APJI');
        $siteTagline = \App\Models\Setting::getValue('site_tagline', 'Lokakarya Pendidikan Kejuruan Daerah — Asosiasi Pendidikan Kejuruan Indonesia');
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
        $selectedPapersInfo = \App\Models\Setting::getValue('selected_papers_info', 'Makalah terpilih akan diterbitkan pada jurnal-jurnal terindeks SINTA dan Google Scholar.');
        $poweredBy = \App\Models\Setting::getValue('powered_by', 'Powered by Laravel');
    @endphp
    <title>{{ $siteName }}</title>
    @if($siteDescription)
    <meta name="description" content="{{ $siteDescription }}">
    @endif
    @if($siteKeywords)
    <meta name="keywords" content="{{ $siteKeywords }}">
    @endif
    @if($googleAnalytics)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalytics }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $googleAnalytics }}');</script>
    @endif
    @php $siteFavicon = \App\Models\Setting::getValue('site_favicon'); @endphp
    @if($siteFavicon)
    <link rel="icon" type="image/png" href="{{ asset('storage/' . $siteFavicon) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    @include('templates.default-blue.partials.theme-config')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">

    {{-- ═══════════════════════════════════════════════════════════════════
         NAVBAR — Fixed, transparent on hero, solid on scroll
    ═══════════════════════════════════════════════════════════════════ --}}
    <nav x-data="{ scrolled: false, mobileOpen: false }"
         @scroll.window="scrolled = (window.scrollY > 60)"
         :class="scrolled ? 'bg-white shadow-md' : 'bg-white/90 backdrop-blur'"
         class="fixed top-0 w-full z-50 transition-all duration-300">
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
                    <div>
                        @include('partials.navbar-brand')
                    </div>
                </div>

                {{-- Desktop nav --}}
                <div class="hidden md:flex items-center space-x-1">
                    @if($activeConference)
                    <a href="#conference" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition font-medium">{{ __('welcome.nav.tentang') }}</a>
                    <a href="#dates" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition font-medium">{{ __('welcome.nav.tanggal_penting') }}</a>
                    @if($activeConference->keynoteSpeakers->count())
                    <a href="#speakers" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition font-medium">{{ __('welcome.nav.speaker') }}</a>
                    @endif
                    @if($activeConference->journalPublications->where('is_active', true)->count())
                    <a href="#journals" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition font-medium">{{ __('welcome.nav.jurnal') }}</a>
                    @endif
                    @endif
                    @if($latestNews->count())
                    <a href="#news" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition font-medium">{{ __('welcome.nav.berita') }}</a>
                    @endif
                    <a href="{{ route('proceedings') }}" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition font-medium">{{ __('welcome.nav.publikasi') }}</a>
                    <a href="{{ route('archive') }}" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition font-medium">{{ __('welcome.nav.arsip') }}</a>

                    {{-- Dynamic header menus --}}
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
                <button @click="mobileOpen = !mobileOpen" class="md:hidden flex items-center text-gray-600 hover:text-blue-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile menu --}}
            <div x-show="mobileOpen" x-cloak x-transition class="md:hidden pb-4 border-t space-y-1 pt-2">
                @if($activeConference)
                <a href="#conference" @click="mobileOpen=false" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg">{{ __('welcome.nav.tentang') }}</a>
                <a href="#dates" @click="mobileOpen=false" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg">{{ __('welcome.nav.tanggal_penting') }}</a>
                @if($activeConference->journalPublications->where('is_active', true)->count())
                <a href="#journals" @click="mobileOpen=false" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg">{{ __('welcome.nav.jurnal') }}</a>
                @endif
                @endif
                @if($latestNews->count())
                <a href="#news" @click="mobileOpen=false" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg">{{ __('welcome.nav.berita') }}</a>
                @endif
                <a href="{{ route('proceedings') }}" @click="mobileOpen=false" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg">{{ __('welcome.nav.publikasi') }}</a>
                <a href="{{ route('archive') }}" @click="mobileOpen=false" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg">{{ __('welcome.nav.arsip') }}</a>

                {{-- Dynamic mobile menus --}}
                @if($headerMenus->count())
                <div class="border-t border-gray-100 pt-1 mt-1">
                    @include(\App\Helpers\Template::view('partials.menu-mobile'), ['items' => $headerMenus, 'depth' => 0])
                </div>
                @endif

                <div class="flex gap-2 pt-2 px-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm bg-blue-600 text-white px-4 py-2 rounded-lg font-medium w-full text-center">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium flex-1 text-center">Login</a>
                        <a href="{{ route('register') }}" class="text-sm bg-blue-600 text-white px-4 py-2 rounded-lg font-medium flex-1 text-center">Register</a>
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
        @include('templates.default-blue.sections.' . str_replace('_', '-', $__section['id']))
    @endforeach
    {{-- ═══════════════════════════════════════════════════════════════════
         FOOTER — Full with contact, links, info
    ═══════════════════════════════════════════════════════════════════ --}}
    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                {{-- Brand --}}
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="h-10 w-10 object-contain rounded">
                        @else
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                        @endif
                        <span class="text-xl font-bold text-white">{{ $siteName }}</span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-md mb-5">{{ $siteTagline }}</p>
                    @if($socialFacebook || $socialInstagram || $socialTwitter || $socialYoutube)
                    <div class="flex items-center gap-3">
                        @if($socialFacebook)
                        <a href="{{ $socialFacebook }}" target="_blank" rel="noopener" class="w-9 h-9 bg-gray-800 hover:bg-blue-600 rounded-full flex items-center justify-center transition-all duration-300 hover:-translate-y-0.5">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        @endif
                        @if($socialInstagram)
                        <a href="{{ $socialInstagram }}" target="_blank" rel="noopener" class="w-9 h-9 bg-gray-800 hover:bg-pink-600 rounded-full flex items-center justify-center transition-all duration-300 hover:-translate-y-0.5">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        @endif
                        @if($socialTwitter)
                        <a href="{{ $socialTwitter }}" target="_blank" rel="noopener" class="w-9 h-9 bg-gray-800 hover:bg-gray-600 rounded-full flex items-center justify-center transition-all duration-300 hover:-translate-y-0.5">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        @endif
                        @if($socialYoutube)
                        <a href="{{ $socialYoutube }}" target="_blank" rel="noopener" class="w-9 h-9 bg-gray-800 hover:bg-red-600 rounded-full flex items-center justify-center transition-all duration-300 hover:-translate-y-0.5">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Quick Links + Dynamic Footer Menus --}}
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">{{ __('welcome.footer.link_cepat') }}</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition flex items-center gap-2"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition flex items-center gap-2"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>Register</a></li>
                        @foreach($footerMenus as $fMenu)
                        <li>
                            <a href="{{ $fMenu->url ?: '#' }}" target="{{ $fMenu->target }}" class="hover:text-white transition flex items-center gap-2">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
                                {{ $fMenu->title }}
                            </a>
                            @if($fMenu->allActiveChildren && $fMenu->allActiveChildren->count())
                            <ul class="ml-5 mt-1 space-y-1.5">
                                @foreach($fMenu->allActiveChildren->sortBy('sort_order') as $fChild)
                                <li>
                                    <a href="{{ $fChild->url ?: '#' }}" target="{{ $fChild->target }}" class="hover:text-white transition text-xs">{{ $fChild->title }}</a>
                                    @if($fChild->allActiveChildren && $fChild->allActiveChildren->count())
                                    <ul class="ml-4 mt-1 space-y-1">
                                        @foreach($fChild->allActiveChildren->sortBy('sort_order') as $fGrandchild)
                                        <li><a href="{{ $fGrandchild->url ?: '#' }}" target="{{ $fGrandchild->target }}" class="hover:text-white transition text-xs">{{ $fGrandchild->title }}</a></li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">{{ __('welcome.footer.kontak') }}</h4>
                    <ul class="space-y-3 text-sm">
                        @if($contactAddress)
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span>{{ $contactAddress }}</span>
                        </li>
                        @endif
                        @if($contactPhone)
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>{{ $contactPhone }}</span>
                        </li>
                        @endif
                        @if($contactEmail)
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>{{ $contactEmail }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        {{-- Supporter / Sponsor / Partner Section --}}
        @if($supporters->count())
        <div class="bg-gray-800/50 border-t border-b border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                @php
                    $supporterGroups = $supporters->groupBy('type');
                @endphp
                @foreach($supporterGroups as $type => $group)
                <div class="@if(!$loop->last) mb-10 @endif">
                    <div class="flex items-center justify-center gap-3 mb-6">
                        <div class="h-px bg-gradient-to-r from-transparent via-gray-600 to-transparent flex-1 max-w-[80px]"></div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">{{ \App\Models\Supporter::TYPE_LABELS[$type] ?? ucfirst($type) }}</h4>
                        <div class="h-px bg-gradient-to-r from-transparent via-gray-600 to-transparent flex-1 max-w-[80px]"></div>
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-8">
                        @foreach($group as $supporter)
                        @if($supporter->logo)
                            @if($supporter->url)
                            <a href="{{ $supporter->url }}" target="_blank" rel="noopener" class="group relative bg-gray-800/80 hover:bg-gray-700/80 rounded-xl p-4 transition-all duration-300 hover:shadow-lg hover:shadow-black/20 hover:-translate-y-0.5" title="{{ $supporter->name }}">
                                <img src="{{ asset('storage/' . $supporter->logo) }}" alt="{{ $supporter->name }}" class="h-14 max-w-[160px] object-contain opacity-70 group-hover:opacity-100 transition-opacity duration-300">
                            </a>
                            @else
                            <div class="group relative bg-gray-800/80 rounded-xl p-4 transition-all duration-300 hover:shadow-lg hover:shadow-black/20 hover:-translate-y-0.5" title="{{ $supporter->name }}">
                                <img src="{{ asset('storage/' . $supporter->logo) }}" alt="{{ $supporter->name }}" class="h-14 max-w-[160px] object-contain opacity-70 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            @endif
                        @else
                            @if($supporter->url)
                            <a href="{{ $supporter->url }}" target="_blank" rel="noopener" class="text-sm text-gray-400 hover:text-white transition-all duration-300 px-5 py-2.5 bg-gray-800/80 hover:bg-gray-700/80 rounded-xl font-medium hover:-translate-y-0.5">{{ $supporter->name }}</a>
                            @else
                            <span class="text-sm text-gray-400 px-5 py-2.5 bg-gray-800/80 rounded-xl font-medium">{{ $supporter->name }}</span>
                            @endif
                        @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Copyright Bar --}}
        <div class="bg-gray-900 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row justify-between items-center text-sm">
                <p>{{ $footerText ?: '© ' . date('Y') . ' ' . $siteName . '. ' . __('welcome.footer.all_rights') }}</p>
                @if($poweredBy)
                <p class="text-gray-600 mt-1 sm:mt-0">{{ $poweredBy }}</p>
                @endif
            </div>
        </div>
    </footer>

</body>
</html>
