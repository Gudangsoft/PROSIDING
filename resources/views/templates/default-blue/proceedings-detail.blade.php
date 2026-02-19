<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $conference->name }} - {{ $siteName }}</title>
    <meta name="description" content="Daftar paper prosiding {{ $conference->name }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out forwards',
                        'slide-up': 'slideUp 0.5s ease-out forwards',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                    }
                }
            }
        }
    </script>
    @include('templates.default-blue.partials.theme-config')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&family=playfair-display:700,800,900" rel="stylesheet" />
    <style>
        [x-cloak]{display:none!important}
        body{font-family:'Inter',sans-serif;}
        html{scroll-behavior:smooth;}
        .font-display{font-family:'Playfair Display',serif;}
        .pattern-dots{background-image:radial-gradient(rgba(255,255,255,0.08) 1px,transparent 1px);background-size:20px 20px;}
        .line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
        .line-clamp-3{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}
        .paper-card{transition:all .3s cubic-bezier(.4,0,.2,1);}
        .paper-card:hover{transform:translateY(-2px);}
        .search-glow:focus-within{box-shadow:0 0 0 3px rgba(59,130,246,0.1);}
        ::-webkit-scrollbar{width:6px;height:6px;}
        ::-webkit-scrollbar-track{background:transparent;}
        ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:3px;}
        ::-webkit-scrollbar-thumb:hover{background:#94a3b8;}
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- ═══════════════════════════════════════════════════════════════
         NAVBAR
         ═══════════════════════════════════════════════════════════════ --}}
    <nav class="bg-white/95 backdrop-blur border-b border-gray-200 sticky top-0 z-50 shadow-sm" x-data="{ mobileOpen: false }">
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

    {{-- ═══════════════════════════════════════════════════════════════
         HERO — Immersive conference header
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden" style="z-index:1;isolation:isolate">
        {{-- Background --}}
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-950"></div>
        @if($conference->cover_image)
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $conference->cover_image) }}" class="w-full h-full object-cover opacity-15" alt="">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-slate-900/80"></div>
        @endif
        <div class="absolute inset-0 pattern-dots"></div>

        {{-- Decorative --}}
        <div class="absolute top-10 right-0 w-96 h-96 bg-blue-500/5 rounded-full" style="opacity:0.15"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-indigo-500/5 rounded-full" style="opacity:0.15"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-20">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-blue-300/70 mb-8 animate-fade-in">
                <a href="/" class="hover:text-white transition">Beranda</a>
                <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('proceedings') }}" class="hover:text-white transition">Publikasi</a>
                <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white/90 font-medium">{{ $conference->acronym ?? Str::limit($conference->name, 30) }}</span>
            </nav>

            <div class="flex flex-col lg:flex-row gap-8 items-start animate-slide-up" style="animation-delay:0.1s">
                {{-- Cover --}}
                @if($conference->cover_image)
                <div class="flex-shrink-0">
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $conference->cover_image) }}" alt="{{ $conference->name }}"
                             class="relative w-36 h-48 object-cover rounded-2xl border border-white/10" style="box-shadow:0 0 30px rgba(59,130,246,0.3),0 25px 50px rgba(0,0,0,0.5)">
                    </div>
                </div>
                @endif

                {{-- Info --}}
                <div class="flex-1 min-w-0 text-white">
                    @if($conference->acronym)
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-1.5 text-xs font-bold tracking-wider uppercase mb-4 border border-white/10">
                        <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        PROSIDING {{ $conference->acronym }} {{ $conference->start_date?->format('Y') }}
                    </div>
                    @endif

                    <h1 class="font-display text-3xl md:text-4xl font-black leading-tight tracking-tight">{{ $conference->name }}</h1>

                    @if($conference->theme)
                    <p class="text-blue-200/70 mt-3 text-base italic max-w-2xl">"{{ $conference->theme }}"</p>
                    @endif

                    <div class="flex flex-wrap gap-x-5 gap-y-2 mt-5 text-sm text-blue-200/80">
                        @if($conference->start_date)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $conference->date_range }}
                        </div>
                        @endif
                        @if($conference->venue_type)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ \App\Models\Conference::VENUE_TYPE_ICONS[$conference->venue_type] ?? '' }}"/></svg>
                            <span class="bg-white/10 px-2 py-0.5 rounded-full text-xs font-semibold">{{ $conference->venue_type_label }}</span>
                        </div>
                        @endif
                        @if($conference->organizer)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            {{ $conference->organizer }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         STATS + SEARCH — Overlapping card
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10 animate-slide-up" style="animation-delay:0.2s">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            {{-- Stats --}}
            <div class="grid grid-cols-3 divide-x divide-gray-100">
                <div class="flex items-center justify-center gap-3 py-6 px-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-gray-800 leading-none">{{ $totalPapers }}</p>
                        <p class="text-xs text-gray-400 font-medium mt-1">Total Paper</p>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-3 py-6 px-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-gray-800 leading-none">{{ $totalAuthors }}</p>
                        <p class="text-xs text-gray-400 font-medium mt-1">Penulis</p>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-3 py-6 px-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-gray-800 leading-none">{{ $topics->count() }}</p>
                        <p class="text-xs text-gray-400 font-medium mt-1">Topik</p>
                    </div>
                </div>
            </div>

            {{-- Search & Filter --}}
            <div class="border-t border-gray-100 bg-gray-50/40 px-6 py-5">
                <form method="GET" action="{{ route('proceedings.show', $conference) }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    <div class="flex-1 min-w-0">
                        <div class="relative search-glow rounded-xl transition">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 placeholder-gray-400 transition outline-none"
                                   placeholder="Cari judul, penulis, kata kunci...">
                        </div>
                    </div>
                    <div class="flex gap-3 items-center">
                        @if($topics->count())
                        <select name="topic" onchange="this.form.submit()" class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 min-w-[170px] outline-none cursor-pointer">
                            <option value="">Semua Topik</option>
                            @foreach($topics as $t)
                                <option value="{{ $t }}" {{ request('topic') === $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                        @endif
                        <select name="sort" onchange="this.form.submit()" class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 min-w-[120px] outline-none cursor-pointer">
                            <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Judul (A-Z)</option>
                        </select>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-sm hover:shadow-md whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Cari
                        </button>
                    </div>
                    @if(request('search') || request('topic'))
                    <a href="{{ route('proceedings.show', $conference) }}" class="text-sm text-red-500 hover:text-red-600 font-medium flex items-center gap-1 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reset
                    </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         PAPER LIST — Professional Publication Cards
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-1">
        @if($papers->count())

        {{-- Results header --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-gradient-to-b from-blue-600 to-indigo-600 rounded-full"></div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Daftar Paper</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $conference->name }}</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2 shadow-sm">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-sm text-gray-600">Menampilkan <strong class="text-gray-800">{{ $papers->firstItem() }}–{{ $papers->lastItem() }}</strong> dari <strong class="text-gray-800">{{ $papers->total() }}</strong> paper</span>
            </div>
        </div>

        {{-- Papers --}}
        <div class="space-y-5">
            @foreach($papers as $index => $paper)
            @php
                $fullPaper = $paper->files->whereIn('type', ['full_paper', 'revision'])->sortByDesc('created_at')->first();
                $authorDeliverables = $paper->deliverables->where('direction', 'author_upload');
            @endphp
            <div class="paper-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group hover:shadow-lg hover:border-gray-200 transition-all duration-300" x-data="{ showAbstract: false }">

                {{-- Top accent line --}}
                <div class="h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                {{-- Card Body --}}
                <div class="p-5 md:p-7">

                    {{-- Title + Year Badge --}}
                    <div class="flex items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg md:text-xl font-bold text-gray-800 leading-snug group-hover:text-blue-700 transition-colors duration-200">
                                @if($paper->article_link)
                                <a href="{{ $paper->article_link }}" target="_blank" class="hover:underline underline-offset-4 decoration-blue-300">{{ $paper->title }}</a>
                                @else
                                {{ $paper->title }}
                                @endif
                            </h3>
                        </div>
                        @if($paper->accepted_at)
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 text-white font-extrabold text-sm shadow-lg shadow-orange-200/50 ring-4 ring-orange-50">
                                {{ $paper->accepted_at->format('Y') }}
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Authors --}}
                    <div class="flex items-start gap-2.5 mt-4">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            @if($paper->authors_meta && count($paper->authors_meta))
                                @foreach(collect($paper->authors_meta)->pluck('name') as $i => $authorName)
                                    <span class="font-medium text-gray-700">{{ $authorName }}</span>@if(!$loop->last)<span class="text-gray-400"> ; </span>@endif
                                @endforeach
                            @else
                                <span class="font-medium text-gray-700">{{ $paper->user->name }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- Badges Row --}}
                    <div class="flex flex-wrap items-center gap-2 mt-4">
                        @if($paper->topic)
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full border border-emerald-200/70 shadow-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            {{ $paper->topic }}
                        </span>
                        @endif
                        @if($conference->organizer)
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full border border-blue-200/70 shadow-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            {{ $conference->organizer }}
                        </span>
                        @endif
                    </div>

                    {{-- Abstract Preview --}}
                    @if($paper->abstract)
                    <div class="mt-4">
                        <p class="text-sm text-gray-500 leading-relaxed" x-show="!showAbstract">
                            {{ Str::limit($paper->abstract, 250, '...') }}
                        </p>
                        <div x-show="showAbstract" x-cloak x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="relative bg-gradient-to-r from-slate-50 to-transparent rounded-xl p-4 border-l-4 border-blue-400">
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $paper->abstract }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Keywords as hashtags --}}
                    @if($paper->keywords)
                    <div class="mt-4 bg-gradient-to-r from-rose-50/80 via-purple-50/40 to-transparent rounded-xl px-4 py-2.5 border border-rose-100/50">
                        <p class="text-xs font-medium leading-relaxed">
                            @foreach(explode(',', $paper->keywords) as $kw)
                                <span class="text-rose-400">#</span><span class="text-rose-500/80">{{ trim($kw) }}</span>@if(!$loop->last)<span class="text-gray-300">; </span>@endif
                            @endforeach
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Bottom Action Bar --}}
                <div class="border-t border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 md:px-7 py-3.5 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-gray-400">
                        @if($paper->accepted_at)
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $paper->accepted_at->translatedFormat('d M Y') }}
                        </span>
                        @endif

                        @if($paper->article_link)
                        <a href="{{ $paper->article_link }}" target="_blank" class="inline-flex items-center gap-1.5 text-blue-500 hover:text-blue-700 font-semibold transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            Artikel
                        </a>
                        @endif

                        @if($fullPaper)
                        <a href="{{ asset('storage/' . $fullPaper->file_path) }}" download class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 font-bold px-3 py-1.5 rounded-lg border border-emerald-200/60 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download PDF
                        </a>
                        @endif

                        @if($authorDeliverables->count())
                            @foreach($authorDeliverables as $deliverable)
                            <a href="{{ asset('storage/' . $deliverable->file_path) }}" download class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 hover:text-purple-700 font-bold px-3 py-1.5 rounded-lg border border-purple-200/60 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                {{ \App\Models\Deliverable::TYPE_LABELS[$deliverable->type] ?? $deliverable->original_name }}
                            </a>
                            @endforeach
                        @endif

                        @if($conference->acronym)
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            {{ $conference->acronym }} {{ $conference->start_date?->format('Y') }}
                        </span>
                        @endif
                    </div>

                    {{-- Detail Button --}}
                    @if($paper->abstract)
                    <button @click="showAbstract = !showAbstract"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white pl-5 pr-4 py-2.5 rounded-full text-xs font-bold transition-all duration-200 shadow-md shadow-orange-200/50 hover:shadow-lg hover:shadow-orange-300/50 active:scale-95">
                        <span x-text="showAbstract ? 'Sembunyikan' : 'Lihat Detail'">Lihat Detail</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="showAbstract ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    @elseif($fullPaper || $paper->article_link)
                    <a href="{{ $paper->article_link ?? asset('storage/' . $fullPaper->file_path) }}" target="_blank"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white pl-5 pr-4 py-2.5 rounded-full text-xs font-bold transition-all duration-200 shadow-md shadow-orange-200/50 hover:shadow-lg hover:shadow-orange-300/50 active:scale-95">
                        Lihat Detail
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($papers->hasPages())
        <div class="mt-12 flex justify-center">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-3 py-2.5">
                {{ $papers->withQueryString()->links() }}
            </div>
        </div>
        @endif

        @else
        {{-- Empty state --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl flex items-center justify-center border border-gray-100">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Paper</h3>
            <p class="text-gray-400 max-w-md mx-auto">
                @if(request('search') || request('topic'))
                    Tidak ditemukan paper yang sesuai dengan pencarian Anda.
                    <a href="{{ route('proceedings.show', $conference) }}" class="text-blue-600 hover:underline font-medium ml-1">Reset filter</a>
                @else
                    Belum ada paper yang dipublikasikan dalam kegiatan ini.
                @endif
            </p>
        </div>
        @endif

        {{-- Back link --}}
        <div class="mt-10">
            <a href="{{ route('proceedings') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-blue-600 font-medium transition group bg-white px-5 py-3 rounded-xl border border-gray-100 shadow-sm hover:shadow hover:border-gray-200">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Semua Publikasi
            </a>
        </div>
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
