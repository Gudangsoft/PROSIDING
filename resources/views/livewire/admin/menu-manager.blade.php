<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Menu</h1>
            <p class="text-sm text-gray-500 mt-1">Atur menu navigasi website dengan dukungan hingga 3 level kedalaman</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Location Tabs --}}
    <div class="flex items-center gap-1 mb-6 bg-gray-100 rounded-lg p-1 w-fit">
        <button wire:click="switchLocation('header')"
            class="px-4 py-2 text-sm font-medium rounded-md transition {{ $location === 'header' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                Header
            </span>
        </button>
        <button wire:click="switchLocation('footer')"
            class="px-4 py-2 text-sm font-medium rounded-md transition {{ $location === 'footer' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-800' }}">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13H5v-2h14v2zm0 6H5v-2h14v2zM5 7V5h14v2H5z"/></svg>
                Footer
            </span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- ── Menu Tree ── --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="flex items-center justify-between px-5 py-4 border-b">
                    <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wider">
                        Menu {{ $location === 'header' ? 'Header' : 'Footer' }}
                    </h2>
                    <button wire:click="openForm" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Tambah Menu
                    </button>
                </div>

                <div class="p-4">
                    {{-- Default Menu Items Section --}}
                    @if(count($this->defaultMenus))
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-3 px-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Menu Bawaan</h3>
                        </div>
                        <div class="space-y-0.5">
                            @foreach($this->defaultMenus as $defaultItem)
                            <div class="flex items-center gap-2 py-2 px-3 rounded-lg hover:bg-gray-50 transition {{ !($defaultMenuVisibility[$defaultItem['key']] ?? true) ? 'opacity-50' : '' }}">
                                {{-- Icon --}}
                                <div class="flex items-center gap-1 shrink-0">
                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </div>

                                {{-- Title & URL --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-800 truncate">{{ $defaultItem['label'] }}</span>
                                        <span class="px-1.5 py-0.5 bg-purple-100 text-purple-700 text-[10px] font-semibold rounded">BAWAAN</span>
                                        @if(!($defaultMenuVisibility[$defaultItem['key']] ?? true))
                                        <span class="px-1.5 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-semibold rounded">TERSEMBUNYI</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-xs text-gray-400 truncate font-mono">{{ $defaultItem['url'] }}</p>
                                        @if($defaultItem['conditional'])
                                        <span class="text-[10px] text-amber-500 italic truncate">{{ $defaultItem['conditional'] }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Toggle visibility --}}
                                <div class="flex items-center shrink-0">
                                    <button wire:click="toggleDefaultMenu('{{ $defaultItem['key'] }}')" 
                                        title="{{ ($defaultMenuVisibility[$defaultItem['key']] ?? true) ? 'Sembunyikan' : 'Tampilkan' }}"
                                        class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors {{ ($defaultMenuVisibility[$defaultItem['key']] ?? true) ? 'bg-green-500' : 'bg-gray-300' }}">
                                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform shadow-sm {{ ($defaultMenuVisibility[$defaultItem['key']] ?? true) ? 'translate-x-4' : 'translate-x-0.5' }}"></span>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Divider between default and custom --}}
                    <div class="flex items-center gap-2 mb-3 mt-4 px-2">
                        <div class="h-px bg-gray-200 flex-1"></div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-medium">Menu Kustom</span>
                        <div class="h-px bg-gray-200 flex-1"></div>
                    </div>
                    @endif

                    @if($this->menuTree->count())
                        @foreach($this->menuTree as $menu)
                            @include('livewire.admin.partials.menu-item', ['menu' => $menu, 'depth' => 0])
                        @endforeach
                    @else
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            <p class="text-sm text-gray-500">Belum ada menu untuk lokasi ini</p>
                            <button wire:click="openForm" class="mt-3 text-sm text-blue-600 hover:text-blue-700 font-medium">+ Tambah menu pertama</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Form Panel ── --}}
        <div class="lg:col-span-1">
            @if($showForm)
            <div class="bg-white rounded-xl shadow-sm border sticky top-20">
                <div class="flex items-center justify-between px-5 py-4 border-b">
                    <h3 class="text-sm font-semibold text-gray-800">
                        {{ $editingId ? 'Edit Menu' : 'Tambah Menu' }}
                    </h3>
                    <button wire:click="resetForm" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-5 space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Menu <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="title" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Beranda">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- URL --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL / Link</label>
                        <input type="text" wire:model="url" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://... atau #section atau /halaman">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika hanya sebagai induk (dropdown)</p>
                    </div>

                    {{-- Parent --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Menu Induk</label>
                        <select wire:model="parent_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">— Tanpa Induk (Root) —</option>
                            @foreach($this->availableParents as $p)
                                <option value="{{ $p->id }}">
                                    {{ str_repeat('— ', $p->depth) }}{{ $p->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Target --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                        <select wire:model="target" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="_self">Buka di tab yang sama</option>
                            <option value="_blank">Buka di tab baru</option>
                        </select>
                    </div>

                    {{-- Icon (optional SVG or class) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ikon (SVG / class)</label>
                        <textarea wire:model="icon" rows="2" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 font-mono" placeholder='<svg ...>...</svg>'></textarea>
                        <p class="text-xs text-gray-400 mt-1">Opsional. Masukkan kode SVG inline</p>
                    </div>

                    {{-- Sort Order --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input type="number" wire:model="sort_order" min="0" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- Active --}}
                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model="is_active" id="menuActive" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="menuActive" class="text-sm text-gray-700">Aktif</label>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $editingId ? 'Perbarui' : 'Simpan' }}
                        </button>
                        <button type="button" wire:click="resetForm" class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border p-6 text-center">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <p class="text-sm text-gray-500 mb-1">Pilih menu untuk diedit</p>
                <p class="text-xs text-gray-400">atau klik "Tambah Menu" untuk membuat baru</p>
            </div>
            @endif
        </div>
    </div>
</div>
