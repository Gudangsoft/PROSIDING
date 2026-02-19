<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Slider</h1>
            <p class="text-sm text-gray-500 mt-1">Slider/banner yang tampil di halaman utama</p>
        </div>
        <a href="{{ route('admin.sliders.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Slider
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    {{-- Search --}}
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari slider..." class="w-full md:w-80 border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
    </div>

    {{-- Slider Grid --}}
    @if($sliders->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($sliders as $slider)
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden group">
            {{-- Image Preview --}}
            <div class="relative h-48 bg-gray-100 overflow-hidden">
                @if($slider->image)
                <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif

                {{-- Status Badge --}}
                <div class="absolute top-3 left-3 flex gap-2">
                    <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $slider->is_active ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                        {{ $slider->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-blue-500 text-white">#{{ $slider->sort_order }}</span>
                </div>

                {{-- Overlay actions --}}
                <div class="absolute inset-0 bg-black/50 flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition">
                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="p-2 bg-white rounded-lg text-blue-600 hover:bg-blue-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <button wire:click="toggleActive({{ $slider->id }})" class="p-2 bg-white rounded-lg {{ $slider->is_active ? 'text-yellow-600 hover:bg-yellow-50' : 'text-green-600 hover:bg-green-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $slider->is_active ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/></svg>
                    </button>
                    <button wire:click="delete({{ $slider->id }})" wire:confirm="Hapus slider ini?" class="p-2 bg-white rounded-lg text-red-600 hover:bg-red-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>

            {{-- Info --}}
            <div class="p-4">
                <h3 class="font-semibold text-gray-800 text-sm">{{ $slider->title ?: '(Tanpa Judul)' }}</h3>
                @if($slider->subtitle)
                <p class="text-xs text-gray-500 mt-0.5">{{ $slider->subtitle }}</p>
                @endif
                <div class="flex items-center gap-3 mt-3 text-xs text-gray-400">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        {{ \App\Models\Slider::POSITIONS[$slider->text_position] ?? $slider->text_position }}
                    </span>
                    @if($slider->start_date || $slider->end_date)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $slider->start_date?->format('d/m/Y') ?? '...' }} – {{ $slider->end_date?->format('d/m/Y') ?? '...' }}
                    </span>
                    @endif
                    @if($slider->button_text)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        {{ $slider->button_text }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $sliders->links() }}</div>
    @else
    <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <p class="text-gray-500 mb-4">Belum ada slider.</p>
        <a href="{{ route('admin.sliders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Tambah Slider Pertama</a>
    </div>
    @endif
</div>
