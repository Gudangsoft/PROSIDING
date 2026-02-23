    {{-- ═══════════════════════════════════════════════════════════════════
         ABOUT CONFERENCE — Redesigned Premium Layout
    ═══════════════════════════════════════════════════════════════════ --}}
    @if($activeConference && $activeConference->isSectionVisible('about'))
    <section id="conference" class="relative py-24 overflow-hidden bg-white">

        {{-- Decorative blobs --}}
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-50 rounded-full opacity-60 -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-indigo-50 rounded-full opacity-50 translate-x-1/3 translate-y-1/3 pointer-events-none"></div>
        <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-blue-100 rounded-full opacity-30 pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <div class="text-center mb-14">
                <span class="inline-flex items-center gap-2 bg-blue-600/10 text-blue-700 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-4 border border-blue-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse inline-block"></span>
                    {{ __('welcome.conference.badge') }}
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-4 max-w-4xl mx-auto">
                    {{ $activeConference->name }}
                </h2>
                @if($activeConference->theme)
                <div class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl px-6 py-3 mt-2">
                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    <p class="text-base text-blue-700 font-semibold italic">{{ $activeConference->theme }}</p>
                    <svg class="w-4 h-4 text-blue-500 shrink-0 rotate-180" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                </div>
                @endif
            </div>

            @php
                $posterImage = $activeConference->cover_image ?? $activeConference->brochure;
                $posterLabel = $activeConference->cover_image ? __('welcome.conference.poster') : __('welcome.conference.brosur');
                $hasPoster   = !empty($posterImage);
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

                {{-- LEFT: Description + Topics --}}
                <div class="lg:col-span-5 order-2 lg:order-1" x-data="{ expanded: false }">

                    @if($activeConference->description)
                    <div class="bg-gradient-to-br from-slate-50 to-blue-50/40 rounded-2xl border border-blue-100/60 p-6 mb-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-blue-100/50 rounded-bl-3xl pointer-events-none"></div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Tentang Konferensi</h3>
                        </div>
                        <div class="relative">
                            <div class="prose prose-sm prose-blue max-w-none text-gray-600 leading-relaxed"
                                 :class="expanded ? '' : 'max-h-40 overflow-hidden'">
                                {!! nl2br(e($activeConference->description)) !!}
                            </div>
                            <div x-show="!expanded"
                                 class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-slate-50/95 to-transparent pointer-events-none"></div>
                        </div>
                        <button @click="expanded = !expanded"
                                class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors group">
                            <span x-text="expanded ? 'Sembunyikan' : 'Baca Selengkapnya'"></span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-y-0.5" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                    @endif

                    @if($activeConference->topics->count())
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">{{ __('welcome.conference.topik_bidang') }}</h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($activeConference->topics->sortBy('sort_order') as $i => $topic)
                            @php
                                $colors = ['bg-blue-50 text-blue-700 border-blue-200','bg-indigo-50 text-indigo-700 border-indigo-200','bg-violet-50 text-violet-700 border-violet-200','bg-purple-50 text-purple-700 border-purple-200','bg-cyan-50 text-cyan-700 border-cyan-200','bg-teal-50 text-teal-700 border-teal-200'];
                                $color = $colors[$i % count($colors)];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border {{ $color }} hover:shadow-sm transition-shadow cursor-default">
                                <span class="w-1 h-1 rounded-full bg-current opacity-60"></span>
                                {{ $topic->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- CENTER: Poster / Brosur --}}
                @if($hasPoster)
                <div class="lg:col-span-4 order-1 lg:order-2" x-data="{ lightbox: false }">
                    <div class="sticky top-24">
                        <div class="relative group rounded-2xl overflow-hidden shadow-2xl border border-gray-200/60 bg-white ring-1 ring-gray-900/5 hover:shadow-blue-200/60 transition-shadow duration-500">
                            <div class="absolute inset-0 bg-gradient-to-tr from-blue-400/10 to-indigo-400/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-10"></div>
                            <div class="relative cursor-pointer" @click="lightbox = true">
                                <img src="{{ asset('storage/' . $posterImage) }}" alt="{{ $activeConference->name }}"
                                     class="w-full h-auto transition-transform duration-700 group-hover:scale-[1.03]">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6 z-20">
                                    <span class="inline-flex items-center gap-2 text-white text-sm font-semibold bg-white/20 backdrop-blur-md px-4 py-2.5 rounded-full border border-white/30 shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                        {{ __('welcome.conference.klik_perbesar') }}
                                    </span>
                                </div>
                            </div>
                            <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
                                <span class="text-xs text-gray-600 font-semibold">{{ $posterLabel }}</span>
                                <a href="{{ asset('storage/' . $posterImage) }}" download
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 active:scale-95 transition-all shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                    <div x-show="lightbox" x-cloak
                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                         @click="lightbox = false" @keydown.escape.window="lightbox = false"
                         class="fixed inset-0 z-[100] bg-black/85 backdrop-blur-sm flex items-center justify-center p-4 cursor-zoom-out">
                        <img src="{{ asset('storage/' . $posterImage) }}" alt="{{ $activeConference->name }}"
                             class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl" @click.stop>
                        <button @click="lightbox = false" class="absolute top-4 right-4 w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/30 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                @endif

                {{-- RIGHT: Info Card --}}
                <div class="{{ $hasPoster ? 'lg:col-span-3' : 'lg:col-span-7' }} order-3">
                    <div class="sticky top-24 space-y-4">
                        @if($activeConference->logo)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex justify-center">
                            <img src="{{ asset('storage/' . $activeConference->logo) }}" alt="Logo {{ $activeConference->name }}" class="h-20 w-auto object-contain">
                        </div>
                        @endif
                        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-4">
                                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ __('welcome.conference.info_kegiatan') }}
                                </h3>
                            </div>
                            <div class="p-5 space-y-4">
                                @if($activeConference->start_date)
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center shrink-0 border border-blue-100">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('welcome.conference.tanggal') }}</p>
                                        <p class="text-sm font-bold text-gray-800 mt-0.5">
                                            {{ $activeConference->start_date->translatedFormat('d F Y') }}
                                            @if($activeConference->end_date && $activeConference->end_date->ne($activeConference->start_date)) — {{ $activeConference->end_date->translatedFormat('d F Y') }} @endif
                                        </p>
                                        @if($activeConference->formatted_time)
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $activeConference->formatted_time }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @php $vColor = ['offline'=>'bg-green-50 border-green-100','online'=>'bg-sky-50 border-sky-100','hybrid'=>'bg-purple-50 border-purple-100'][$activeConference->venue_type ?? 'offline'] ?? 'bg-gray-50 border-gray-100'; @endphp
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 {{ $vColor }} rounded-xl flex items-center justify-center shrink-0 border">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ \App\Models\Conference::VENUE_TYPE_ICONS[$activeConference->venue_type ?? 'offline'] ?? '' }}"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('welcome.conference.lokasi') }}</p>
                                        <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $activeConference->venue_type_label }}</p>
                                        @if($activeConference->venue_display)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $activeConference->venue_display }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($activeConference->organizer)
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center shrink-0 border border-purple-100">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('welcome.conference.penyelenggara') }}</p>
                                        <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $activeConference->organizer }}</p>
                                    </div>
                                </div>
                                @endif
                                <div class="border-t border-gray-100 pt-4 space-y-2">
                                    @auth
                                    <a href="{{ url('/dashboard') }}"
                                       class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Submit Paper
                                    </a>
                                    @else
                                    <a href="{{ route('register') }}"
                                       class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Submit Paper
                                    </a>
                                    <a href="{{ route('login') }}"
                                       class="flex items-center justify-center gap-2 w-full bg-white text-gray-700 border border-gray-200 text-sm font-semibold py-3 rounded-xl hover:bg-gray-50 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                        Login
                                    </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STATS STRIP --}}
            @php
                $paperCount   = $activeConference->papers()->count();
                $topicCount   = $activeConference->topics()->count();
                $speakerCount = $activeConference->keynoteSpeakers()->count();
                $packageCount = $activeConference->registrationPackages()->where('is_active', true)->count();
            @endphp
            @if($paperCount || $topicCount || $speakerCount || $packageCount)
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4">
                @if($paperCount)
                <div class="bg-gradient-to-br from-blue-50 to-blue-100/60 border border-blue-200/60 rounded-2xl p-5 text-center hover:shadow-md transition-shadow group">
                    <div class="text-3xl font-extrabold text-blue-700 mb-1 group-hover:scale-110 transition-transform inline-block">{{ number_format($paperCount) }}</div>
                    <div class="text-xs font-bold text-blue-500 uppercase tracking-wider">Paper Submitted</div>
                </div>
                @endif
                @if($topicCount)
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100/60 border border-indigo-200/60 rounded-2xl p-5 text-center hover:shadow-md transition-shadow group">
                    <div class="text-3xl font-extrabold text-indigo-700 mb-1 group-hover:scale-110 transition-transform inline-block">{{ $topicCount }}</div>
                    <div class="text-xs font-bold text-indigo-500 uppercase tracking-wider">Topik Bidang</div>
                </div>
                @endif
                @if($speakerCount)
                <div class="bg-gradient-to-br from-violet-50 to-violet-100/60 border border-violet-200/60 rounded-2xl p-5 text-center hover:shadow-md transition-shadow group">
                    <div class="text-3xl font-extrabold text-violet-700 mb-1 group-hover:scale-110 transition-transform inline-block">{{ $speakerCount }}</div>
                    <div class="text-xs font-bold text-violet-500 uppercase tracking-wider">Keynote Speaker</div>
                </div>
                @endif
                @if($packageCount)
                <div class="bg-gradient-to-br from-cyan-50 to-cyan-100/60 border border-cyan-200/60 rounded-2xl p-5 text-center hover:shadow-md transition-shadow group">
                    <div class="text-3xl font-extrabold text-cyan-700 mb-1 group-hover:scale-110 transition-transform inline-block">{{ $packageCount }}</div>
                    <div class="text-xs font-bold text-cyan-500 uppercase tracking-wider">Paket Registrasi</div>
                </div>
                @endif
            </div>
            @endif

        </div>
    </section>
    @endif
