@if(!$activeConference || $activeConference->isSectionVisible('info_cards'))
    {{-- ═══════════════════════════════════════════════════════════════════
         INFO CARDS — Tanggal Penting / Publikasi Prosiding / Makalah Terpilih
         (Referensi: semnas.iti.ac.id)
    ═══════════════════════════════════════════════════════════════════ --}}
    <section id="dates" class="py-14 bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-0 md:divide-x divide-gray-200">

                {{-- Card 1: TANGGAL PENTING — from importantDates --}}
                <div class="flex items-start gap-5 px-6 py-4 md:pr-8">
                    <div class="shrink-0 mt-1">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-gray-700 uppercase tracking-wider mb-3">{{ __('welcome.info.tanggal_penting') }}</h3>
                        @if($activeConference && $activeConference->importantDates->count())
                        <ul class="space-y-1.5 text-sm text-gray-600">
                            @foreach($activeConference->importantDates->sortBy('sort_order') as $date)
                            <li class="{{ $date->is_past ? 'line-through text-gray-400' : '' }}">
                                <span class="font-medium text-gray-700">{{ $date->title }}</span> :
                                <span class="text-red-600 font-semibold">{{ $date->date?->translatedFormat('d F Y') ?? '-' }}</span>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-sm text-gray-400 italic">{{ __('welcome.info.tanggal_penting_empty') }}</p>
                        @endif
                    </div>
                </div>

                {{-- Card 2: PUBLIKASI PROSIDING — from settings --}}
                <div class="flex items-start gap-5 px-6 py-4 md:px-8 border-t md:border-t-0">
                    <div class="shrink-0 mt-1">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-gray-700 uppercase tracking-wider mb-3">{{ __('welcome.info.publikasi_prosiding') }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($publicationInfo)) !!}</p>
                    </div>
                </div>

                {{-- Card 3: MAKALAH TERPILIH — from settings --}}
                <div class="flex items-start gap-5 px-6 py-4 md:pl-8 border-t md:border-t-0">
                    <div class="shrink-0 mt-1">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-4.5A3.375 3.375 0 0019.875 10.875h0A3.375 3.375 0 0016.5 7.5h0V3.75m0 15h0m-9-15v3.75m0 0A3.375 3.375 0 004.125 10.875h0A3.375 3.375 0 007.5 14.25v4.5m0 0h0"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-gray-700 uppercase tracking-wider mb-3">{{ __('welcome.info.makalah_terpilih') }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($selectedPapersInfo)) !!}</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endif