    {{-- ════════════════════════════════════════════════════
         ABOUT CONFERENCE — Side-by-side elegant layout
    ════════════════════════════════════════════════════ --}}
    @if($activeConference && $activeConference->isSectionVisible('about'))
    <section id="about" class="py-20 bg-gray-50/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                {{-- Left: Info --}}
                <div>
                    <span class="inline-block bg-teal-100 text-teal-700 text-xs font-bold px-3 py-1.5 rounded-full mb-5 uppercase tracking-wider">{{ __('welcome.conference.badge') }}</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-3 leading-tight">{{ $activeConference->name }}</h2>
                    @if($activeConference->theme)
                    <p class="text-lg text-teal-600 font-medium italic mb-6">"{{ $activeConference->theme }}"</p>
                    @endif
                    @if($activeConference->description)
                    <div class="text-gray-600 leading-relaxed mb-8">{!! nl2br(e($activeConference->description)) !!}</div>
                    @endif

                    {{-- Key Info Pills --}}
                    <div class="space-y-3 mb-8">
                        @if($activeConference->start_date)
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-teal-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase">{{ __('welcome.conference.tanggal') }}</p>
                                <p class="text-sm font-bold text-gray-800">{{ $activeConference->start_date->translatedFormat('d F Y') }}@if($activeConference->end_date && $activeConference->end_date->ne($activeConference->start_date)) — {{ $activeConference->end_date->translatedFormat('d F Y') }}@endif</p>
                                @if($activeConference->formatted_time)
                                <p class="text-xs text-gray-500 mt-0.5">🕐 {{ $activeConference->formatted_time }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase">{{ __('welcome.conference.lokasi') }}</p>
                                <p class="text-sm font-bold text-gray-800">{{ $activeConference->venue_type_label }} — {{ $activeConference->venue_display }}</p>
                            </div>
                        </div>
                        @if($activeConference->organizer)
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-cyan-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase">{{ __('welcome.conference.penyelenggara') }}</p>
                                <p class="text-sm font-bold text-gray-800">{{ $activeConference->organizer }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Topics --}}
                    @if($activeConference->topics->count())
                    <div>
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">{{ __('welcome.conference.topik_bidang') }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($activeConference->topics->sortBy('sort_order') as $topic)
                            <span class="text-sm bg-white text-teal-700 px-3 py-1.5 rounded-full border border-teal-200 font-medium">{{ $topic->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Right: Poster / CTA Card --}}
                <div class="sticky top-24">
                    @php $posterImage = $activeConference->cover_image ?? $activeConference->brochure; @endphp
                    @if($posterImage)
                    <div class="rounded-2xl overflow-hidden shadow-xl border border-gray-200 bg-white mb-6" x-data="{ lightbox: false }">
                        <div class="relative cursor-pointer group" @click="lightbox = true">
                            <img src="{{ asset('storage/' . $posterImage) }}" alt="{{ $activeConference->name }}" class="w-full h-auto">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center">
                                <span class="opacity-0 group-hover:opacity-100 text-white bg-black/40 backdrop-blur px-4 py-2 rounded-full text-sm font-medium transition">
                                    <svg class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                    {{ __('welcome.conference.klik_perbesar') }}
                                </span>
                            </div>
                        </div>
                        {{-- Lightbox --}}
                        <div x-show="lightbox" x-cloak x-transition @click="lightbox = false" @keydown.escape.window="lightbox = false"
                             class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 cursor-zoom-out">
                            <img src="{{ asset('storage/' . $posterImage) }}" alt="{{ $activeConference->name }}" class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl" @click.stop>
                            <button @click="lightbox = false" class="absolute top-4 right-4 w-10 h-10 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white hover:bg-white/30 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
                        @if($activeConference->logo)
                        <img src="{{ asset('storage/' . $activeConference->logo) }}" alt="Logo" class="h-16 mx-auto mb-4 object-contain">
                        @endif
                        <p class="text-gray-500 text-sm mb-5">{{ __('welcome.conference.info_kegiatan') }}</p>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-full bg-teal-600 text-white py-3 rounded-full font-semibold hover:bg-teal-700 transition shadow-sm">Submit Paper</a>
                        @else
                            <a href="{{ route('register') }}" class="block w-full bg-teal-600 text-white py-3 rounded-full font-semibold hover:bg-teal-700 transition shadow-sm">Submit Paper</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
