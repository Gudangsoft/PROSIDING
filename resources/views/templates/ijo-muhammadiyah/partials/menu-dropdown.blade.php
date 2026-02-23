{{-- Recursive desktop dropdown menu partial — Oke Theme --}}
@foreach($items as $item)
    @if($item->allActiveChildren && $item->allActiveChildren->count())
        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
            <button @click="open = !open"
                class="text-sm text-gray-600 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50 transition font-medium flex items-center gap-1">
                @if($item->icon)<span class="w-4 h-4 shrink-0">{!! $item->icon !!}</span>@endif
                {{ $item->title }}
                <svg class="w-3.5 h-3.5 text-gray-400 transition" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-cloak x-transition
                 class="{{ $depth > 0 ? 'left-full top-0 ml-0.5' : 'left-0 top-full mt-1' }} absolute z-50 min-w-[200px] bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 overflow-hidden">
                @foreach($item->allActiveChildren->sortBy('sort_order') as $child)
                    @if($child->allActiveChildren && $child->allActiveChildren->count())
                        <div x-data="{ subOpen: false }" @mouseenter="subOpen = true" @mouseleave="subOpen = false" class="relative">
                            <button @click="subOpen = !subOpen"
                                class="w-full flex items-center justify-between px-4 py-2 text-sm text-gray-600 hover:text-teal-700 hover:bg-teal-50 transition">
                                <span class="flex items-center gap-2">
                                    @if($child->icon)<span class="w-4 h-4 shrink-0">{!! $child->icon !!}</span>@endif
                                    {{ $child->title }}
                                </span>
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                            <div x-show="subOpen" x-cloak x-transition
                                 class="absolute left-full top-0 ml-0.5 min-w-[190px] bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-50">
                                @foreach($child->allActiveChildren->sortBy('sort_order') as $grandchild)
                                    <a href="{{ $grandchild->url ?: '#' }}" target="{{ $grandchild->target }}"
                                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:text-teal-700 hover:bg-teal-50 transition">
                                        @if($grandchild->icon)<span class="w-4 h-4 shrink-0">{!! $grandchild->icon !!}</span>@endif
                                        {{ $grandchild->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $child->url ?: '#' }}" target="{{ $child->target }}"
                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:text-teal-700 hover:bg-teal-50 transition">
                            @if($child->icon)<span class="w-4 h-4 shrink-0">{!! $child->icon !!}</span>@endif
                            {{ $child->title }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @else
        <a href="{{ $item->url ?: '#' }}" target="{{ $item->target }}"
           class="text-sm text-gray-600 hover:text-teal-700 px-3 py-2 rounded-lg hover:bg-teal-50 transition font-medium flex items-center gap-1.5">
            @if($item->icon)<span class="w-4 h-4 shrink-0">{!! $item->icon !!}</span>@endif
            {{ $item->title }}
        </a>
    @endif
@endforeach
