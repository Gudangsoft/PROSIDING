    {{-- ════════════════════════════════════════════════════
         INFO STRIP — 3 columns, minimal card style
    ════════════════════════════════════════════════════ --}}
    <section id="dates" class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Important Dates --}}
                <div class="bg-gradient-to-br from-teal-50 to-white rounded-2xl p-6 border border-teal-100">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">{{ __('welcome.info.tanggal_penting') }}</h3>
                    </div>
                    @if($activeConference && $activeConference->importantDates->count())
                    <ul class="space-y-2.5">
                        @foreach($activeConference->importantDates->sortBy('sort_order') as $date)
                        <li class="flex items-center justify-between text-sm {{ $date->is_past ? 'opacity-40 line-through' : '' }}">
                            <span class="text-gray-600">{{ $date->title }}</span>
                            <span class="text-teal-700 font-semibold text-xs bg-teal-50 px-2 py-0.5 rounded-full">{{ $date->date?->translatedFormat('d M Y') ?? '-' }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-sm text-gray-400 italic">{{ __('welcome.info.tanggal_penting_empty') }}</p>
                    @endif
                </div>

                {{-- Publication Info --}}
                <div class="bg-gradient-to-br from-emerald-50 to-white rounded-2xl p-6 border border-emerald-100">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">{{ __('welcome.info.publikasi_prosiding') }}</h3>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($publicationInfo)) !!}</p>
                </div>

                {{-- Selected Papers --}}
                <div class="bg-gradient-to-br from-cyan-50 to-white rounded-2xl p-6 border border-cyan-100">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-cyan-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">{{ __('welcome.info.makalah_terpilih') }}</h3>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($selectedPapersInfo)) !!}</p>
                </div>
            </div>
        </div>
    </section>
