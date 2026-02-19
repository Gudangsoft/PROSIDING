<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $conference->name }} - {{ $siteName }}</title>
    <meta name="description" content="{{ $conference->theme ?? $conference->name }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out forwards',
                        'slide-up': 'slideUp 0.5s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
                    }
                }
            }
        }
    </script>
    @include('templates.default.partials.theme-config')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&family=playfair-display:700,800,900" rel="stylesheet" />
    <style>
        [x-cloak]{display:none!important}
        body{font-family:'Inter',sans-serif;}
        html{scroll-behavior:smooth;}
        .font-display{font-family:'Playfair Display',serif;}
        .glass{background:rgba(255,255,255,0.1);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);}
        .pattern-dots{background-image:radial-gradient(rgba(255,255,255,0.1) 1px,transparent 1px);background-size:20px 20px;}
        .tab-active{color:#1d4ed8;border-bottom:3px solid #1d4ed8;background:linear-gradient(to top,rgba(59,130,246,0.05),transparent);}
        .card-hover{transition:all .3s cubic-bezier(.4,0,.2,1);}
        .card-hover:hover{transform:translateY(-4px);box-shadow:0 20px 40px rgba(0,0,0,0.08);}
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" x-data="{ mobileOpen: false, activeTab: 'overview' }">

    {{-- ═══════════════════════════════════════════════════════════════
         NAVBAR
         ═══════════════════════════════════════════════════════════════ --}}
    <nav class="bg-white/95 backdrop-blur border-b border-gray-200 sticky top-0 z-50 shadow-sm">
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
                    <a href="{{ route('proceedings') }}" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition font-medium">Publikasi</a>
                    <a href="{{ route('archive') }}" class="text-sm text-blue-700 bg-blue-50 px-3 py-2 rounded-lg font-medium">Arsip</a>
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
                <a href="{{ route('proceedings') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg">Publikasi</a>
                <a href="{{ route('archive') }}" class="block px-3 py-2 text-sm text-blue-700 bg-blue-50 rounded-lg font-medium">Arsip</a>
            </div>
        </div>
    </nav>

    {{-- ═══════════════════════════════════════════════════════════════
         HERO SECTION — Full-width immersive header
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden min-h-[420px] md:min-h-[480px] flex items-end">
        {{-- Background layers --}}
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-950"></div>
        @if($conference->cover_image)
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $conference->cover_image) }}" class="w-full h-full object-cover opacity-20" alt="">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/70 to-transparent"></div>
        @endif
        <div class="absolute inset-0 pattern-dots"></div>

        {{-- Floating decorative shapes --}}
        <div class="absolute top-20 right-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-10 left-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl animate-float" style="animation-delay:-3s"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 pt-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-blue-300/80 mb-8 animate-fade-in">
                <a href="/" class="hover:text-white transition">Beranda</a>
                <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('archive') }}" class="hover:text-white transition">Arsip</a>
                <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white/90 font-medium">Detail</span>
            </nav>

            <div class="flex flex-col lg:flex-row gap-8 items-end lg:items-end">
                {{-- Cover Image --}}
                @if($conference->cover_image)
                <div class="flex-shrink-0 animate-slide-up" style="animation-delay:0.1s">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition"></div>
                        <img src="{{ asset('storage/' . $conference->cover_image) }}" alt="{{ $conference->name }}" class="relative w-44 h-[230px] object-cover rounded-2xl shadow-2xl border border-white/10">
                    </div>
                </div>
                @endif

                {{-- Title & Info --}}
                <div class="flex-1 min-w-0 text-white animate-slide-up" style="animation-delay:0.2s">
                    @if($conference->acronym)
                    <div class="inline-flex items-center gap-2 glass rounded-full px-4 py-1.5 text-xs font-bold tracking-wider uppercase mb-4 border border-white/10">
                        <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
                        {{ $conference->acronym }} {{ $conference->start_date?->format('Y') }}
                    </div>
                    @endif

                    <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight">{{ $conference->name }}</h1>

                    @if($conference->theme)
                    <p class="text-blue-200/80 mt-3 text-base md:text-lg italic max-w-2xl">"{{ $conference->theme }}"</p>
                    @endif

                    <div class="flex flex-wrap gap-x-6 gap-y-2 mt-5 text-sm text-blue-200/90">
                        @if($conference->start_date)
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            {{ $conference->date_range }}
                        </div>
                        @endif
                        @if($conference->venue_type)
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ \App\Models\Conference::VENUE_TYPE_ICONS[$conference->venue_type] ?? '' }}"/></svg>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                @if($conference->venue_type === 'offline') bg-green-400/20 text-green-200
                                @elseif($conference->venue_type === 'online') bg-blue-400/20 text-blue-200
                                @else bg-purple-400/20 text-purple-200 @endif">
                                {{ $conference->venue_type_label }}
                            </span>
                        </div>
                        @endif
                        @if(($conference->venue_type === 'offline' || $conference->venue_type === 'hybrid') && ($conference->venue || $conference->city))
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            {{ $conference->venue ?? '' }}{{ $conference->venue && $conference->city ? ', ' : '' }}{{ $conference->city ?? '' }}
                        </div>
                        @endif
                        @if(($conference->venue_type === 'online' || $conference->venue_type === 'hybrid') && $conference->online_url)
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            </div>
                            <a href="{{ $conference->online_url }}" target="_blank" class="text-blue-300 hover:text-white underline underline-offset-2 transition">Link Meeting Online</a>
                        </div>
                        @endif
                        @if($conference->organizer)
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            {{ $conference->organizer }}
                        </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap gap-3 mt-8">
                        @if($completedPapersCount > 0)
                        <a href="{{ route('proceedings.show', $conference) }}" class="inline-flex items-center gap-2 bg-white text-blue-700 px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-50 transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Lihat {{ $completedPapersCount }} Paper
                        </a>
                        @endif
                        @if($conference->brochure)
                        <a href="{{ asset('storage/' . $conference->brochure) }}" target="_blank" class="inline-flex items-center gap-2 glass border border-white/20 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-white/20 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Brosur
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         STATS BAR — Overlapping card
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-7 relative z-10 animate-slide-up" style="animation-delay:0.3s">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-2">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 divide-x divide-gray-100">
                @if($conference->topics->count())
                <div class="text-center py-4 px-3">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-50 mb-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-800">{{ $conference->topics->count() }}</p>
                    <p class="text-xs text-gray-500 font-medium">Topik</p>
                </div>
                @endif
                @if($conference->keynoteSpeakers->count())
                <div class="text-center py-4 px-3">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-purple-50 mb-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-800">{{ $conference->keynoteSpeakers->count() }}</p>
                    <p class="text-xs text-gray-500 font-medium">Speaker</p>
                </div>
                @endif
                @if($conference->committees->count())
                <div class="text-center py-4 px-3">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-amber-50 mb-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-800">{{ $conference->committees->count() }}</p>
                    <p class="text-xs text-gray-500 font-medium">Panitia</p>
                </div>
                @endif
                @if($completedPapersCount)
                <div class="text-center py-4 px-3">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-green-50 mb-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-800">{{ $completedPapersCount }}</p>
                    <p class="text-xs text-gray-500 font-medium">Paper</p>
                </div>
                @endif
                @if($conference->importantDates->count())
                <div class="text-center py-4 px-3 hidden lg:block">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-rose-50 mb-2">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-800">{{ $conference->importantDates->count() }}</p>
                    <p class="text-xs text-gray-500 font-medium">Tanggal Penting</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         TAB NAVIGATION
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="sticky top-16 z-30 bg-white/95 backdrop-blur border-b border-gray-200 shadow-sm mt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex overflow-x-auto no-scrollbar -mb-px" style="-ms-overflow-style:none;scrollbar-width:none;">
                <button @click="activeTab='overview'" :class="activeTab==='overview' && 'tab-active'" class="flex items-center gap-2 px-5 py-4 text-sm font-semibold text-gray-500 hover:text-blue-700 border-b-3 border-transparent transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Informasi
                </button>
                @if($conference->keynoteSpeakers->count())
                <button @click="activeTab='speakers'" :class="activeTab==='speakers' && 'tab-active'" class="flex items-center gap-2 px-5 py-4 text-sm font-semibold text-gray-500 hover:text-blue-700 border-b-3 border-transparent transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                    Speaker
                </button>
                @endif
                @if($conference->committees->count())
                <button @click="activeTab='committee'" :class="activeTab==='committee' && 'tab-active'" class="flex items-center gap-2 px-5 py-4 text-sm font-semibold text-gray-500 hover:text-blue-700 border-b-3 border-transparent transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Panitia
                </button>
                @endif
                @if($conference->importantDates->count())
                <button @click="activeTab='dates'" :class="activeTab==='dates' && 'tab-active'" class="flex items-center gap-2 px-5 py-4 text-sm font-semibold text-gray-500 hover:text-blue-700 border-b-3 border-transparent transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Jadwal
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         TAB CONTENT
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-1">

        {{-- ──────────────────────── TAB: Overview ──────────────────────── --}}
        <div x-show="activeTab==='overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Description --}}
                    @if($conference->description)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h2 class="text-white font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Tentang Kegiatan
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $conference->description }}</div>
                        </div>
                    </div>
                    @endif

                    {{-- Topics --}}
                    @if($conference->topics->count())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover">
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                            <h2 class="text-white font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                Topik Kegiatan
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid sm:grid-cols-2 gap-3">
                                @foreach($conference->topics as $i => $topic)
                                <div class="group flex items-start gap-4 p-4 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all">
                                    <div class="flex-shrink-0 w-11 h-11 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white text-sm font-extrabold shadow-md">
                                        {{ $topic->code ?? ($i + 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-800 group-hover:text-indigo-700 transition">{{ $topic->name }}</p>
                                        @if($topic->description)
                                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $topic->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Quick Info Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-5 py-4">
                            <h3 class="text-white font-bold text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Info Kegiatan
                            </h3>
                        </div>
                        <div class="p-5 space-y-4">
                            @if($conference->start_date)
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Tanggal</p>
                                    <p class="text-sm text-gray-800 font-semibold">{{ $conference->start_date->translatedFormat('d F Y') }}@if($conference->end_date && $conference->end_date->ne($conference->start_date)) — {{ $conference->end_date->translatedFormat('d F Y') }}@endif</p>
                                    @if($conference->formatted_time)
                                    <p class="text-xs text-gray-500 mt-0.5">🕐 {{ $conference->formatted_time }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if($conference->venue_type)
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ \App\Models\Conference::VENUE_TYPE_COLORS[$conference->venue_type] ?? 'bg-gray-50 text-gray-600' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ \App\Models\Conference::VENUE_TYPE_ICONS[$conference->venue_type] ?? '' }}"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Tipe Kegiatan</p>
                                    <p class="text-sm text-gray-800 font-semibold">{{ $conference->venue_type_label }}</p>
                                </div>
                            </div>
                            @endif
                            @if(($conference->venue_type === 'offline' || $conference->venue_type === 'hybrid') && $conference->venue)
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Tempat</p>
                                    <p class="text-sm text-gray-800 font-semibold">{{ $conference->venue }}</p>
                                </div>
                            </div>
                            @endif
                            @if(($conference->venue_type === 'offline' || $conference->venue_type === 'hybrid') && $conference->city)
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Kota</p>
                                    <p class="text-sm text-gray-800 font-semibold">{{ $conference->city }}</p>
                                </div>
                            </div>
                            @endif
                            @if(($conference->venue_type === 'online' || $conference->venue_type === 'hybrid') && $conference->online_url)
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Link Online</p>
                                    <a href="{{ $conference->online_url }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 font-semibold underline underline-offset-2">Buka Link</a>
                                </div>
                            </div>
                            @endif
                            @if($conference->organizer)
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Penyelenggara</p>
                                    <p class="text-sm text-gray-800 font-semibold">{{ $conference->organizer }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Registration Packages --}}
                    @if($conference->registrationPackages->count())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 px-5 py-4">
                            <h3 class="text-white font-bold text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Paket Registrasi
                            </h3>
                        </div>
                        <div class="p-4 space-y-3">
                            @foreach($conference->registrationPackages as $pkg)
                            <div class="group bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100 p-4 hover:border-emerald-200 hover:shadow-md transition-all">
                                <div class="flex justify-between items-start mb-1">
                                    <p class="text-sm font-bold text-gray-800">{{ $pkg->name }}</p>
                                </div>
                                <p class="text-lg font-extrabold text-emerald-600">{{ $pkg->currency ?? 'Rp' }} {{ number_format($pkg->price, 0, ',', '.') }}</p>
                                @if($pkg->description)
                                <p class="text-xs text-gray-400 mt-1.5">{{ $pkg->description }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Paper CTA --}}
                    @if($completedPapersCount > 0)
                    <div class="relative overflow-hidden rounded-2xl shadow-lg">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800"></div>
                        <div class="absolute inset-0 pattern-dots opacity-30"></div>
                        <div class="relative p-6 text-center text-white">
                            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-white/15 flex items-center justify-center">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="text-3xl font-extrabold">{{ $completedPapersCount }}</p>
                            <p class="text-blue-200 text-sm mb-4">Paper Dipublikasikan</p>
                            <a href="{{ route('proceedings.show', $conference) }}" class="inline-flex items-center gap-2 bg-white text-blue-700 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Lihat Paper
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ──────────────────────── TAB: Speakers ──────────────────────── --}}
        @if($conference->keynoteSpeakers->count())
        <div x-show="activeTab==='speakers'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-gray-800">Keynote Speaker & Narasumber</h2>
                <p class="text-gray-500 text-sm mt-1">Para pembicara kunci dalam kegiatan ini</p>
            </div>

            @php
                $speakersByType = $conference->keynoteSpeakers->groupBy('type');
            @endphp

            @foreach($speakersByType as $type => $speakers)
            <div class="mb-10 last:mb-0">
                @php
                    $typeColors = [
                        'opening_speech' => ['from-purple-500', 'to-purple-700', 'bg-purple-50', 'text-purple-700'],
                        'keynote_speaker' => ['from-blue-500', 'to-blue-700', 'bg-blue-50', 'text-blue-700'],
                        'narasumber' => ['from-green-500', 'to-green-700', 'bg-green-50', 'text-green-700'],
                        'moderator_host' => ['from-orange-500', 'to-orange-700', 'bg-orange-50', 'text-orange-700'],
                    ];
                    $colors = $typeColors[$type] ?? ['from-gray-500', 'to-gray-700', 'bg-gray-50', 'text-gray-700'];
                    $typeLabel = \App\Models\KeynoteSpeaker::TYPE_LABELS[$type] ?? ucfirst($type);
                @endphp
                <div class="inline-flex items-center gap-2 {{ $colors[2] }} {{ $colors[3] }} px-4 py-1.5 rounded-full text-sm font-bold mb-5">
                    {{ $typeLabel }}
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($speakers as $speaker)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover group">
                        <div class="bg-gradient-to-r {{ $colors[0] }} {{ $colors[1] }} p-5 text-white text-center">
                            @if($speaker->photo)
                            <img src="{{ asset('storage/' . $speaker->photo) }}" alt="{{ $speaker->name }}" class="w-20 h-20 rounded-full object-cover mx-auto border-4 border-white/30 shadow-lg group-hover:scale-110 transition-transform">
                            @else
                            <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mx-auto border-4 border-white/20">
                                <svg class="w-10 h-10 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            @endif
                        </div>
                        <div class="p-5 text-center">
                            <h4 class="font-bold text-gray-800 text-base">{{ $speaker->title ? $speaker->title . ' ' : '' }}{{ $speaker->name }}</h4>
                            @if($speaker->institution)
                            <p class="text-xs text-gray-500 mt-1 flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                {{ $speaker->institution }}
                            </p>
                            @endif
                            @if($speaker->topic)
                            <div class="mt-3 bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Topik</p>
                                <p class="text-sm text-gray-700 font-medium italic">"{{ $speaker->topic }}"</p>
                            </div>
                            @endif
                            @if($speaker->bio)
                            <div x-data="{ showBio: false }" class="mt-3">
                                <button @click="showBio=!showBio" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    <span x-text="showBio ? 'Tutup Bio' : 'Lihat Bio'"></span>
                                </button>
                                <div x-show="showBio" x-cloak x-transition class="mt-2 text-xs text-gray-500 text-left bg-gray-50 rounded-lg p-3 leading-relaxed">
                                    {{ $speaker->bio }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- ──────────────────────── TAB: Committee ──────────────────────── --}}
        @if($conference->committees->count())
        <div x-show="activeTab==='committee'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-gray-800">Susunan Panitia</h2>
                <p class="text-gray-500 text-sm mt-1">Tim yang menyelenggarakan kegiatan ini</p>
            </div>

            @php
                $committeesByType = $conference->committees->groupBy('type');
                $typeColors = [
                    'steering' => 'from-purple-600 to-purple-700',
                    'organizing' => 'from-blue-600 to-blue-700',
                    'scientific' => 'from-emerald-600 to-emerald-700',
                    'advisory' => 'from-amber-600 to-amber-700',
                    'reviewer_committee' => 'from-rose-600 to-rose-700',
                ];
            @endphp

            <div class="space-y-8">
                @foreach($committeesByType as $type => $members)
                @php
                    $gradient = $typeColors[$type] ?? 'from-gray-600 to-gray-700';
                    $typeLabel = \App\Models\Committee::TYPE_LABELS[$type] ?? ucfirst($type);
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r {{ $gradient }} px-6 py-4 flex items-center justify-between">
                        <h3 class="text-white font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $typeLabel }}
                        </h3>
                        <span class="text-white/70 text-xs font-medium bg-white/10 px-3 py-1 rounded-full">{{ $members->count() }} orang</span>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($members as $member)
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-blue-50/30 transition group">
                            @if($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-11 h-11 rounded-full object-cover border-2 border-gray-100 group-hover:border-blue-200 transition">
                            @else
                            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center flex-shrink-0 group-hover:from-blue-50 group-hover:to-blue-100 transition">
                                <span class="text-sm font-bold text-gray-400 group-hover:text-blue-500 transition">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800">{{ $member->title ? $member->title . ' ' : '' }}{{ $member->name }}</p>
                                <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                    @if($member->role)
                                    <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-medium">{{ $member->role }}</span>
                                    @endif
                                    @if($member->institution)
                                    <span class="text-xs text-gray-400">{{ $member->institution }}</span>
                                    @endif
                                </div>
                            </div>
                            @if($member->email)
                            <a href="mailto:{{ $member->email }}" class="text-gray-300 hover:text-blue-500 transition" title="{{ $member->email }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ──────────────────────── TAB: Important Dates ──────────────────────── --}}
        @if($conference->importantDates->count())
        <div x-show="activeTab==='dates'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-gray-800">Jadwal & Tanggal Penting</h2>
                <p class="text-gray-500 text-sm mt-1">Timeline kegiatan prosiding</p>
            </div>

            <div class="max-w-3xl mx-auto">
                {{-- Timeline --}}
                <div class="relative">
                    {{-- Vertical line --}}
                    <div class="absolute left-6 md:left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-500 via-indigo-500 to-purple-500 transform md:-translate-x-0.5"></div>

                    @foreach($conference->importantDates as $i => $date)
                    @php
                        $isLeft = $i % 2 === 0;
                        $dateColors = [
                            'submission_deadline' => ['bg-blue-500', 'border-blue-200', 'bg-blue-50'],
                            'review_deadline' => ['bg-amber-500', 'border-amber-200', 'bg-amber-50'],
                            'notification' => ['bg-green-500', 'border-green-200', 'bg-green-50'],
                            'camera_ready' => ['bg-purple-500', 'border-purple-200', 'bg-purple-50'],
                            'early_bird' => ['bg-pink-500', 'border-pink-200', 'bg-pink-50'],
                            'registration_deadline' => ['bg-orange-500', 'border-orange-200', 'bg-orange-50'],
                            'conference_date' => ['bg-indigo-500', 'border-indigo-200', 'bg-indigo-50'],
                        ];
                        $dc = $dateColors[$date->type] ?? ['bg-gray-500', 'border-gray-200', 'bg-gray-50'];
                    @endphp
                    <div class="relative flex items-start mb-8 last:mb-0 {{ $isLeft ? 'md:flex-row' : 'md:flex-row-reverse' }}">
                        {{-- Timeline dot --}}
                        <div class="absolute left-6 md:left-1/2 w-4 h-4 rounded-full {{ $dc[0] }} border-4 border-white shadow-lg transform -translate-x-1/2 z-10 mt-5"></div>

                        {{-- Spacer --}}
                        <div class="hidden md:block w-1/2"></div>

                        {{-- Content card --}}
                        <div class="ml-14 md:ml-0 {{ $isLeft ? 'md:pr-10' : 'md:pl-10' }} w-full md:w-1/2">
                            <div class="bg-white rounded-xl shadow-sm border {{ $dc[1] }} p-5 card-hover">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-14 h-14 rounded-xl {{ $dc[2] }} flex flex-col items-center justify-center">
                                        <span class="text-lg font-extrabold text-gray-800 leading-none">{{ $date->date?->format('d') }}</span>
                                        <span class="text-xs text-gray-500 uppercase font-medium">{{ $date->date?->format('M') }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-gray-800">{{ $date->title }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $date->date?->translatedFormat('l, d F Y') }}</p>
                                        @if($date->description)
                                        <p class="text-xs text-gray-500 mt-2 leading-relaxed">{{ $date->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         BACK NAVIGATION
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
        <a href="{{ route('archive') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-blue-600 font-medium transition group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Arsip Kegiatan
        </a>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         FOOTER
         ═══════════════════════════════════════════════════════════════ --}}
    <footer class="bg-gray-900 text-gray-400 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
            <p>{{ $footerText ?? '© ' . date('Y') . ' ' . $siteName }}</p>
            @if($poweredBy)<p class="mt-1 text-gray-500 text-xs">{!! $poweredBy !!}</p>@endif
        </div>
    </footer>

</body>
</html>
