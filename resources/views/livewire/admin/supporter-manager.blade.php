<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Supporter</h1>
            <p class="text-sm text-gray-500 mt-1">Atur logo pendukung, sponsor, dan partner yang ditampilkan di footer</p>
        </div>
        <button wire:click="openForm" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Supporter
        </button>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Filter --}}
    <div class="flex items-center gap-2 mb-6">
        <button wire:click="$set('filterType', '')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ !$filterType ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Semua</button>
        @foreach(\App\Models\Supporter::TYPE_LABELS as $key => $label)
        <button wire:click="$set('filterType', '{{ $key }}')" class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ $filterType === $key ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ $label }}</button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- List --}}
        <div class="lg:col-span-2">
            @if($supporters->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($supporters as $supporter)
                <div class="bg-white rounded-xl shadow-sm border p-4 group {{ !$supporter->is_active ? 'opacity-50' : '' }}">
                    <div class="flex items-center gap-4">
                        {{-- Logo --}}
                        <div class="w-16 h-16 rounded-xl bg-gray-50 border flex items-center justify-center overflow-hidden shrink-0">
                            @if($supporter->logo)
                                <img src="{{ asset('storage/' . $supporter->logo) }}" alt="{{ $supporter->name }}" class="max-w-full max-h-full object-contain p-1">
                            @else
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-bold text-gray-800 truncate">{{ $supporter->name }}</h3>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold mt-0.5
                                {{ $supporter->type === 'supporter' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $supporter->type === 'sponsor' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $supporter->type === 'partner' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $supporter->type === 'organizer' ? 'bg-orange-100 text-orange-700' : '' }}">
                                {{ $supporter->type_label }}
                            </span>
                            @if($supporter->url)
                            <p class="text-xs text-gray-400 truncate mt-0.5">{{ $supporter->url }}</p>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition shrink-0">
                            <button wire:click="toggleActive({{ $supporter->id }})" title="{{ $supporter->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                class="p-1.5 rounded transition {{ $supporter->is_active ? 'text-green-500 hover:bg-green-50' : 'text-gray-400 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                            <button wire:click="edit({{ $supporter->id }})" title="Edit" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="delete({{ $supporter->id }})" wire:confirm="Hapus '{{ $supporter->name }}'?" title="Hapus" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <p class="text-sm text-gray-500">Belum ada supporter</p>
                <button wire:click="openForm" class="mt-3 text-sm text-blue-600 hover:text-blue-700 font-medium">+ Tambah supporter pertama</button>
            </div>
            @endif
        </div>

        {{-- Form --}}
        <div class="lg:col-span-1">
            @if($showForm)
            <div class="bg-white rounded-xl shadow-sm border sticky top-20">
                <div class="flex items-center justify-between px-5 py-4 border-b">
                    <h3 class="text-sm font-semibold text-gray-800">{{ $editingId ? 'Edit Supporter' : 'Tambah Supporter' }}</h3>
                    <button wire:click="resetForm" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form wire:submit.prevent="save" class="p-5 space-y-4">
                    {{-- Logo Upload --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center">
                            <div class="w-20 h-20 mx-auto bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden mb-3 border">
                                @if($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" class="max-w-full max-h-full object-contain">
                                @elseif($existingLogo)
                                    <img src="{{ asset('storage/' . $existingLogo) }}" class="max-w-full max-h-full object-contain">
                                @else
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                            <label class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium cursor-pointer hover:bg-blue-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload
                                <input type="file" wire:model="logo" accept="image/*" class="sr-only">
                            </label>
                            @if($existingLogo)
                            <button type="button" wire:click="removeLogo" class="ml-2 text-xs text-red-500 hover:text-red-700">Hapus</button>
                            @endif
                            <p class="text-xs text-gray-400 mt-2">PNG/JPG/SVG. Maks 2MB</p>
                        </div>
                        @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Nama organisasi">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                        <select wire:model="type" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach(\App\Models\Supporter::TYPE_LABELS as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- URL --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Website</label>
                        <input type="url" wire:model="url" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://...">
                    </div>

                    {{-- Sort Order --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" wire:model="sort_order" min="0" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- Active --}}
                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model="is_active" id="suppActive" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="suppActive" class="text-sm text-gray-700">Aktif</label>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $editingId ? 'Perbarui' : 'Simpan' }}
                        </button>
                        <button type="button" wire:click="resetForm" class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                    </div>
                </form>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border p-6 text-center">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <p class="text-sm text-gray-500">Klik "Tambah Supporter" atau pilih untuk diedit</p>
            </div>
            @endif
        </div>
    </div>
</div>
