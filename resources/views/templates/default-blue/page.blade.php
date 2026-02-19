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
        $siteLogo = \App\Models\Setting::getValue('site_logo');
        $footerText = \App\Models\Setting::getValue('footer_text');
        $poweredBy = \App\Models\Setting::getValue('powered_by', 'Powered by Laravel');
        $contactEmail = \App\Models\Setting::getValue('contact_email');
        $contactPhone = \App\Models\Setting::getValue('contact_phone');
        $socialFacebook = \App\Models\Setting::getValue('social_facebook');
        $socialInstagram = \App\Models\Setting::getValue('social_instagram');
        $socialTwitter = \App\Models\Setting::getValue('social_twitter');
    @endphp
    <title>{{ $page->meta_title ?: $page->title }} — {{ $siteName }}</title>
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
    @include('templates.default-blue.partials.theme-config')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .prose-content { font-size: 1.0625rem; line-height: 1.85; color: #374151; }
        .prose-content p { margin-bottom: 1.5em; }
        .prose-content h1 { font-size: 2em; font-weight: 800; margin-top: 1.5em; margin-bottom: 0.75em; color: #111827; line-height: 1.2; }
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
                    @auth
                    <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold transition px-3 py-1.5 rounded-lg hover:bg-blue-50">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-blue-700 px-3 py-2 font-medium transition">Login</a>
                    <a href="{{ route('register') }}" class="text-sm bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if($page->layout !== 'blank')
    {{-- ══════════ HERO / HEADER ══════════ --}}
    <header class="relative overflow-hidden" style="background: linear-gradient(135deg, var(--theme-hero-bg, #1e40af), var(--theme-secondary-color, #4f46e5));">
        @if($page->cover_image)
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $page->cover_image) }}" alt="" class="w-full h-full object-cover opacity-20">
        </div>
        @endif
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">{{ $page->title }}</h1>
            @if($page->excerpt)
            <p class="text-lg text-white/80 max-w-2xl mx-auto">{{ $page->excerpt }}</p>
            @endif
        </div>
    </header>
    @endif

    {{-- ══════════ CONTENT ══════════ --}}
    <main class="py-12">
        <div class="{{ $page->layout === 'narrow' ? 'max-w-3xl' : ($page->layout === 'sidebar' ? 'max-w-7xl' : 'max-w-5xl') }} mx-auto px-4 sm:px-6 lg:px-8">
            @if($page->layout === 'sidebar')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <article class="bg-white rounded-2xl shadow-sm border p-8 md:p-10">
                        <div class="prose-content">
                            {!! $page->content !!}
                        </div>
                    </article>
                </div>
                {{-- Sidebar --}}
                <aside class="space-y-6">
                    @if($relatedPages->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-sm border p-5">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Halaman Lainnya
                        </h3>
                        <ul class="space-y-2">
                            @foreach($relatedPages as $rp)
                            <li>
                                <a href="{{ route('page.show', $rp->slug) }}"
                                   class="text-sm text-gray-600 hover:text-blue-600 transition flex items-center gap-2 {{ $rp->id === $page->id ? 'text-blue-600 font-semibold' : '' }}">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    {{ $rp->title }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </aside>
            </div>
            @else
            <article class="bg-white rounded-2xl shadow-sm border p-8 md:p-10">
                @if($page->layout === 'blank')
                <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>
                @endif
                <div class="prose-content">
                    {!! $page->content !!}
                </div>
            </article>
            @endif
        </div>
    </main>

    @if($page->layout !== 'blank')
    {{-- ══════════ FOOTER ══════════ --}}
    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    @if($siteLogo)
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo" class="h-8 object-contain opacity-80">
                    @endif
                    <span class="text-sm text-gray-400">{{ $footerText ?? '© ' . date('Y') . ' ' . $siteName }}</span>
                </div>
                <div class="flex items-center gap-4">
                    @if($socialFacebook)<a href="{{ $socialFacebook }}" target="_blank" class="text-gray-500 hover:text-white transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>@endif
                    @if($socialInstagram)<a href="{{ $socialInstagram }}" target="_blank" class="text-gray-500 hover:text-white transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>@endif
                    @if($socialTwitter)<a href="{{ $socialTwitter }}" target="_blank" class="text-gray-500 hover:text-white transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>@endif
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-gray-800 text-center text-xs text-gray-500">
                {!! $poweredBy !!}
            </div>
        </div>
    </footer>
    @endif
</body>
</html>
