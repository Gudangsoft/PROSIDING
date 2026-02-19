<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.pages') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Halaman
        </a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">{{ $isEdit ? 'Edit Halaman' : 'Buat Halaman Baru' }}</h2>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main content (2/3) --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Halaman <span class="text-red-500">*</span></label>
                        <input wire:model.live.debounce.500ms="title" type="text"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Contoh: Tentang Kami" required>
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)
                            <span class="text-xs text-gray-400 font-normal">— otomatis dari judul</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-400 shrink-0">/page/</span>
                            <input wire:model="slug" type="text"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="tentang-kami">
                        </div>
                        @error('slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan / Excerpt</label>
                        <textarea wire:model="excerpt" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
                                  placeholder="Deskripsi singkat halaman (opsional)..."></textarea>
                    </div>

                    <div x-data="{ mode: 'visual' }">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-medium text-gray-700">Konten <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-0.5">
                                <button type="button" @click="mode = 'visual'"
                                        :class="mode === 'visual' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-500'"
                                        class="px-2.5 py-1 text-xs font-medium rounded-md transition">Visual</button>
                                <button type="button" @click="mode = 'html'"
                                        :class="mode === 'html' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-500'"
                                        class="px-2.5 py-1 text-xs font-medium rounded-md transition">HTML</button>
                            </div>
                        </div>
                        {{-- Visual editor using contenteditable with hidden textarea sync --}}
                        <div x-show="mode === 'visual'" x-cloak>
                            <div x-data="{
                                content: @entangle('content'),
                                init() {
                                    this.$refs.editor.innerHTML = this.content;
                                    this.$watch('content', val => {
                                        if (this.$refs.editor.innerHTML !== val) {
                                            this.$refs.editor.innerHTML = val;
                                        }
                                    });
                                },
                                execCmd(cmd, val = null) {
                                    document.execCommand(cmd, false, val);
                                    this.$refs.editor.focus();
                                    this.sync();
                                },
                                sync() {
                                    this.content = this.$refs.editor.innerHTML;
                                }
                            }">
                                {{-- Toolbar --}}
                                <div class="flex flex-wrap gap-0.5 p-1.5 bg-gray-50 border border-b-0 border-gray-300 rounded-t-lg">
                                    <button type="button" @click="execCmd('bold')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600" title="Bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg>
                                    </button>
                                    <button type="button" @click="execCmd('italic')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600" title="Italic">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
                                    </button>
                                    <button type="button" @click="execCmd('underline')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600" title="Underline">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 3v7a6 6 0 0012 0V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg>
                                    </button>
                                    <span class="w-px h-6 bg-gray-300 mx-1 self-center"></span>
                                    <button type="button" @click="execCmd('formatBlock', '<h2>')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600 text-xs font-bold" title="Heading 2">H2</button>
                                    <button type="button" @click="execCmd('formatBlock', '<h3>')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600 text-xs font-bold" title="Heading 3">H3</button>
                                    <button type="button" @click="execCmd('formatBlock', '<p>')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600 text-xs font-bold" title="Paragraph">P</button>
                                    <span class="w-px h-6 bg-gray-300 mx-1 self-center"></span>
                                    <button type="button" @click="execCmd('insertUnorderedList')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600" title="Bullet List">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="4" cy="6" r="1" fill="currentColor"/><circle cx="4" cy="12" r="1" fill="currentColor"/><circle cx="4" cy="18" r="1" fill="currentColor"/></svg>
                                    </button>
                                    <button type="button" @click="execCmd('insertOrderedList')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600" title="Numbered List">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><text x="2" y="8" font-size="8" fill="currentColor" stroke="none">1</text><text x="2" y="14" font-size="8" fill="currentColor" stroke="none">2</text><text x="2" y="20" font-size="8" fill="currentColor" stroke="none">3</text></svg>
                                    </button>
                                    <span class="w-px h-6 bg-gray-300 mx-1 self-center"></span>
                                    <button type="button" @click="
                                        let url = prompt('Masukkan URL link:');
                                        if (url) execCmd('createLink', url);
                                    " class="p-1.5 rounded hover:bg-gray-200 text-gray-600" title="Insert Link">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                                    </button>
                                    <button type="button" @click="execCmd('unlink')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600" title="Remove Link">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/><line x1="4" y1="4" x2="20" y2="20"/></svg>
                                    </button>
                                    <button type="button" @click="execCmd('justifyLeft')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600" title="Align Left">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="18" y2="18"/></svg>
                                    </button>
                                    <button type="button" @click="execCmd('justifyCenter')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600" title="Align Center">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="5" y1="18" x2="19" y2="18"/></svg>
                                    </button>
                                    <button type="button" @click="execCmd('removeFormat')" class="p-1.5 rounded hover:bg-gray-200 text-gray-600" title="Clear Formatting">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/><line x1="4" y1="20" x2="20" y2="4" stroke-width="1.5"/></svg>
                                    </button>
                                </div>
                                {{-- Editable area --}}
                                <div x-ref="editor" @input="sync()" @blur="sync()"
                                     contenteditable="true"
                                     class="w-full min-h-[300px] px-4 py-3 border border-gray-300 rounded-b-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none prose prose-sm max-w-none"
                                     style="overflow-y: auto; max-height: 600px;">
                                </div>
                            </div>
                        </div>

                        {{-- HTML source mode --}}
                        <div x-show="mode === 'html'" x-cloak>
                            <textarea wire:model.lazy="content" rows="16"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="<h2>Judul</h2><p>Konten halaman...</p>"></textarea>
                        </div>
                        @error('content')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Sidebar (1/3) --}}
            <div class="space-y-6">
                {{-- Publish Settings --}}
                <div class="bg-white rounded-xl shadow-sm border p-5 space-y-4">
                    <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Publikasi
                    </h3>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                            @foreach(\App\Models\Page::STATUS_LABELS as $val => $lbl)
                            <option value="{{ $val }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Layout</label>
                        <select wire:model="layout" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                            @foreach($layoutOptions as $val => $lbl)
                            <option value="{{ $val }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Urutan</label>
                        <input wire:model="sort_order" type="number" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    </div>

                    <label class="flex items-center gap-2">
                        <input wire:model="is_featured" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Halaman Featured</span>
                    </label>
                </div>

                {{-- Menu Settings --}}
                <div class="bg-white rounded-xl shadow-sm border p-5 space-y-4" x-data="{ menuType: @entangle('menu_type') }">
                    <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Tampilkan di Menu
                    </h3>

                    <label class="flex items-center gap-2">
                        <input wire:model.live="show_in_menu" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Tampilkan halaman ini di menu navigasi</span>
                    </label>

                    @if($show_in_menu)
                    <div class="space-y-3 pt-1 border-t border-gray-100">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Posisi Menu</label>
                            <div class="space-y-2">
                                <label class="flex items-start gap-3 p-2.5 rounded-lg border cursor-pointer transition
                                    {{ $menu_type !== 'child' ? 'border-blue-300 bg-blue-50/50 ring-1 ring-blue-200' : 'border-gray-200 hover:border-gray-300' }}"
                                >
                                    <input wire:model.live="menu_type" type="radio" name="menu_type" value="main"
                                           class="mt-0.5 border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <span class="text-sm font-medium text-gray-800">Menu Utama</span>
                                        <p class="text-xs text-gray-500 mt-0.5">Tampil langsung di navbar sebagai menu teratas</p>
                                    </div>
                                </label>
                                <label class="flex items-start gap-3 p-2.5 rounded-lg border cursor-pointer transition
                                    {{ $menu_type === 'child' ? 'border-blue-300 bg-blue-50/50 ring-1 ring-blue-200' : 'border-gray-200 hover:border-gray-300' }}"
                                >
                                    <input wire:model.live="menu_type" type="radio" name="menu_type" value="child"
                                           class="mt-0.5 border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <span class="text-sm font-medium text-gray-800">Sub Menu</span>
                                        <p class="text-xs text-gray-500 mt-0.5">Tampil sebagai dropdown di bawah menu lain</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        @if($menu_type === 'child')
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Induk Menu (Parent)</label>
                            <select wire:model="menu_parent_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">— Pilih menu induk —</option>
                                @foreach($parentMenus as $pm)
                                <option value="{{ $pm->id }}">{{ $pm->title }}</option>
                                @endforeach
                            </select>
                            @if($parentMenus->isEmpty())
                            <p class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Belum ada menu utama. Buat menu utama terlebih dahulu di Menu Manager.
                            </p>
                            @endif
                        </div>
                        @endif

                        <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-500 flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Menu akan otomatis dibuat/diperbarui saat halaman disimpan dengan status <strong>Dipublikasi</strong>. Halaman berstatus Draf akan otomatis dihapus dari menu.</span>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Cover Image --}}
                <div class="bg-white rounded-xl shadow-sm border p-5 space-y-3">
                    <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Cover Image
                    </h3>

                    @if($cover_image)
                    <div class="relative">
                        <img src="{{ $cover_image->temporaryUrl() }}" class="w-full h-32 object-cover rounded-lg">
                        <button type="button" wire:click="$set('cover_image', null)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @elseif($existing_cover)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $existing_cover) }}" class="w-full h-32 object-cover rounded-lg">
                        <button type="button" wire:click="removeCover" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @endif
                    <input wire:model="cover_image" type="file" accept="image/*"
                           class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-blue-50 file:text-blue-700">
                    @error('cover_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- SEO --}}
                <div class="bg-white rounded-xl shadow-sm border p-5 space-y-3">
                    <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        SEO
                    </h3>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Meta Title</label>
                        <input wire:model="meta_title" type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
                               placeholder="Judul untuk mesin pencari">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Meta Description</label>
                        <textarea wire:model="meta_description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
                                  placeholder="Deskripsi untuk mesin pencari (maks 160 karakter)"></textarea>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="space-y-2">
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Buat Halaman' }}</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                    @if($isEdit && $page && $page->status === 'published')
                    <a href="{{ route('page.show', $page->slug) }}" target="_blank"
                       class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Halaman
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
