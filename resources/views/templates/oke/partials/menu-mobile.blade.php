{{-- Recursive mobile menu partial — Oke Theme --}}
@foreach($items as $item)
    @if($item->allActiveChildren && $item->allActiveChildren->count())
        <div x-data="{ expanded: false }">
            <div class="flex items-center">
                @if($item->url)
                <a href="{{ $item->url }}" target="{{ $item->target }}" @click="mobileOpen=false"
                   class="flex-1 block px-3 py-2 text-sm text-gray-600 hover:bg-teal-50 rounded-lg" style="{{ $depth > 0 ? 'padding-left: ' . (0.75 + $depth * 1) . 'rem;' : '' }}">
                    {{ $item->title }}
                </a>
                @else
                <span class="flex-1 block px-3 py-2 text-sm text-gray-500" style="{{ $depth > 0 ? 'padding-left: ' . (0.75 + $depth * 1) . 'rem;' : '' }}">
                    {{ $item->title }}
                </span>
                @endif
                <button @click="expanded = !expanded" class="p-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4 transition" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
            <div x-show="expanded" x-cloak x-collapse>
                @include(\App\Helpers\Template::view('partials.menu-mobile'), ['items' => $item->allActiveChildren->sortBy('sort_order'), 'depth' => $depth + 1])
            </div>
        </div>
    @else
        <a href="{{ $item->url ?: '#' }}" target="{{ $item->target }}" @click="mobileOpen=false"
           class="block px-3 py-2 text-sm text-gray-600 hover:bg-teal-50 rounded-lg" style="{{ $depth > 0 ? 'padding-left: ' . (0.75 + $depth * 1) . 'rem;' : '' }}">
            {{ $item->title }}
        </a>
    @endif
@endforeach
