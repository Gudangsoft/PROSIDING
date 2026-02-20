    {{-- ═══════════════════════════════════════════════════════════════════
         NEWS & ANNOUNCEMENTS
    ═══════════════════════════════════════════════════════════════════ --}}
    @if(($latestNews->count() || $announcements->count()) && (!$activeConference || $activeConference->isSectionVisible('news')))
    <section id="news" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block bg-rose-100 text-rose-700 text-xs font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-wider">{{ __('welcome.news.badge') }}</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">{{ __('welcome.news.title') }}</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-{{ ($latestNews->count() && $announcements->count()) ? '2' : '1' }} gap-10">
                {{-- Latest News --}}
                @if($latestNews->count())
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        {{ __('welcome.news.berita_terkini') }}
                    </h3>
                    <div class="space-y-4">
                        @foreach($latestNews as $news)
                        <a href="{{ route('news.detail', $news->slug) }}" class="block bg-gray-50 rounded-xl p-5 border hover:shadow-md transition group">
                            <div class="flex items-start gap-4">
                                @if($news->cover_image)
                                <img src="{{ asset('storage/' . $news->cover_image) }}" class="w-24 h-24 rounded-xl object-cover shrink-0 shadow" alt="">
                                @else
                                <div class="w-24 h-24 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 shrink-0 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-400 mb-1">
                                        <svg class="w-3 h-3 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $news->published_at?->translatedFormat('d M Y') }}
                                    </p>
                                    <h4 class="text-sm font-bold text-gray-800 group-hover:text-blue-600 transition line-clamp-2 mb-1">{{ $news->title }}</h4>
                                    @if($news->excerpt)
                                    <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $news->excerpt }}</p>
                                    @endif
                                    <span class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-blue-600 group-hover:text-blue-800 transition">
                                        {{ __('welcome.news.baca_selengkapnya') }}
                                        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Announcements --}}
                @if($announcements->count())
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        {{ __('welcome.news.pengumuman') }}
                    </h3>
                    <div class="space-y-4">
                        @foreach($announcements as $ann)
                        <div class="bg-blue-50 rounded-xl p-5 border border-blue-200 hover:shadow-md transition">
                            <div class="flex items-start gap-3">
                                @if($ann->is_pinned)
                                <div class="bg-yellow-400 p-1.5 rounded-lg shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-400 mb-1">{{ $ann->published_at?->translatedFormat('d M Y') }}</p>
                                    <h4 class="text-sm font-bold text-gray-800 line-clamp-2 mb-1">{{ $ann->title }}</h4>
                                    <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{!! Str::limit(strip_tags($ann->content), 150) !!}</p>
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
