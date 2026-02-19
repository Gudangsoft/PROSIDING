    {{-- ════════════════════════════════════════════════════
         COMMITTEES — Simple list cards
    ════════════════════════════════════════════════════ --}}
    @if($activeConference && $activeConference->committees->count())
    <section class="py-16 bg-gray-50/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12">
                <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full mb-3 uppercase tracking-wider">{{ __('welcome.committees.badge') }}</span>
                <h2 class="text-3xl font-extrabold text-gray-900">{{ __('welcome.committees.title') }}</h2>
            </div>
            @php $committeeGroups = $activeConference->committees->sortBy('sort_order')->groupBy('type'); @endphp
            <div class="grid grid-cols-1 md:grid-cols-{{ min($committeeGroups->count(), 3) }} gap-6">
                @foreach($committeeGroups as $type => $members)
                <div class="bg-white rounded-2xl border p-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        {{ \App\Models\Committee::TYPE_LABELS[$type] ?? ucfirst($type) }}
                    </h3>
                    <ul class="space-y-2.5">
                        @foreach($members as $member)
                        <li class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-teal-300 to-emerald-400 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $member->name }}{{ $member->title ? ', ' . $member->title : '' }}</p>
                                @if($member->institution || $member->role)
                                <p class="text-xs text-gray-400 truncate">{{ $member->role ? $member->role . ' — ' : '' }}{{ $member->institution }}</p>
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
