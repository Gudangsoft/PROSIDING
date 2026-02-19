{{-- Recursive menu item partial --}}
<div class="group {{ $depth > 0 ? 'ml-6 border-l-2 border-gray-100' : '' }}">
    <div class="flex items-center gap-2 py-2 px-3 rounded-lg hover:bg-gray-50 transition {{ !$menu->is_active ? 'opacity-50' : '' }}">
        {{-- Drag / depth indicator --}}
        <div class="flex items-center gap-1 shrink-0">
            @if($depth > 0)
            <div class="w-3 h-px bg-gray-200"></div>
            @endif
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
        </div>

        {{-- Icon --}}
        @if($menu->icon)
        <div class="w-5 h-5 text-gray-400 shrink-0 flex items-center justify-center">{!! $menu->icon !!}</div>
        @endif

        {{-- Title & URL --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-800 truncate">{{ $menu->title }}</span>
                @if(!$menu->is_active)
                <span class="px-1.5 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-semibold rounded">NONAKTIF</span>
                @endif
                @if($menu->target === '_blank')
                <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                @endif
            </div>
            @if($menu->url)
            <p class="text-xs text-gray-400 truncate font-mono">{{ $menu->url }}</p>
            @else
            <p class="text-xs text-gray-300 italic">Dropdown (tanpa link)</p>
            @endif
        </div>

        {{-- Level badge --}}
        <span class="px-1.5 py-0.5 text-[10px] font-bold rounded {{ $depth === 0 ? 'bg-blue-100 text-blue-700' : ($depth === 1 ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700') }}">
            L{{ $depth + 1 }}
        </span>

        {{-- Actions --}}
        <div class="flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition shrink-0">
            {{-- Move up --}}
            <button wire:click="moveUp({{ $menu->id }})" wire:loading.attr="disabled" title="Geser ke atas"
                class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
            </button>
            {{-- Move down --}}
            <button wire:click="moveDown({{ $menu->id }})" wire:loading.attr="disabled" title="Geser ke bawah"
                class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            {{-- Toggle active --}}
            <button wire:click="toggleActive({{ $menu->id }})" title="{{ $menu->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                class="p-1.5 {{ $menu->is_active ? 'text-green-500 hover:text-red-500 hover:bg-red-50' : 'text-gray-400 hover:text-green-500 hover:bg-green-50' }} rounded transition">
                @if($menu->is_active)
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                @else
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                @endif
            </button>
            {{-- Add child (if depth < 2) --}}
            @if($depth < 2)
            <button wire:click="openForm({{ $menu->id }})" title="Tambah sub-menu"
                class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </button>
            @endif
            {{-- Edit --}}
            <button wire:click="edit({{ $menu->id }})" title="Edit"
                class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            {{-- Delete --}}
            <button wire:click="delete({{ $menu->id }})" wire:confirm="Hapus menu '{{ $menu->title }}' dan semua sub-menunya?" title="Hapus"
                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>

    {{-- Recursive children --}}
    @if($menu->allChildren && $menu->allChildren->count())
        @foreach($menu->allChildren->sortBy('sort_order') as $child)
            @include('livewire.admin.partials.menu-item', ['menu' => $child, 'depth' => $depth + 1])
        @endforeach
    @endif
</div>
