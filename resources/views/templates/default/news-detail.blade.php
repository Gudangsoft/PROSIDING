<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }} — {{ $siteName }}</title>
    <meta name="description" content="{{ $news->excerpt ?: Str::limit(strip_tags($news->content), 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $news->title }}">
    <meta property="og:description" content="{{ $news->excerpt ?: Str::limit(strip_tags($news->content), 160) }}">
    <meta property="og:url" content="{{ request()->url() }}">
    @if($news->cover_image)
    <meta property="og:image" content="{{ asset('storage/' . $news->cover_image) }}">
    @endif
    <meta property="article:published_time" content="{{ $news->published_at?->toIso8601String() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>
    @include('templates.default.partials.theme-config')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        /* Prose Styling */
        .prose-content { font-size: 1.0625rem; line-height: 1.85; color: #374151; }
        .prose-content p { margin-bottom: 1.5em; }
        .prose-content h2 { font-size: 1.5em; font-weight: 700; margin-top: 2em; margin-bottom: 0.75em; color: #111827; line-height: 1.3; }
        .prose-content h3 { font-size: 1.25em; font-weight: 600; margin-top: 1.75em; margin-bottom: 0.5em; color: #1f2937; }
        .prose-content h4 { font-size: 1.125em; font-weight: 600; margin-top: 1.5em; margin-bottom: 0.5em; color: #1f2937; }
        .prose-content ul, .prose-content ol { margin-bottom: 1.25em; padding-left: 1.75em; }
        .prose-content li { margin-bottom: 0.5em; line-height: 1.75; }
        .prose-content ul li { list-style-type: disc; }
        .prose-content ol li { list-style-type: decimal; }
        .prose-content img { border-radius: 0.75rem; margin: 1.5em 0; max-width: 100%; height: auto; }
        .prose-content a { color: var(--theme-link-color, #2563eb); text-decoration: underline; text-underline-offset: 2px; }
        .prose-content a:hover { color: var(--theme-link-hover-color, #1d4ed8); }
        .prose-content blockquote { border-left: 4px solid var(--theme-primary-color, #2563eb); padding: 1em 1.25em; margin: 1.5em 0; background: #eff6ff; border-radius: 0 0.5rem 0.5rem 0; font-style: italic; color: #4b5563; }
        .prose-content table { width: 100%; border-collapse: collapse; margin: 1.5em 0; font-size: 0.9375em; }
        .prose-content th, .prose-content td { border: 1px solid #e5e7eb; padding: 0.75em 1em; text-align: left; }
        .prose-content th { background: #f9fafb; font-weight: 600; color: #111827; }
        .prose-content pre { background: #1f2937; color: #e5e7eb; padding: 1.25em; border-radius: 0.75rem; overflow-x: auto; margin: 1.5em 0; font-size: 0.875em; }
        .prose-content code { background: #f3f4f6; padding: 0.2em 0.4em; border-radius: 0.25rem; font-size: 0.875em; }
        .prose-content pre code { background: transparent; padding: 0; }
        .prose-content hr { border: none; border-top: 2px solid #e5e7eb; margin: 2em 0; }
        .prose-content figure { margin: 1.5em 0; }
        .prose-content figcaption { text-align: center; font-size: 0.875em; color: #6b7280; margin-top: 0.5em; }
    </style>
</head>
<body class="bg-gray-50">
    {{-- ══════════ NAVBAR ══════════ --}}
    <nav class="bg-white border-b sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    @if($siteLogo)
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="h-9 w-9 object-contain rounded-lg">
                    @else
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    @endif
                    @include('partials.navbar-brand')
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="hidden sm:flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 transition font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Beranda
                    </a>
                    <a href="{{ url('/') }}#news" class="flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-semibold transition px-3 py-1.5 rounded-lg hover:bg-blue-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        Berita
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ══════════ BREADCRUMB ══════════ --}}
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center gap-2 text-sm text-gray-500 overflow-hidden">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition shrink-0">Beranda</a>
                <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ url('/') }}#news" class="hover:text-blue-600 transition shrink-0">Berita</a>
                <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-800 font-medium truncate">{{ Str::limit($news->title, 60) }}</span>
            </nav>
        </div>
    </div>

    {{-- ══════════ MAIN CONTENT ══════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- ────── ARTICLE (LEFT) ────── --}}
            <article class="flex-1 min-w-0">
                <div class="bg-white rounded-2xl border shadow-sm p-6 sm:p-8 lg:p-10">

                    {{-- Category Badge --}}
                    @if($news->category)
                    <div class="mb-5">
                        <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider
                            {{ $news->category === 'general' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $news->category === 'academic' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $news->category === 'event' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $news->category === 'announcement' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ !in_array($news->category, ['general','academic','event','announcement']) ? 'bg-gray-100 text-gray-700' : '' }}">
                            {{ \App\Models\News::CATEGORY_LABELS[$news->category] ?? $news->category }}
                        </span>
                    </div>
                    @endif

                    {{-- Title --}}
                    <h1 class="text-2xl sm:text-3xl lg:text-[2.25rem] font-extrabold text-gray-900 leading-tight mb-6">{{ $news->title }}</h1>

                    {{-- Author & Meta Row --}}
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-3 mb-8 pb-6 border-b border-gray-100">
                        @if($news->author)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($news->author->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $news->author->name }}</p>
                                <p class="text-xs text-gray-400">Penulis</p>
                            </div>
                        </div>
                        <div class="hidden sm:block w-px h-8 bg-gray-200"></div>
                        @endif
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $news->published_at?->translatedFormat('d F Y') }}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ number_format($news->views_count) }} views
                            </div>
                            @php $readTime = max(1, ceil(str_word_count(strip_tags($news->content)) / 200)); @endphp
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $readTime }} menit baca
                            </div>
                        </div>
                    </div>

                    {{-- Cover Image --}}
                    @if($news->cover_image)
                    <div class="mb-8 rounded-xl overflow-hidden">
                        <img src="{{ asset('storage/' . $news->cover_image) }}" alt="{{ $news->title }}" class="w-full h-auto object-cover">
                    </div>
                    @endif

                    {{-- Excerpt / Lead --}}
                    @if($news->excerpt)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-5 rounded-r-xl mb-8">
                        <p class="text-gray-700 font-medium leading-relaxed italic text-[0.9375rem]">"{{ $news->excerpt }}"</p>
                    </div>
                    @endif

                    {{-- Article Body --}}
                    <div class="prose-content">
                        {!! $news->content !!}
                    </div>

                </div>

                {{-- Share Bar (Mobile only — desktop uses sidebar) --}}
                <div class="mt-6 bg-white rounded-2xl border shadow-sm p-6 lg:hidden">
                    <p class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        Bagikan Artikel Ini
                    </p>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener"
                           class="flex items-center gap-2 px-4 py-2 bg-[#1877F2] hover:bg-[#166fe5] text-white rounded-lg text-sm font-medium transition shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}" target="_blank" rel="noopener"
                           class="flex items-center gap-2 px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-lg text-sm font-medium transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($news->title) }}" target="_blank" rel="noopener"
                           class="flex items-center gap-2 px-4 py-2 bg-[#0A66C2] hover:bg-[#004182] text-white rounded-lg text-sm font-medium transition shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            LinkedIn
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->url()) }}" target="_blank" rel="noopener"
                           class="flex items-center gap-2 px-4 py-2 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-lg text-sm font-medium transition shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>
                        <button onclick="copyLink(this)" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            <span>Salin Link</span>
                        </button>
                    </div>
                </div>

                {{-- Related News --}}
                @if($relatedNews->count())
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Berita Terkait</h3>
                        <a href="{{ url('/') }}#news" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                            Lihat Semua Berita
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($relatedNews as $related)
                        <a href="{{ route('news.detail', $related->slug) }}" class="group bg-white rounded-xl border shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                            @if($related->cover_image)
                            <div class="h-44 overflow-hidden">
                                <img src="{{ asset('storage/' . $related->cover_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                            @else
                            <div class="h-44 bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                                <svg class="w-14 h-14 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                            @endif
                            <div class="p-4">
                                <h4 class="text-sm font-bold text-gray-800 group-hover:text-blue-600 transition line-clamp-2 mb-2">{{ $related->title }}</h4>
                                <p class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $related->published_at?->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </article>

            {{-- ────── SIDEBAR (RIGHT) ────── --}}
            <aside class="w-full lg:w-80 xl:w-96 shrink-0 space-y-6">

                {{-- Share Card (Desktop only — sticky) --}}
                <div class="hidden lg:block bg-white rounded-xl border shadow-sm p-5 lg:sticky lg:top-24">
                    <p class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        Bagikan Artikel Ini
                    </p>
                    <div class="grid grid-cols-2 gap-2.5">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener"
                           class="flex items-center justify-center gap-2 px-3 py-2.5 bg-[#1877F2] hover:bg-[#166fe5] text-white rounded-lg text-xs font-medium transition shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}" target="_blank" rel="noopener"
                           class="flex items-center justify-center gap-2 px-3 py-2.5 bg-black hover:bg-gray-800 text-white rounded-lg text-xs font-medium transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($news->title) }}" target="_blank" rel="noopener"
                           class="flex items-center justify-center gap-2 px-3 py-2.5 bg-[#0A66C2] hover:bg-[#004182] text-white rounded-lg text-xs font-medium transition shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            LinkedIn
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->url()) }}" target="_blank" rel="noopener"
                           class="flex items-center justify-center gap-2 px-3 py-2.5 bg-[#25D366] hover:bg-[#20bd5a] text-white rounded-lg text-xs font-medium transition shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>
                    </div>
                    <button onclick="copyLink(this)" class="mt-2.5 w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <span>Salin Link</span>
                    </button>
                </div>

                {{-- About Author --}}
                @if($news->author)
                <div class="bg-white rounded-xl border shadow-sm p-5">
                    <p class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Tentang Penulis
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md shrink-0">
                            {{ strtoupper(substr($news->author->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $news->author->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $news->author->email }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Latest News Sidebar --}}
                @if($latestNews->count())
                <div class="bg-white rounded-xl border shadow-sm p-5">
                    <p class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        Berita Terbaru
                    </p>
                    <div class="space-y-4">
                        @foreach($latestNews as $latest)
                        <a href="{{ route('news.detail', $latest->slug) }}" class="flex items-start gap-3 group">
                            @if($latest->cover_image)
                            <img src="{{ asset('storage/' . $latest->cover_image) }}" alt="" class="w-16 h-16 rounded-lg object-cover shrink-0 shadow-sm">
                            @else
                            <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-100 shrink-0 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition line-clamp-2 leading-snug">{{ $latest->title }}</h4>
                                <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $latest->published_at?->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <a href="{{ url('/') }}#news" class="mt-5 flex items-center justify-center gap-1.5 w-full px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold transition">
                        Lihat Semua Berita
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                @endif

            </aside>
        </div>
    </div>

    {{-- ══════════ FOOTER ══════════ --}}
    <footer class="bg-gray-900 text-gray-400 mt-10">
        <div class="border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row justify-between items-center text-sm gap-2">
                <p>{{ $footerText ?: '© ' . date('Y') . ' ' . $siteName . '. All rights reserved.' }}</p>
                @if($poweredBy)
                <p class="text-gray-600">{{ $poweredBy }}</p>
                @endif
            </div>
        </div>
    </footer>

    <script>
    function copyLink(btn) {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const span = btn.querySelector('span');
            if (span) {
                const orig = span.textContent;
                span.textContent = 'Tersalin!';
                setTimeout(() => span.textContent = orig, 2000);
            } else {
                const orig = btn.innerHTML;
                btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                setTimeout(() => btn.innerHTML = orig, 2000);
            }
        });
    }
    </script>
</body>
</html>
