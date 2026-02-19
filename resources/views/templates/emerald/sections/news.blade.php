    {{-- ════════════════════════════════════════════════════
         NEWS & ANNOUNCEMENTS — Card grid
    ════════════════════════════════════════════════════ --}}
    @if($latestNews->count() || $announcements->count())
    <section id="news" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14">
                <span class="inline-block bg-rose-100 text-rose-700 text-xs font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-wider">{{ __('welcome.news.badge') }}</span>
                <h2 class="text-3xl font-extrabold text-gray-900">{{ __('welcome.news.title') }}</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-{{ ($latestNews->count() && $announcements->count()) ? '5' : '1' }} gap-10">
                @if($latestNews->count())
                <div class="lg:col-span-3">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        {{ __('welcome.news.berita_terkini') }}
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-{{ min($latestNews->count(), 3) }} gap-5">
                        @foreach($latestNews as $news)
                        <a href="{{ route('news.detail', $news->slug) }}" class="group bg-white rounded-xl border hover:shadow-lg transition overflow-hidden">
                            @if($news->cover_image)
                            <div class="h-40 overflow-hidden">
                                <img src="{{ asset('storage/' . $news->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="">
                            </div>
                            @else
                            <div class="h-40 bg-gradient-to-br from-teal-100 to-emerald-100 flex items-center justify-center">
                                <svg class="w-10 h-10 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                            @endif
                            <div class="p-4">
                                <p class="text-xs text-gray-400 mb-1">{{ $news->published_at?->translatedFormat('d M Y') }}</p>
                                <h4 class="text-sm font-bold text-gray-800 group-hover:text-teal-700 transition line-clamp-2 mb-1">{{ $news->title }}</h4>
                                @if($news->excerpt)
                                <p class="text-xs text-gray-400 line-clamp-2">{{ $news->excerpt }}</p>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($announcements->count())
                <div class="{{ $latestNews->count() ? 'lg:col-span-2' : 'max-w-xl mx-auto' }}">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        {{ __('welcome.news.pengumuman') }}
                    </h3>
                    <div class="space-y-3">
                        @foreach($announcements as $ann)
                        <div class="bg-teal-50/60 rounded-xl p-4 border border-teal-100 hover:shadow-sm transition">
                            <div class="flex items-start gap-3">
                                @if($ann->is_pinned)
                                <div class="bg-amber-400 p-1 rounded-lg shrink-0">
                                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-gray-400 mb-0.5">{{ $ann->published_at?->translatedFormat('d M Y') }}</p>
                                    <h4 class="text-sm font-bold text-gray-800 line-clamp-2">{{ $ann->title }}</h4>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{!! Str::limit(strip_tags($ann->content), 120) !!}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif
