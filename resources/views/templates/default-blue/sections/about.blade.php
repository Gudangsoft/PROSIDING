    {{-- ═══════════════════════════════════════════════════════════════════
         ABOUT CONFERENCE — Description + Theme + Info
    ═══════════════════════════════════════════════════════════════════ --}}
    @if($activeConference && $activeConference->isSectionVisible('about'))
    <section id="conference" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @php
                $posterImage = $activeConference->cover_image ?? $activeConference->brochure;
                $posterLabel = $activeConference->cover_image ? __('welcome.conference.poster') : __('welcome.conference.brosur');
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                {{-- Left — Conference Info --}}
                <div class="lg:col-span-5 order-2 lg:order-1">
                    <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full mb-4 uppercase tracking-wider">{{ __('welcome.conference.badge') }}</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $activeConference->name }}</h2>
                    @if($activeConference->theme)
                    <p class="text-lg text-blue-600 font-medium italic mb-6">"{{ $activeConference->theme }}"</p>
                    @endif
                    @if($activeConference->description)
                    <div class="prose prose-gray max-w-none text-gray-600 leading-relaxed mb-8">
                        {!! nl2br(e($activeConference->description)) !!}
                    </div>
                    @endif

                    <style>#topics-scroll::-webkit-scrollbar{display:none}</style>
                    {{-- Topics --}}
                    @if($activeConference->topics->count())
                    <div class="mt-8">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1 h-5 bg-blue-600 rounded-full"></div>
                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">{{ __('welcome.conference.topik_bidang') }}</h3>
                        </div>
                        <div class="relative">
                            {{-- gradient fade kiri --}}
                            <div class="pointer-events-none absolute left-0 top-0 h-full w-8 bg-gradient-to-r from-white to-transparent z-10"></div>
                            {{-- gradient fade kanan --}}
                            <div class="pointer-events-none absolute right-0 top-0 h-full w-8 bg-gradient-to-l from-white to-transparent z-10"></div>
                            <div id="topics-scroll" class="flex flex-nowrap overflow-x-auto gap-2 px-2 py-1"
                                 style="-webkit-overflow-scrolling: touch; scrollbar-width: none; -ms-overflow-style: none;"
                                 onscroll="this.previousElementSibling.style.opacity=this.scrollLeft>4?'1':'0'"
                            >
                            @foreach($activeConference->topics->sortBy('sort_order') as $index => $topic)
                            @php
                                $colors = [
                                    ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',   'text' => 'text-blue-700',   'dot' => 'bg-blue-500'],
                                    ['bg' => 'bg-indigo-50', 'border' => 'border-indigo-200', 'text' => 'text-indigo-700', 'dot' => 'bg-indigo-500'],
                                    ['bg' => 'bg-violet-50', 'border' => 'border-violet-200', 'text' => 'text-violet-700', 'dot' => 'bg-violet-500'],
                                    ['bg' => 'bg-sky-50',    'border' => 'border-sky-200',    'text' => 'text-sky-700',    'dot' => 'bg-sky-500'],
                                    ['bg' => 'bg-teal-50',   'border' => 'border-teal-200',   'text' => 'text-teal-700',   'dot' => 'bg-teal-500'],
                                    ['bg' => 'bg-cyan-50',   'border' => 'border-cyan-200',   'text' => 'text-cyan-700',   'dot' => 'bg-cyan-500'],
                                ];
                                $c = $colors[$index % count($colors)];
                            @endphp
                            <span class="inline-flex shrink-0 items-center gap-1.5 px-3 py-1.5 rounded-full border text-sm font-medium {{ $c['bg'] }} {{ $c['border'] }} {{ $c['text'] }} hover:shadow-sm transition-shadow duration-200">
                                <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $c['dot'] }}"></span>
                                {{ $topic->name }}
                            </span>
                            @endforeach
                            </div>{{-- end scroll inner --}}
                        </div>{{-- end relative wrapper --}}
                    </div>
                    @endif
                </div>

                {{-- Center — Poster / Brosur --}}
                @if($posterImage)
                <div class="lg:col-span-4 order-1 lg:order-2" x-data="{ lightbox: false }">
                    <div class="sticky top-24">
                        <div class="relative group rounded-2xl overflow-hidden shadow-xl border border-gray-200/60 bg-white ring-1 ring-gray-900/5">
                            {{-- Image with hover overlay --}}
                            <div class="relative cursor-pointer" @click="lightbox = true">
                                <img src="{{ asset('storage/' . $posterImage) }}" alt="{{ $activeConference->name }}"
                                     class="w-full h-auto transition-transform duration-500 group-hover:scale-[1.02]">
                                {{-- Gradient overlay on hover --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6">
                                    <span class="inline-flex items-center gap-2 text-white text-sm font-semibold bg-white/20 backdrop-blur-md px-4 py-2.5 rounded-full border border-white/30 shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                        {{ __('welcome.conference.klik_perbesar') }}
                                    </span>
                                </div>
                            </div>
                            {{-- Bottom bar --}}
                            <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-xs text-gray-600 font-semibold">{{ $posterLabel }}</span>
                                </div>
                                <a href="{{ asset('storage/' . $posterImage) }}" download
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 active:scale-95 transition-all shadow-sm hover:shadow-md">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Lightbox modal --}}
                    <div x-show="lightbox" x-cloak
                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                         @click="lightbox = false" @keydown.escape.window="lightbox = false"
                         class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 cursor-zoom-out">
                        <img src="{{ asset('storage/' . $posterImage) }}" alt="{{ $activeConference->name }}"
                             class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl"
                             @click.stop>
                        <button @click="lightbox = false" class="absolute top-4 right-4 w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/30 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                @endif

                {{-- Right — Key Info Card --}}
                <div class="{{ $posterImage ? 'lg:col-span-3' : 'lg:col-span-7' }} order-3">
                    <div class="bg-white rounded-2xl shadow-lg border p-6 space-y-5 sticky top-24">
                        @if($activeConference->logo)
                        <div class="flex justify-center pb-3 border-b border-gray-200">
                            <img src="{{ asset('storage/' . $activeConference->logo) }}" alt="Logo {{ $activeConference->name }}" class="h-20 w-auto object-contain">
                        </div>
                        @endif
                        <h3 class="text-lg font-bold text-gray-800 {{ $activeConference->logo ? '' : 'border-b pb-3' }}">{{ __('welcome.conference.info_kegiatan') }}</h3>

                        @if($activeConference->start_date)
                        <div class="flex items-start gap-3">
                            <div class="bg-blue-100 p-2 rounded-lg shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('welcome.conference.tanggal') }}</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $activeConference->start_date->translatedFormat('d F Y') }}
                                    @if($activeConference->end_date && $activeConference->end_date->ne($activeConference->start_date)) — {{ $activeConference->end_date->translatedFormat('d F Y') }} @endif
                                </p>
                                @if($activeConference->formatted_time)
                                <p class="text-xs text-gray-500 mt-0.5">🕐 {{ $activeConference->formatted_time }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start gap-3">
                            <div class="p-2 rounded-lg shrink-0 {{ \App\Models\Conference::VENUE_TYPE_COLORS[$activeConference->venue_type ?? 'offline'] ?? 'bg-gray-100' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ \App\Models\Conference::VENUE_TYPE_ICONS[$activeConference->venue_type ?? 'offline'] ?? '' }}"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('welcome.conference.lokasi') }}</p>
                                <p class="text-sm font-bold text-gray-800">{{ $activeConference->venue_type_label }} — {{ $activeConference->venue_display }}</p>
                            </div>
                        </div>

                        @if($activeConference->organizer)
                        <div class="flex items-start gap-3">
                            <div class="bg-purple-100 p-2 rounded-lg shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('welcome.conference.penyelenggara') }}</p>
                                <p class="text-sm font-bold text-gray-800">{{ $activeConference->organizer }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="pt-2 flex flex-col gap-2.5">
                            @if($activeConference->read_more_url)
                            <a href="{{ $activeConference->read_more_url }}" target="_blank" rel="noopener"
                               class="flex items-center justify-center gap-2 w-full border-2 border-blue-600 text-blue-600 text-center py-3 rounded-xl font-semibold hover:bg-blue-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                </svg>
                                Selengkapnya
                            </a>
                            @endif
                            @auth
                                <a href="{{ url('/dashboard') }}" class="flex items-center justify-center gap-2 w-full bg-blue-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.338-2.32 3.75 3.75 0 0 1 3.827 3.785h.004a3 3 0 0 1-2.497 2.972"/>
                                    </svg>
                                    Submit Paper
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 w-full bg-blue-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.338-2.32 3.75 3.75 0 0 1 3.827 3.785h.004a3 3 0 0 1-2.497 2.972"/>
                                    </svg>
                                    Submit Paper
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
