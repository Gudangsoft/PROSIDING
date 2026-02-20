    {{-- ═══════════════════════════════════════════════════════════════════
         SPEAKERS — Grouped by type: Opening Speech, Keynote, Narasumber, Moderator & Host
    ═══════════════════════════════════════════════════════════════════ --}}
    @if($activeConference && $activeConference->isSectionVisible('speakers') && $activeConference->keynoteSpeakers->count())
    <section id="speakers" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-wider">{{ __('welcome.speakers.badge') }}</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">{{ __('welcome.speakers.title') }}</h2>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">{{ __('welcome.speakers.description') }}</p>
            </div>

            @php
                $speakerTypes = \App\Models\KeynoteSpeaker::TYPE_LABELS;
                $typeColors = [
                    'opening_speech' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'badge' => 'bg-purple-100 text-purple-700', 'ring' => 'border-purple-200 group-hover:border-purple-400', 'gradient' => 'from-purple-400 to-purple-600', 'text' => 'text-purple-600'],
                    'keynote_speaker' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'badge' => 'bg-blue-100 text-blue-700', 'ring' => 'border-blue-200 group-hover:border-blue-400', 'gradient' => 'from-blue-400 to-indigo-500', 'text' => 'text-blue-600'],
                    'narasumber' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'badge' => 'bg-green-100 text-green-700', 'ring' => 'border-green-200 group-hover:border-green-400', 'gradient' => 'from-green-400 to-emerald-500', 'text' => 'text-green-600'],
                    'moderator_host' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'badge' => 'bg-orange-100 text-orange-700', 'ring' => 'border-orange-200 group-hover:border-orange-400', 'gradient' => 'from-orange-400 to-amber-500', 'text' => 'text-orange-600'],
                ];
                $typeIcons = \App\Models\KeynoteSpeaker::TYPE_ICONS;
                $allSpeakers = $activeConference->keynoteSpeakers->sortBy('sort_order');
            @endphp

            @foreach($speakerTypes as $typeKey => $typeLabel)
                @php
                    $filtered = $allSpeakers->where('type', $typeKey);
                    $colors = $typeColors[$typeKey] ?? $typeColors['keynote_speaker'];
                    $icon = $typeIcons[$typeKey] ?? '';
                    $colCount = min($filtered->count(), 4);
                @endphp

                @if($activeConference->isSpeakerTypeVisible($typeKey) && $filtered->count())
                <div class="mb-16 last:mb-0">
                    {{-- Section Header --}}
                    <div class="flex items-center justify-center gap-3 mb-8">
                        <div class="h-px flex-1 max-w-[80px] bg-gradient-to-r from-transparent {{ str_replace('bg-', 'to-', explode(' ', $colors['badge'])[0]) }}"></div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 {{ $colors['badge'] }} text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                                {{ $typeLabel }}
                            </span>
                        </div>
                        <div class="h-px flex-1 max-w-[80px] bg-gradient-to-l from-transparent {{ str_replace('bg-', 'to-', explode(' ', $colors['badge'])[0]) }}"></div>
                    </div>

                    {{-- Speaker Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $colCount }} gap-8 max-w-5xl mx-auto">
                        @foreach($filtered as $speaker)
                        <div class="group text-center">
                            <div class="relative w-36 h-36 mx-auto mb-5">
                                @if($speaker->photo)
                                <img src="{{ asset('storage/' . $speaker->photo) }}" alt="{{ $speaker->name }}"
                                     class="w-36 h-36 rounded-full object-cover border-4 {{ $colors['ring'] }} transition shadow-lg">
                                @else
                                <div class="w-36 h-36 rounded-full bg-gradient-to-br {{ $colors['gradient'] }} flex items-center justify-center border-4 {{ $colors['ring'] }} transition shadow-lg">
                                    <span class="text-4xl font-bold text-white">{{ strtoupper(substr($speaker->name, 0, 1)) }}</span>
                                </div>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $speaker->name }}</h3>
                            @if($speaker->title)
                            <p class="text-sm {{ $colors['text'] }} font-medium">{{ $speaker->title }}</p>
                            @endif
                            @if($speaker->institution)
                            <p class="text-sm text-gray-500 mt-0.5">{{ $speaker->institution }}</p>
                            @endif
                            @if($speaker->topic)
                            <div class="mt-3 inline-block bg-gray-100 text-gray-600 text-xs font-medium px-3 py-1.5 rounded-full max-w-xs">
                                <svg class="w-3 h-3 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                                {{ $speaker->topic }}
                            </div>
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
