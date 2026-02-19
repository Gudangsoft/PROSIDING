<div class="p-6 max-w-7xl mx-auto" x-data="{
    tab: @entangle('activeTab'),
    showSaveAs: @entangle('showSaveAsModal'),
}">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengaturan Tema</h1>
            <p class="text-sm text-gray-500 mt-1">Kustomisasi warna, tipografi, dan layout website — simpan sebagai preset</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

        {{-- ═══════════════════════════════════════════════════════
             LEFT: Preset List
        ═══════════════════════════════════════════════════════ --}}
        <div class="xl:col-span-1 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="px-4 py-3 border-b bg-gray-50 rounded-t-xl">
                    <h2 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        Daftar Preset
                    </h2>
                </div>
                <div class="p-3 space-y-2 max-h-[500px] overflow-y-auto">
                    @forelse($presets as $preset)
                    <div class="group relative p-3 rounded-lg border transition cursor-pointer
                        {{ $editingPresetId === $preset->id ? 'border-blue-400 bg-blue-50 ring-2 ring-blue-200' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}"
                        wire:click="loadPreset({{ $preset->id }})">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $preset->name }}</h3>
                                    @if($preset->is_active)
                                    <span class="bg-green-100 text-green-700 text-[10px] px-1.5 py-0.5 rounded-full font-bold uppercase">Aktif</span>
                                    @endif
                                    @if($preset->is_default)
                                    <span class="bg-gray-100 text-gray-500 text-[10px] px-1.5 py-0.5 rounded-full font-bold uppercase">Bawaan</span>
                                    @endif
                                </div>
                                @if($preset->description)
                                <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $preset->description }}</p>
                                @endif
                                @if($preset->linked_template)
                                <span class="inline-flex items-center gap-0.5 text-[10px] text-blue-600 mt-0.5">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/></svg>
                                    {{ ucfirst($preset->linked_template) }}
                                </span>
                                @endif
                            </div>
                            {{-- Color dots --}}
                            <div class="flex gap-0.5 shrink-0">
                                <span class="w-3 h-3 rounded-full border border-white shadow-sm" style="background: {{ $preset->primary_color }}"></span>
                                <span class="w-3 h-3 rounded-full border border-white shadow-sm" style="background: {{ $preset->secondary_color }}"></span>
                                <span class="w-3 h-3 rounded-full border border-white shadow-sm" style="background: {{ $preset->accent_color }}"></span>
                            </div>
                        </div>
                        {{-- Hover actions --}}
                        <div class="absolute top-1 right-1 hidden group-hover:flex items-center gap-0.5 bg-white rounded-lg shadow-sm border p-0.5">
                            @if(!$preset->is_active)
                            <button wire:click.stop="activatePreset({{ $preset->id }})" title="Aktifkan" class="p-1 rounded hover:bg-green-50 text-green-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                            @endif
                            <button wire:click.stop="duplicatePreset({{ $preset->id }})" title="Duplikasi" class="p-1 rounded hover:bg-blue-50 text-blue-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                            @if(!$preset->is_default)
                            <button wire:click.stop="deletePreset({{ $preset->id }})" wire:confirm="Hapus tema '{{ $preset->name }}'?" title="Hapus" class="p-1 rounded hover:bg-red-50 text-red-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-gray-400 text-sm">
                        <p>Belum ada preset.</p>
                        <p class="text-xs">Klik "Simpan" untuk membuat preset pertama.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════
             CENTER: Editor
        ═══════════════════════════════════════════════════════ --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Preset Name & Template --}}
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tema <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="presetName" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Biru Professional">
                        @error('presetName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <input type="text" wire:model="presetDescription" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Deskripsi singkat tema">
                    </div>
                </div>
                {{-- Template folder info --}}
                <div class="mt-3 pt-3 border-t flex items-center justify-between">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        Template folder:
                        <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-700 font-mono text-[11px]">templates/{{ $linkedTemplate ?: Str::slug($presetName ?: 'nama-tema') }}/</code>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Aktif: <strong class="text-gray-700">{{ ucfirst($currentActiveTemplate) }}</strong>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 mt-1">Folder template dibuat otomatis saat disimpan berdasarkan nama tema</p>
            </div>

            {{-- Tabs --}}
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="border-b px-2 bg-gray-50 flex gap-0 overflow-x-auto">
                    @foreach(['colors' => 'Warna', 'typography' => 'Tipografi & UI', 'layout' => 'Layout', 'sections' => 'Seksi', 'advanced' => 'Lanjutan'] as $tabKey => $tabLabel)
                    <button @click="tab = '{{ $tabKey }}'" type="button"
                            :class="tab === '{{ $tabKey }}' ? 'border-blue-600 text-blue-700 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap -mb-px">
                        {{ $tabLabel }}
                    </button>
                    @endforeach
                </div>

                <div class="p-6">
                    {{-- ── TAB: Colors ── --}}
                    <div x-show="tab === 'colors'" x-transition>
                        <h3 class="text-base font-semibold text-gray-800 mb-1">Skema Warna</h3>
                        <p class="text-xs text-gray-400 mb-5">Atur warna untuk admin panel dan halaman publik</p>

                        {{-- Admin Colors --}}
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Admin Panel
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                            @foreach([
                                'primary_color' => 'Warna Utama',
                                'secondary_color' => 'Warna Sekunder',
                                'accent_color' => 'Warna Aksen',
                                'sidebar_bg' => 'BG Sidebar',
                                'sidebar_text' => 'Teks Sidebar',
                                'header_bg' => 'BG Header',
                                'body_bg' => 'BG Body',
                            ] as $field => $label)
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">{{ $label }}</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="color" wire:model.live="form.{{ $field }}" class="w-8 h-8 rounded border cursor-pointer shrink-0 p-0.5">
                                    <input type="text" wire:model.live="form.{{ $field }}" class="flex-1 border-gray-300 rounded-lg text-xs font-mono focus:ring-blue-500 focus:border-blue-500" maxlength="9">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Public Colors --}}
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 bg-teal-500 rounded-full"></span> Halaman Publik
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach([
                                'nav_bg' => 'BG Navbar',
                                'nav_text' => 'Teks Navbar',
                                'hero_bg' => 'BG Hero',
                                'hero_text' => 'Teks Hero',
                                'link_color' => 'Warna Link',
                                'link_hover_color' => 'Link Hover',
                                'button_bg' => 'BG Tombol',
                                'button_text' => 'Teks Tombol',
                                'card_bg' => 'BG Kartu',
                                'card_border' => 'Border Kartu',
                                'section_alt_bg' => 'BG Seksi Alt',
                                'footer_bg' => 'BG Footer',
                                'footer_text_color' => 'Teks Footer',
                            ] as $field => $label)
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">{{ $label }}</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="color" wire:model.live="form.{{ $field }}" class="w-8 h-8 rounded border cursor-pointer shrink-0 p-0.5">
                                    <input type="text" wire:model.live="form.{{ $field }}" class="flex-1 border-gray-300 rounded-lg text-xs font-mono focus:ring-blue-500 focus:border-blue-500" maxlength="9">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── TAB: Typography ── --}}
                    <div x-show="tab === 'typography'" x-cloak x-transition>
                        <h3 class="text-base font-semibold text-gray-800 mb-1">Tipografi & UI</h3>
                        <p class="text-xs text-gray-400 mb-5">Font, sudut elemen, dan gaya bayangan</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Font Utama</label>
                                <select wire:model.live="form.font_family" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($fontOptions as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Font Heading</label>
                                <select wire:model.live="form.heading_font" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($fontOptions as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Font Base</label>
                                <div class="flex items-center gap-3">
                                    <input type="range" wire:model.live="form.font_size_base" min="12" max="20" step="1" class="flex-1">
                                    <span class="text-sm font-mono text-gray-600 w-12 text-right">{{ $form['font_size_base'] ?? 16 }}px</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Border Radius</label>
                                <select wire:model.live="form.border_radius" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($borderRadiusOptions as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gaya Shadow</label>
                                <select wire:model.live="form.shadow_style" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($shadowOptions as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Font Preview --}}
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                            <p class="text-xs text-gray-400 mb-2">Preview Font:</p>
                            <p class="text-lg" style="font-family: '{{ $form['font_family'] ?? 'Inter' }}', sans-serif; font-size: {{ $form['font_size_base'] ?? 16 }}px;">
                                Contoh teks dengan font <strong style="font-family: '{{ $form['heading_font'] ?? 'Inter' }}', sans-serif;">{{ $form['font_family'] ?? 'Inter' }}</strong>
                            </p>
                            <div class="flex gap-2 mt-3">
                                <div class="px-3 py-1 bg-white border text-xs" style="border-radius: {{ $form['border_radius'] ?? 8 }}px;">
                                    Border Radius: {{ $form['border_radius'] ?? 8 }}px
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB: Layout ── --}}
                    <div x-show="tab === 'layout'" x-cloak x-transition>
                        <h3 class="text-base font-semibold text-gray-800 mb-1">Pengaturan Layout</h3>
                        <p class="text-xs text-gray-400 mb-5">Gaya navbar, hero, footer, kartu, dan lebar kontainer</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gaya Navbar</label>
                                <select wire:model.live="form.navbar_style" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($navbarStyles as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gaya Hero Section</label>
                                <select wire:model.live="form.hero_style" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($heroStyles as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gaya Footer</label>
                                <select wire:model.live="form.footer_style" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($footerStyles as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gaya Kartu</label>
                                <select wire:model.live="form.card_style" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($cardStyles as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lebar Kontainer</label>
                                <select wire:model.live="form.container_width" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($containerWidthOptions as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Layout Visual Preview --}}
                        <div class="mt-6 border rounded-xl overflow-hidden bg-gray-100">
                            <p class="px-3 py-2 bg-gray-50 text-xs text-gray-400 border-b">Preview Layout</p>
                            <div class="p-4">
                                {{-- Mini navbar --}}
                                <div class="h-6 rounded-t-lg mb-0.5 flex items-center px-2 gap-1"
                                    style="background-color: {{ $form['nav_bg'] ?? '#ffffff' }}; border: 1px solid {{ $form['card_border'] ?? '#e5e7eb' }};">
                                    <div class="w-3 h-3 rounded" style="background: {{ $form['primary_color'] ?? '#2563eb' }};"></div>
                                    <div class="h-1.5 rounded bg-gray-300 w-8 ml-1"></div>
                                    <div class="h-1.5 rounded bg-gray-200 w-6 ml-auto"></div>
                                    <div class="h-1.5 rounded bg-gray-200 w-6 ml-1"></div>
                                </div>
                                {{-- Mini hero --}}
                                <div class="h-16 rounded flex items-center justify-center mb-1"
                                    style="background: {{ ($form['hero_style'] ?? 'gradient') === 'gradient' ? 'linear-gradient(135deg, '.($form['hero_bg'] ?? '#1e40af').','.($form['secondary_color'] ?? '#4f46e5').')' : ($form['hero_bg'] ?? '#1e40af') }};">
                                    <div class="text-center">
                                        <div class="h-2 rounded bg-white/80 w-20 mx-auto mb-1"></div>
                                        <div class="h-1.5 rounded bg-white/50 w-14 mx-auto"></div>
                                    </div>
                                </div>
                                {{-- Mini content area --}}
                                <div class="p-2 rounded" style="background: {{ $form['section_alt_bg'] ?? '#f9fafb' }};">
                                    <div class="flex gap-1.5">
                                        @for($i = 0; $i < 3; $i++)
                                        <div class="flex-1 p-1.5"
                                            style="background: {{ $form['card_bg'] ?? '#ffffff' }}; border: 1px solid {{ $form['card_border'] ?? '#e5e7eb' }}; border-radius: {{ $form['border_radius'] ?? 8 }}px;">
                                            <div class="h-3 rounded-sm mb-1" style="background: {{ $form['primary_color'] ?? '#2563eb' }}; opacity: 0.12;"></div>
                                            <div class="h-1 rounded bg-gray-200 w-3/4"></div>
                                        </div>
                                        @endfor
                                    </div>
                                    <div class="mt-1 flex justify-center gap-1">
                                        <span class="px-2 py-0.5 text-[7px] font-bold" style="background: {{ $form['button_bg'] ?? '#0d9488' }}; color: {{ $form['button_text'] ?? '#ffffff' }}; border-radius: {{ $form['border_radius'] ?? 8 }}px;">Button</span>
                                        <span class="px-2 py-0.5 text-[7px] font-bold border" style="color: {{ $form['link_color'] ?? '#0d9488' }}; border-color: {{ $form['link_color'] ?? '#0d9488' }}; border-radius: {{ $form['border_radius'] ?? 8 }}px;">Link</span>
                                    </div>
                                </div>
                                {{-- Mini footer --}}
                                <div class="h-5 rounded-b-lg flex items-center justify-center mt-0.5"
                                    style="background: {{ $form['footer_bg'] ?? '#1f2937' }};">
                                    <div class="h-1.5 rounded w-12" style="background: {{ $form['footer_text_color'] ?? '#9ca3af' }}; opacity: 0.5;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB: Sections ── --}}
                    <div x-show="tab === 'sections'" x-cloak x-transition>
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-base font-semibold text-gray-800">Tata Letak Seksi</h3>
                            <button wire:click="resetSections" wire:confirm="Reset urutan seksi ke default?" type="button"
                                    class="text-xs text-gray-400 hover:text-gray-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Reset
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mb-4">Atur urutan dan visibilitas seksi halaman utama. Gunakan tombol panah atau drag untuk mengubah urutan.</p>

                        <div class="space-y-1" x-data="{
                            dragging: null,
                            dragOver: null,
                            startDrag(e, idx) {
                                this.dragging = idx;
                                e.dataTransfer.effectAllowed = 'move';
                                e.dataTransfer.setData('text/plain', idx);
                            },
                            onDragOver(e, idx) {
                                e.preventDefault();
                                this.dragOver = idx;
                            },
                            onDrop(e, idx) {
                                e.preventDefault();
                                const from = this.dragging;
                                const to = idx;
                                if (from !== null && from !== to) {
                                    // Build new order array
                                    let items = @js(collect($sectionsConfig)->pluck('id')->toArray());
                                    const moved = items.splice(from, 1)[0];
                                    items.splice(to, 0, moved);
                                    $wire.updateSectionOrder(items);
                                }
                                this.dragging = null;
                                this.dragOver = null;
                            },
                            onDragEnd() {
                                this.dragging = null;
                                this.dragOver = null;
                            }
                        }">
                            @foreach($sectionsConfig as $index => $section)
                            <div draggable="true"
                                 x-on:dragstart="startDrag($event, {{ $index }})"
                                 x-on:dragover="onDragOver($event, {{ $index }})"
                                 x-on:drop="onDrop($event, {{ $index }})"
                                 x-on:dragend="onDragEnd()"
                                 :class="{
                                     'border-blue-400 bg-blue-50 shadow-sm': dragOver === {{ $index }},
                                     'opacity-50': dragging === {{ $index }},
                                     'border-gray-200': dragOver !== {{ $index }}
                                 }"
                                 class="flex items-center gap-3 p-3 bg-white border rounded-lg transition-all cursor-grab active:cursor-grabbing group">
                                {{-- Drag handle --}}
                                <div class="text-gray-300 group-hover:text-gray-400 shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 2a2 2 0 10.001 4.001A2 2 0 007 2zm0 6a2 2 0 10.001 4.001A2 2 0 007 8zm0 6a2 2 0 10.001 4.001A2 2 0 007 14zm6-8a2 2 0 10-.001-4.001A2 2 0 0013 6zm0 2a2 2 0 10.001 4.001A2 2 0 0013 8zm0 6a2 2 0 10.001 4.001A2 2 0 0013 14z"/>
                                    </svg>
                                </div>

                                {{-- Order number --}}
                                <span class="w-6 h-6 flex items-center justify-center rounded-full text-[10px] font-bold shrink-0
                                    {{ $section['visible'] ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }}">
                                    {{ $index + 1 }}
                                </span>

                                {{-- Section info --}}
                                <div class="flex-1 min-w-0">
                                    <span class="text-sm font-medium {{ $section['visible'] ? 'text-gray-800' : 'text-gray-400 line-through' }}">
                                        {{ $sectionLabels[$section['id']] ?? ucfirst(str_replace('_', ' ', $section['id'])) }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 ml-1.5 font-mono">{{ $section['id'] }}</span>
                                </div>

                                {{-- Up/Down buttons --}}
                                <div class="flex items-center gap-0.5 shrink-0 opacity-0 group-hover:opacity-100 transition">
                                    @if($index > 0)
                                    <button wire:click="moveSectionUp('{{ $section['id'] }}')" type="button"
                                            class="p-1 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-600" title="Naik">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    </button>
                                    @else
                                    <span class="p-1 w-5.5"></span>
                                    @endif
                                    @if($index < count($sectionsConfig) - 1)
                                    <button wire:click="moveSectionDown('{{ $section['id'] }}')" type="button"
                                            class="p-1 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-600" title="Turun">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    @else
                                    <span class="p-1 w-5.5"></span>
                                    @endif
                                </div>

                                {{-- Toggle visibility --}}
                                <button wire:click="toggleSectionVisibility('{{ $section['id'] }}')" type="button"
                                        class="relative inline-flex items-center h-5 w-9 rounded-full transition-colors shrink-0
                                            {{ $section['visible'] ? 'bg-blue-600' : 'bg-gray-300' }}"
                                        title="{{ $section['visible'] ? 'Sembunyikan' : 'Tampilkan' }}">
                                    <span class="inline-block w-3.5 h-3.5 transform bg-white rounded-full shadow transition-transform
                                        {{ $section['visible'] ? 'translate-x-4' : 'translate-x-1' }}"></span>
                                </button>
                            </div>
                            @endforeach
                        </div>

                        {{-- Section count info --}}
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg border flex items-center justify-between">
                            <div class="text-xs text-gray-500">
                                <span class="font-medium text-gray-700">{{ collect($sectionsConfig)->where('visible', true)->count() }}</span>
                                dari {{ count($sectionsConfig) }} seksi aktif
                            </div>
                            <div class="flex items-center gap-3 text-[10px] text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 2a2 2 0 10.001 4.001A2 2 0 007 2zm0 6a2 2 0 10.001 4.001A2 2 0 007 8zm0 6a2 2 0 10.001 4.001A2 2 0 007 14zm6-8a2 2 0 10-.001-4.001A2 2 0 0013 6zm0 2a2 2 0 10.001 4.001A2 2 0 0013 8zm0 6a2 2 0 10.001 4.001A2 2 0 0013 14z"/>
                                    </svg>
                                    Drag untuk urut
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    Panah untuk geser
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="w-3 h-2 rounded-full bg-blue-600 inline-block"></span>
                                    Toggle visibilitas
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB: Advanced ── --}}
                    <div x-show="tab === 'advanced'" x-cloak x-transition>
                        <h3 class="text-base font-semibold text-gray-800 mb-1">Pengaturan Lanjutan</h3>
                        <p class="text-xs text-gray-400 mb-5">Background login & CSS kustom</p>

                        {{-- Login Background --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Background Halaman Login</label>
                            <div class="flex items-start gap-5">
                                <div class="w-40 h-24 bg-gray-100 rounded-lg overflow-hidden border flex items-center justify-center shrink-0">
                                    @if($loginBgImage)
                                        <img src="{{ $loginBgImage->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @elseif($form['login_bg_image'] ?? false)
                                        <img src="{{ asset('storage/' . $form['login_bg_image']) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <input type="file" wire:model="loginBgImage" accept="image/*" class="text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700">
                                    <p class="text-xs text-gray-400 mt-1">Ukuran rekomendasi: 1920x1080 px</p>
                                </div>
                            </div>
                        </div>

                        {{-- Custom CSS --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Custom CSS</label>
                            <textarea wire:model="form.custom_css" rows="12" class="w-full border-gray-300 rounded-lg text-sm font-mono focus:ring-blue-500 focus:border-blue-500" placeholder="/* Tulis CSS custom Anda di sini */
.my-class {
    color: var(--theme-primary-color);
}"></textarea>
                            <p class="text-xs text-gray-400 mt-1">CSS ini akan di-inject di semua halaman. Gunakan <code class="bg-gray-100 px-1 rounded">var(--theme-*)</code> untuk referensi variabel.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════
             RIGHT: Preview + Actions
        ═══════════════════════════════════════════════════════ --}}
        <div class="xl:col-span-1 space-y-5">
            {{-- Live Preview --}}
            <div class="bg-white rounded-xl shadow-sm border p-5 sticky top-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview Admin
                </h3>
                {{-- Admin Mini Preview --}}
                <div class="border rounded-lg overflow-hidden" style="font-family: '{{ $form['font_family'] ?? 'Inter' }}', sans-serif;">
                    <div class="h-7 flex items-center px-2" style="background-color: {{ $form['header_bg'] ?? '#ffffff' }}; border-bottom: 1px solid {{ $form['card_border'] ?? '#e5e7eb' }};">
                        <div class="w-4 h-4 rounded" style="background-color: {{ $form['primary_color'] ?? '#2563eb' }};"></div>
                        <span class="ml-1.5 text-[9px] font-bold" style="color: {{ $form['sidebar_text'] ?? '#374151' }};">Admin</span>
                        <div class="ml-auto flex gap-1">
                            <div class="w-4 h-4 rounded-full bg-gray-200"></div>
                        </div>
                    </div>
                    <div class="flex" style="height: 140px;">
                        <div class="w-16 p-1.5 space-y-1 border-r" style="background-color: {{ $form['sidebar_bg'] ?? '#ffffff' }}; border-color: {{ $form['card_border'] ?? '#e5e7eb' }};">
                            <div class="h-2 rounded" style="background-color: {{ $form['primary_color'] ?? '#2563eb' }}; opacity: 0.2; width: 80%;"></div>
                            <div class="h-2 rounded" style="background-color: {{ $form['primary_color'] ?? '#2563eb' }}; width: 100%;"></div>
                            <div class="h-2 rounded" style="background-color: {{ $form['sidebar_text'] ?? '#374151' }}; opacity: 0.3; width: 70%;"></div>
                            <div class="h-2 rounded" style="background-color: {{ $form['sidebar_text'] ?? '#374151' }}; opacity: 0.3; width: 55%;"></div>
                        </div>
                        <div class="flex-1 p-2" style="background-color: {{ $form['body_bg'] ?? '#f3f4f6' }};">
                            <div class="bg-white rounded p-1.5 mb-1.5" style="border-radius: {{ $form['border_radius'] ?? '8' }}px; border: 1px solid {{ $form['card_border'] ?? '#e5e7eb' }};">
                                <div class="h-1.5 rounded bg-gray-200 w-2/3 mb-1"></div>
                                <div class="h-1.5 rounded bg-gray-100 w-1/2"></div>
                            </div>
                            <div class="flex gap-1">
                                <div class="flex-1 p-1.5 bg-white rounded" style="border-radius: {{ $form['border_radius'] ?? '8' }}px; border: 1px solid {{ $form['card_border'] ?? '#e5e7eb' }};">
                                    <div class="h-3 rounded mb-0.5" style="background-color: {{ $form['primary_color'] ?? '#2563eb' }}; opacity: 0.12;"></div>
                                    <div class="h-1 rounded bg-gray-100 w-2/3"></div>
                                </div>
                                <div class="flex-1 p-1.5 bg-white rounded" style="border-radius: {{ $form['border_radius'] ?? '8' }}px; border: 1px solid {{ $form['card_border'] ?? '#e5e7eb' }};">
                                    <div class="h-3 rounded mb-0.5" style="background-color: {{ $form['secondary_color'] ?? '#4f46e5' }}; opacity: 0.12;"></div>
                                    <div class="h-1 rounded bg-gray-100 w-2/3"></div>
                                </div>
                            </div>
                            <div class="mt-1.5 flex gap-1">
                                <span class="px-2 py-0.5 text-white text-[7px] font-bold" style="background-color: {{ $form['primary_color'] ?? '#2563eb' }}; border-radius: {{ $form['border_radius'] ?? '8' }}px;">Save</span>
                                <span class="px-2 py-0.5 text-white text-[7px] font-bold" style="background-color: {{ $form['accent_color'] ?? '#0891b2' }}; border-radius: {{ $form['border_radius'] ?? '8' }}px;">Accent</span>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-[10px] text-gray-400 mt-3 text-center">Warna aktual mungkin sedikit berbeda</p>

                {{-- Color Palette Summary --}}
                <div class="mt-4 pt-4 border-t">
                    <p class="text-[10px] text-gray-400 mb-2 uppercase tracking-wider font-bold">Palet Warna</p>
                    <div class="flex flex-wrap gap-1">
                        @foreach(['primary_color','secondary_color','accent_color','hero_bg','button_bg','link_color','footer_bg'] as $c)
                        <div class="w-6 h-6 rounded-md border border-white shadow-sm" style="background: {{ $form[$c] ?? '#ccc' }}" title="{{ $c }}"></div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm border p-5 space-y-3">
                <button wire:click="save" type="button" class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span wire:loading.remove wire:target="save">{{ $editingPresetId ? 'Simpan Perubahan' : 'Simpan Tema Baru' }}</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>

                <button wire:click="openSaveAs" type="button" class="w-full px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Simpan Sebagai Baru
                </button>

                @if($editingPresetId)
                @php $editingPreset = $presets->firstWhere('id', $editingPresetId); @endphp
                <button wire:click="activatePreset({{ $editingPresetId }})" type="button"
                        class="w-full px-4 py-2.5 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm font-medium hover:bg-green-100 transition flex items-center justify-center gap-2
                        {{ $editingPreset && $editingPreset->is_active ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $editingPreset && $editingPreset->is_active ? 'disabled' : '' }}>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $editingPreset && $editingPreset->is_active ? 'Sudah Aktif' : 'Aktifkan Tema Ini' }}
                </button>
                @endif

                <button wire:click="resetToDefaults" wire:confirm="Reset semua warna dan pengaturan ke default?" type="button"
                        class="w-full px-4 py-2.5 text-gray-500 rounded-lg text-xs font-medium hover:bg-gray-50 transition flex items-center justify-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset ke Default
                </button>
            </div>

            {{-- CSS Variables Info --}}
            <div class="bg-gray-50 rounded-xl border p-4">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-2">CSS Variables</p>
                <p class="text-[11px] text-gray-500 leading-relaxed">Template Anda bisa menggunakan variabel CSS seperti:</p>
                <code class="block text-[10px] text-blue-600 bg-white rounded p-2 mt-2 border font-mono">
                    var(--theme-primary-color)<br>
                    var(--theme-button-bg)<br>
                    var(--theme-font-family)<br>
                    var(--theme-border-radius)
                </code>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         SAVE AS MODAL
    ═══════════════════════════════════════════════════════ --}}
    <div x-show="showSaveAs" x-cloak x-transition class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div @click.outside="showSaveAs = false" class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 mx-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Simpan Sebagai Tema Baru</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tema</label>
                    <input type="text" wire:model="saveAsName" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Nama tema baru">
                    @error('saveAsName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <input type="text" wire:model="saveAsDescription" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Deskripsi singkat">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button @click="showSaveAs = false" type="button" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Batal</button>
                <button wire:click="saveAs" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    <span wire:loading.remove wire:target="saveAs">Simpan</span>
                    <span wire:loading wire:target="saveAs">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</div>
