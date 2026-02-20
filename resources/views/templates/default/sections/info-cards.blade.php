@if(!$activeConference || $activeConference->isSectionVisible('info_cards'))
    {{-- ═══════════════════════════════════════════════════════════════════
         INFO CARDS — Tanggal Penting / Publikasi Prosiding / Makalah Terpilih
    ═══════════════════════════════════════════════════════════════════ --}}
    <section id="dates" class="py-16 bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Card 1: TANGGAL PENTING --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    {{-- Header --}}
                    <div class="flex items-center gap-3 px-6 py-4 bg-blue-600">
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-widest">{{ __('welcome.info.tanggal_penting') }}</h3>
                    </div>
                    {{-- Body: Timeline --}}
                    <div class="px-6 py-5">
                        @if($activeConference && $activeConference->importantDates->count())
                        <ul class="space-y-0">
                            @foreach($activeConference->importantDates->sortBy('sort_order') as $i => $date)
                            @php $past = $date->is_past; @endphp
                            <li class="flex gap-4 {{ !$loop->last ? 'pb-4' : '' }}">
                                {{-- Timeline line + dot --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-3 h-3 rounded-full mt-1 shrink-0 {{ $past ? 'bg-gray-300' : 'bg-blue-500 ring-4 ring-blue-100' }}"></div>
                                    @if(!$loop->last)
                                    <div class="w-px flex-1 {{ $past ? 'bg-gray-200' : 'bg-blue-200' }} my-1"></div>
                                    @endif
                                </div>
                                {{-- Content --}}
                                <div class="flex-1 min-w-0 pb-1">
                                    <p class="text-sm font-semibold {{ $past ? 'text-gray-400 line-through' : 'text-gray-800' }} leading-snug">
                                        {{ $date->title }}
                                    </p>
                                    <span class="inline-block mt-1 text-xs font-bold px-2.5 py-0.5 rounded-full
                                        {{ $past ? 'bg-gray-100 text-gray-400' : 'bg-blue-50 text-blue-700' }}">
                                        {{ $date->date?->translatedFormat('d F Y') ?? '-' }}
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-sm text-gray-400 italic py-4 text-center">{{ __('welcome.info.tanggal_penting_empty') }}</p>
                        @endif
                    </div>
                </div>

                {{-- Card 2: PUBLIKASI PROSIDING --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 bg-emerald-600">
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-widest">{{ __('welcome.info.publikasi_prosiding') }}</h3>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($publicationInfo)) !!}</p>
                    </div>
                </div>

                {{-- Card 3: MAKALAH TERPILIH --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 bg-violet-600">
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-widest">{{ __('welcome.info.makalah_terpilih') }}</h3>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($selectedPapersInfo)) !!}</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endif