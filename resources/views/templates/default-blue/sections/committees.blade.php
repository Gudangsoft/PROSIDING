    {{-- ═══════════════════════════════════════════════════════════════════
         COMMITTEES — Organizing / Scientific
    ═══════════════════════════════════════════════════════════════════ --}}
    @if($activeConference && $activeConference->committees->count())
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-wider">{{ __('welcome.committees.badge') }}</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">{{ __('welcome.committees.title') }}</h2>
            </div>
            @php $committeeGroups = $activeConference->committees->sortBy('sort_order')->groupBy('type'); @endphp
            <div class="grid grid-cols-1 md:grid-cols-{{ min($committeeGroups->count(), 3) }} gap-8">
                @foreach($committeeGroups as $type => $members)
                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <h3 class="text-base font-bold text-gray-800 mb-4 pb-3 border-b flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                        {{ \App\Models\Committee::TYPE_LABELS[$type] ?? ucfirst($type) }}
                    </h3>
                    <ul class="space-y-3">
                        @foreach($members as $member)
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $member->name }}{{ $member->title ? ', ' . $member->title : '' }}</p>
                                @if($member->institution || $member->role)
                                <p class="text-xs text-gray-500 truncate">{{ $member->role ? $member->role . ' — ' : '' }}{{ $member->institution }}</p>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
