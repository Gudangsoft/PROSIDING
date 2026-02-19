    {{-- ════════════════════════════════════════════════════
         SPEAKERS — Circular cards, clean
    ════════════════════════════════════════════════════ --}}
    @if($activeConference && $activeConference->keynoteSpeakers->count())
    <section id="speakers" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14">
                <span class="inline-block bg-teal-100 text-teal-700 text-xs font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-wider">{{ __('welcome.speakers.badge') }}</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">{{ __('welcome.speakers.title') }}</h2>
                <p class="text-gray-400 mt-3 max-w-xl mx-auto">{{ __('welcome.speakers.description') }}</p>
            </div>

            @php
                $speakerTypes = \App\Models\KeynoteSpeaker::TYPE_LABELS;
                $typeIcons = \App\Models\KeynoteSpeaker::TYPE_ICONS;
                $allSpeakers = $activeConference->keynoteSpeakers->sortBy('sort_order');
            @endphp

            @foreach($speakerTypes as $typeKey => $typeLabel)
                @php $filtered = $allSpeakers->where('type', $typeKey); @endphp
                @if($filtered->count())
                <div class="mb-14 last:mb-0">
                    <div class="flex items-center justify-center gap-3 mb-8">
                        <div class="h-px flex-1 max-w-[60px] bg-gradient-to-r from-transparent to-teal-200"></div>
                        <span class="text-xs font-bold text-teal-700 bg-teal-50 px-4 py-1.5 rounded-full uppercase tracking-wider border border-teal-200">{{ $typeLabel }}</span>
                        <div class="h-px flex-1 max-w-[60px] bg-gradient-to-l from-transparent to-teal-200"></div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min($filtered->count(), 4) }} gap-8 max-w-4xl mx-auto">
                        @foreach($filtered as $speaker)
                        <div class="text-center group">
                            <div class="relative w-32 h-32 mx-auto mb-4">
                                @if($speaker->photo)
                                <img src="{{ asset('storage/' . $speaker->photo) }}" alt="{{ $speaker->name }}"
                                     class="w-32 h-32 rounded-full object-cover border-4 border-teal-100 group-hover:border-teal-300 transition shadow-md">
                                @else
                                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center border-4 border-teal-100 group-hover:border-teal-300 transition shadow-md">
                                    <span class="text-3xl font-bold text-white">{{ strtoupper(substr($speaker->name, 0, 1)) }}</span>
                                </div>
                                @endif
                            </div>
                            <h3 class="font-bold text-gray-900">{{ $speaker->name }}</h3>
                            @if($speaker->title)
                            <p class="text-sm text-teal-600 font-medium">{{ $speaker->title }}</p>
                            @endif
                            @if($speaker->institution)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $speaker->institution }}</p>
                            @endif
                            @if($speaker->topic)
                            <span class="inline-block mt-2 bg-gray-100 text-gray-500 text-xs px-3 py-1 rounded-full">{{ $speaker->topic }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </section>
    @endif
