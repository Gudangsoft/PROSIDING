@php
$blockMeta = [
    'hero'       => ['icon' => '✦',  'label' => 'Hero Banner',    'color' => '#7c3aed', 'bg' => '#f5f3ff', 'desc' => 'Bagian besar teratas halaman dengan judul & background'],
    'text'       => ['icon' => '≡',  'label' => 'Blok Teks',      'color' => '#374151', 'bg' => '#f9fafb', 'desc' => 'Paragraf teks bebas untuk deskripsi atau konten'],
    'text_image' => ['icon' => '⊞',  'label' => 'Teks + Gambar',  'color' => '#0891b2', 'bg' => '#ecfeff', 'desc' => 'Layout dua kolom: teks kiri, gambar kanan'],
    'cta'        => ['icon' => '→',  'label' => 'Call to Action', 'color' => '#ea580c', 'bg' => '#fff7ed', 'desc' => 'Banner ajakan dengan tombol link mencolok'],
    'highlight'  => ['icon' => '★',  'label' => 'Highlight Box',  'color' => '#ca8a04', 'bg' => '#fefce8', 'desc' => 'Kotak info penting dengan aksen warna'],
    'divider'    => ['icon' => '—',  'label' => 'Pemisah',        'color' => '#9ca3af', 'bg' => '#f9fafb', 'desc' => 'Garis horizontal pemisah antar section'],
    'html'       => ['icon' => '</>', 'label' => 'HTML Custom',    'color' => '#dc2626', 'bg' => '#fff1f2', 'desc' => 'Kode HTML bebas, embed widget atau countdown'],
];
@endphp

<div x-data="{
    activeTab: 'editor',
    device: 'desktop',
    showGuide: false,
    dragOver: null
}" class="min-h-screen bg-gray-50/70">

    {{-- ╔══════════════════════════════════════════════════════════╗
         ║  STICKY HEADER                                          ║
         ╚══════════════════════════════════════════════════════════╝ --}}
    <div class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center gap-4 py-3">
                {{-- Brand --}}
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 bg-gradient-to-br from-violet-600 to-purple-700 rounded-xl flex items-center justify-center shadow-md shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold text-gray-900 leading-none">Page Builder</h1>
                        <p class="text-[11px] text-gray-400 mt-0.5 leading-none">Conference Landing</p>
                    </div>
                </div>

                {{-- Conference picker --}}
                <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <select wire:model.live="conferenceId" class="bg-transparent text-sm text-gray-700 font-medium outline-none pr-4 cursor-pointer min-w-[180px]">
                        <option value="0">— Pilih Konferensi —</option>
                        @foreach($conferences as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select>
                </div>

                @if($conferenceId)
                <div class="flex items-center gap-1 text-xs text-green-700 bg-green-50 border border-green-200 px-2.5 py-1.5 rounded-lg">
                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                    {{ count($blocks) }} blok • {{ count(array_filter($blocks, fn($b) => $b['visible'] ?? true)) }} aktif
                </div>
                @endif

                <div class="flex-1"></div>

                {{-- Guide button --}}
                <button @click="showGuide = !showGuide"
                    :class="showGuide ? 'bg-violet-100 text-violet-700 border-violet-300' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                    class="flex items-center gap-1.5 px-3 py-1.5 border rounded-lg text-xs font-medium transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Panduan
                </button>

                {{-- Tab switcher --}}
                <div class="flex items-center bg-gray-100 rounded-xl p-1 gap-0.5">
                    <button @click="activeTab = 'editor'"
                        :class="activeTab === 'editor' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Editor
                    </button>
                    <button @click="activeTab = 'preview'"
                        :class="activeTab === 'preview' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Preview
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-gray-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl text-sm font-medium border border-white/10">
        <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center shrink-0">
            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
        </div>
        {{ session('success') }}
    </div>
    @endif

    {{-- ╔══════════════════════════════════════════════════════════╗
         ║  PANDUAN  (collapsible)                                 ║
         ╚══════════════════════════════════════════════════════════╝ --}}
    <div x-show="showGuide" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-end="opacity-0"
        class="bg-violet-600 text-white">
        <div class="max-w-7xl mx-auto px-6 py-5">
            <div class="flex items-start justify-between gap-6 mb-5">
                <div>
                    <h3 class="text-base font-bold">Cara Menggunakan Page Builder</h3>
                    <p class="text-violet-200 text-xs mt-0.5">Buat halaman landing konferensi tanpa coding, dalam hitungan menit</p>
                </div>
                <button @click="showGuide=false" class="text-violet-300 hover:text-white transition mt-0.5 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Steps --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                @foreach([
                    ['1','Pilih Konferensi','Pilih dari dropdown di header untuk mulai mengedit halamannya.'],
                    ['2','Tambah Blok','Klik kartu blok di sidebar kiri — setiap klik tambah section baru.'],
                    ['3','Edit & Susun','Atur urutan dengan ▲▼, sembunyikan dengan 👁, edit isi dengan ✏.'],
                    ['4','Preview','Buka tab Preview untuk lihat tampilan akhir sebelum dipublikasi.'],
                ] as [$n,$t,$d])
                <div class="bg-white/10 backdrop-blur rounded-xl p-3.5 flex gap-3">
                    <div class="w-6 h-6 bg-white/20 rounded-full text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $n }}</div>
                    <div>
                        <p class="text-sm font-semibold leading-tight">{{ $t }}</p>
                        <p class="text-[11px] text-violet-200 mt-1 leading-relaxed">{{ $d }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Block types --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-2">
                @foreach($blockMeta as $type => $m)
                <div class="bg-white/10 rounded-xl p-2.5 flex gap-2.5 items-start">
                    <span class="font-mono font-bold text-sm shrink-0 mt-0.5 opacity-80">{{ $m['icon'] }}</span>
                    <div>
                        <p class="text-xs font-bold leading-tight">{{ $m['label'] }}</p>
                        <p class="text-[10px] text-violet-200 mt-0.5 leading-tight">{{ $m['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ╔══════════════════════════════════════════════════════════╗
         ║  MAIN BODY                                              ║
         ╚══════════════════════════════════════════════════════════╝ --}}
    <div class="max-w-7xl mx-auto px-6 py-6 flex gap-5 items-start">

        {{-- ───────────── LEFT SIDEBAR (sticky) ───────────── --}}
        <div class="w-56 shrink-0 sticky top-[61px]" style="max-height:calc(100vh - 80px); overflow-y:auto">
            <div class="space-y-3 pb-6">

                {{-- Title --}}
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Tambah Blok</p>
                </div>

                {{-- Block palette --}}
                <div class="space-y-1.5">
                    @foreach($blockMeta as $type => $m)
                    <button wire:click="openAddBlock('{{ $type }}')"
                        @if(!$conferenceId) disabled @endif
                        class="w-full text-left group relative flex items-center gap-3 px-3 py-2.5 bg-white border border-gray-200 rounded-xl hover:border-gray-300 hover:shadow-sm active:scale-[0.98] transition-all duration-150 {{ !$conferenceId ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 font-bold text-sm transition-transform group-hover:scale-110"
                            style="background:{{ $m['bg'] }}; color:{{ $m['color'] }}">
                            {{ $m['icon'] }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-gray-800 leading-tight">{{ $m['label'] }}</p>
                            <p class="text-[10px] text-gray-400 leading-tight truncate mt-0.5">{{ Str::limit($m['desc'], 32) }}</p>
                        </div>
                        <svg class="w-3.5 h-3.5 text-gray-300 shrink-0 group-hover:text-gray-500 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                    @endforeach
                </div>

                @if(!$conferenceId)
                <p class="text-[10px] text-center text-gray-400 px-2">Pilih konferensi di header untuk mulai menambahkan blok</p>
                @endif

                {{-- Divider --}}
                <div class="border-t border-gray-100 pt-3">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1 mb-2">Tips</p>
                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 space-y-1.5 text-[11px] text-amber-800">
                        <div class="flex gap-1.5"><span class="shrink-0">▲▼</span><span>Drag urutan blok naik/turun</span></div>
                        <div class="flex gap-1.5"><span class="shrink-0">👁</span><span>Sembunyikan tanpa hapus</span></div>
                        <div class="flex gap-1.5"><span class="shrink-0">🎨</span><span>Quick-pick warna di modal edit</span></div>
                        <div class="flex gap-1.5"><span class="shrink-0">&lt;/&gt;</span><span>Blok HTML bebas embed apapun</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─────────────── CANVAS AREA ─────────────── --}}
        <div class="flex-1 min-w-0">

            {{-- ═══ EDITOR TAB ═══ --}}
            <div x-show="activeTab === 'editor'"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0">

                @if(!$conferenceId)
                {{-- No conference selected --}}
                <div class="flex flex-col items-center justify-center h-[480px] bg-white border border-dashed border-gray-200 rounded-2xl text-center px-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-violet-100 to-purple-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Pilih Konferensi</h3>
                    <p class="text-sm text-gray-400 max-w-xs">Pilih konferensi dari dropdown di atas untuk mulai membangun halaman landingnya.</p>
                </div>

                @elseif(empty($blocks))
                {{-- Empty blocks --}}
                <div class="flex flex-col items-center justify-center h-[480px] bg-white border border-dashed border-gray-200 rounded-2xl text-center px-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-violet-100 to-purple-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Halaman Masih Kosong</h3>
                    <p class="text-sm text-gray-400 mb-6 max-w-xs">Klik kartu blok di sidebar kiri untuk mulai menambahkan section ke halaman konferensi.</p>
                    <div class="flex gap-2 flex-wrap justify-center">
                        <button wire:click="openAddBlock('hero')" class="flex items-center gap-2 px-4 py-2 bg-violet-600 text-white rounded-xl text-sm font-semibold hover:bg-violet-700 transition shadow-md shadow-violet-200">
                            <span>✦</span> Mulai dengan Hero Banner
                        </button>
                        <button wire:click="openAddBlock('text')" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">
                            <span>≡</span> Blok Teks
                        </button>
                    </div>
                </div>

                @else
                {{-- Block cards list --}}
                <div class="space-y-2">
                    @foreach($blocks as $i => $block)
                    @php
                        $m      = $blockMeta[$block['type']] ?? ['icon'=>'?','label'=>$block['type'],'color'=>'#374151','bg'=>'#f9fafb'];
                        $vis    = $block['visible'] ?? true;
                        $isLast = $i === count($blocks) - 1;
                    @endphp
                    <div class="group relative bg-white border {{ $vis ? 'border-gray-200' : 'border-dashed border-gray-200' }} rounded-2xl overflow-hidden transition-all hover:shadow-md hover:border-gray-300 {{ !$vis ? 'opacity-55' : '' }}"
                        wire:key="block-{{ $i }}">

                        {{-- Left accent stripe --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl" style="background:{{ $m['color'] }}"></div>

                        <div class="flex items-center gap-3 p-4 pl-5">

                            {{-- Sort controls --}}
                            <div class="flex flex-col shrink-0 gap-0.5">
                                <button wire:click="moveUp({{ $i }})" {{ $i === 0 ? 'disabled' : '' }}
                                    class="w-5 h-5 flex items-center justify-center rounded text-gray-300 {{ $i === 0 ? 'cursor-not-allowed' : 'hover:bg-gray-100 hover:text-gray-600 cursor-pointer' }} text-[10px] transition leading-none">▲</button>
                                <span class="text-[10px] font-mono text-gray-300 text-center leading-none">{{ $i + 1 }}</span>
                                <button wire:click="moveDown({{ $i }})" {{ $isLast ? 'disabled' : '' }}
                                    class="w-5 h-5 flex items-center justify-center rounded text-gray-300 {{ $isLast ? 'cursor-not-allowed' : 'hover:bg-gray-100 hover:text-gray-600 cursor-pointer' }} text-[10px] transition leading-none">▼</button>
                            </div>

                            {{-- Block type icon --}}
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-base shrink-0"
                                style="background:{{ $m['bg'] }}; color:{{ $m['color'] }}">
                                {{ $m['icon'] }}
                            </div>

                            {{-- Content preview --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                    <span class="text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full" style="background:{{ $m['bg'] }}; color:{{ $m['color'] }}">{{ $m['label'] }}</span>
                                    @if($block['title'] ?? null)
                                    <span class="text-sm font-semibold text-gray-800">{{ $block['title'] }}</span>
                                    @else
                                    <span class="text-sm text-gray-400 italic">tanpa judul</span>
                                    @endif
                                    @if(!$vis)
                                    <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-medium">● Tersembunyi</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    @if($block['content'] ?? null)
                                    <p class="text-xs text-gray-400 truncate max-w-sm">{{ Str::limit(strip_tags($block['content']), 90) }}</p>
                                    @endif
                                    @if($block['type'] === 'cta' && ($block['button_text'] ?? null))
                                    <span class="text-[10px] bg-orange-50 text-orange-600 border border-orange-200 px-2 py-0.5 rounded-full font-medium">→ {{ $block['button_text'] }}</span>
                                    @endif
                                    @if($block['type'] === 'divider')
                                    <span class="text-xs text-gray-300">────────</span>
                                    @endif
                                    @if($block['bg_color'] ?? null)
                                    <span class="flex items-center gap-1 text-[10px] text-gray-400">
                                        <span class="w-2.5 h-2.5 rounded-full border border-gray-200 inline-block" style="background: {{ str_starts_with($block['bg_color'], 'bg-') ? 'transparent' : $block['bg_color'] }}"></span>
                                        {{ $block['bg_color'] }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Image thumbnail --}}
                            @if($block['image_url'] ?? null)
                            <div class="shrink-0 w-16 h-12 rounded-lg overflow-hidden border border-gray-100">
                                <img src="{{ $block['image_url'] }}" class="w-full h-full object-cover" alt="">
                            </div>
                            @endif

                            {{-- Action buttons --}}
                            <div class="flex items-center gap-1.5 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="toggleBlockVisible({{ $i }})"
                                    title="{{ $vis ? 'Sembunyikan blok' : 'Tampilkan blok' }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border transition text-base {{ $vis ? 'border-gray-200 text-gray-400 hover:bg-gray-50' : 'border-green-200 bg-green-50 text-green-600' }}">
                                    {{ $vis ? '👁' : '◑' }}
                                </button>
                                <button wire:click="openEditBlock({{ $i }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 transition text-sm font-bold">
                                    ✏
                                </button>
                                <button wire:click="deleteBlock({{ $i }})" wire:confirm="Hapus blok '{{ $block['title'] ?? $m['label'] }}'?"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-500 hover:bg-red-100 transition text-sm">
                                    ✕
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Footer bar --}}
                <div class="mt-4 flex items-center justify-between bg-white border border-dashed border-gray-200 rounded-2xl px-5 py-3">
                    <p class="text-xs text-gray-400">{{ count($blocks) }} blok total • {{ count(array_filter($blocks, fn($b)=>$b['visible']??true)) }} ditampilkan • urutan = kanan atas ke bawah</p>
                    <button wire:click="openAddBlock('text')" class="flex items-center gap-1.5 text-xs font-semibold text-violet-600 hover:text-violet-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Blok
                    </button>
                </div>
                @endif
            </div>

            {{-- ═══ PREVIEW TAB ═══ --}}
            <div x-show="activeTab === 'preview'"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-cloak>

                @if(!$conferenceId || empty($blocks))
                <div class="flex flex-col items-center justify-center h-[480px] bg-white border border-dashed border-gray-200 rounded-2xl text-center px-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <p class="text-gray-500 font-semibold">{{ !$conferenceId ? 'Pilih konferensi terlebih dahulu' : 'Tambahkan blok di tab Editor' }}</p>
                </div>
                @else

                {{-- Device toggle --}}
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-gray-400">
                        Preview — <span class="text-gray-600 font-medium">{{ count(array_filter($blocks, fn($b)=>$b['visible']??true)) }} blok ditampilkan</span>
                        <span class="text-gray-300 mx-1">•</span>
                        <span class="text-amber-600">blok tersembunyi dilewati</span>
                    </p>
                    <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-0.5">
                        <button @click="device='desktop'" :class="device==='desktop'?'bg-white shadow text-gray-700':'text-gray-400'" class="px-2.5 py-1 rounded-md text-xs font-medium transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Desktop
                        </button>
                        <button @click="device='mobile'" :class="device==='mobile'?'bg-white shadow text-gray-700':'text-gray-400'" class="px-2.5 py-1 rounded-md text-xs font-medium transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Mobile
                        </button>
                    </div>
                </div>

                {{-- Browser frame --}}
                <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-xl">
                    {{-- Chrome bar --}}
                    <div class="bg-gray-100 border-b border-gray-200 px-4 py-2.5 flex items-center gap-3">
                        <div class="flex gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                        </div>
                        <div class="flex-1 bg-white border border-gray-200 rounded-md px-2.5 py-0.5 text-[11px] text-gray-400 flex items-center gap-1.5 max-w-xs mx-auto">
                            <svg class="w-2.5 h-2.5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                            {{ config('app.url') }}/conference/preview
                        </div>
                    </div>

                    {{-- Page content --}}
                    <div class="bg-white overflow-y-auto" style="max-height: 680px"
                        :class="device === 'mobile' ? 'max-w-sm mx-auto border-x border-gray-200' : ''">

                        @foreach($blocks as $block)
                        @if(!($block['visible'] ?? true)) @continue @endif
                        @php
                            $bgRaw  = $block['bg_color'] ?? '';
                            $isTw   = str_starts_with($bgRaw, 'bg-');
                            $bgSt   = (!$isTw && $bgRaw) ? "background:{$bgRaw};" : '';
                            $bgCls  = $isTw ? $bgRaw : '';
                        @endphp

                        @if($block['type'] === 'hero')
                        <div class="relative py-20 px-8 text-center {{ $bgCls ?: 'bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-700' }}" style="{{ $bgSt }}">
                            @if($block['image_url'] ?? null)
                            <div class="absolute inset-0 overflow-hidden"><img src="{{ $block['image_url'] }}" class="w-full h-full object-cover opacity-20" alt=""></div>
                            @endif
                            <div class="relative z-10 max-w-3xl mx-auto">
                                @if($block['title'] ?? null)<h1 class="text-4xl font-extrabold text-white mb-3 drop-shadow-lg">{{ $block['title'] }}</h1>@endif
                                @if($block['content'] ?? null)<p class="text-lg text-white/80 leading-relaxed">{{ $block['content'] }}</p>@endif
                            </div>
                        </div>

                        @elseif($block['type'] === 'text')
                        <div class="py-12 px-8 {{ $bgCls }}" style="{{ $bgSt }}">
                            <div class="max-w-3xl mx-auto">
                                @if($block['title'] ?? null)<h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $block['title'] }}</h2>@endif
                                <div class="text-gray-600 text-sm leading-relaxed prose">{!! nl2br(e($block['content'] ?? '')) !!}</div>
                            </div>
                        </div>

                        @elseif($block['type'] === 'text_image')
                        <div class="py-12 px-8 {{ $bgCls }}" style="{{ $bgSt }}">
                            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                                <div>
                                    @if($block['title'] ?? null)<h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $block['title'] }}</h2>@endif
                                    @if($block['content'] ?? null)<p class="text-gray-600 text-sm leading-relaxed">{{ $block['content'] }}</p>@endif
                                </div>
                                <div>
                                    @if($block['image_url'] ?? null)
                                    <img src="{{ $block['image_url'] }}" class="rounded-2xl shadow-lg w-full object-cover" alt="">
                                    @else
                                    <div class="h-52 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400 gap-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-xs">Gambar belum diupload</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @elseif($block['type'] === 'cta')
                        <div class="py-16 px-8 text-center {{ $bgCls ?: 'bg-gradient-to-r from-violet-600 to-indigo-600' }}" style="{{ $bgSt }}">
                            @if($block['title'] ?? null)<h2 class="text-3xl font-extrabold text-white mb-3">{{ $block['title'] }}</h2>@endif
                            @if($block['content'] ?? null)<p class="text-white/80 text-sm mb-7 max-w-lg mx-auto">{{ $block['content'] }}</p>@endif
                            @if($block['button_text'] ?? null)
                            <a href="{{ $block['button_url'] ?? '#' }}" class="inline-flex items-center gap-2 bg-white text-violet-700 font-bold px-8 py-3.5 rounded-full shadow-lg hover:bg-violet-50 text-sm transition">
                                {{ $block['button_text'] }} →
                            </a>
                            @endif
                        </div>

                        @elseif($block['type'] === 'highlight')
                        <div class="py-10 px-8 {{ $bgCls ?: 'bg-amber-50' }}" style="{{ $bgSt }}">
                            <div class="max-w-3xl mx-auto flex gap-5">
                                <div class="w-1 rounded-full bg-amber-400 shrink-0"></div>
                                <div>
                                    @if($block['title'] ?? null)<h3 class="text-xl font-bold text-gray-900 mb-2">{{ $block['title'] }}</h3>@endif
                                    @if($block['content'] ?? null)<p class="text-gray-700 text-sm leading-relaxed">{{ $block['content'] }}</p>@endif
                                </div>
                            </div>
                        </div>

                        @elseif($block['type'] === 'divider')
                        <div class="py-4 px-8 {{ $bgCls }}" style="{{ $bgSt }}">
                            <div class="max-w-5xl mx-auto border-t border-gray-200"></div>
                        </div>

                        @elseif($block['type'] === 'html')
                        <div class="py-8 px-8 {{ $bgCls }}" style="{{ $bgSt }}">
                            @if($block['title'] ?? null)<p class="text-[11px] text-gray-300 font-mono mb-2">&lt;!-- {{ $block['title'] }} --&gt;</p>@endif
                            <div class="bg-gray-900 rounded-2xl p-5 overflow-x-auto">
                                <pre class="text-xs text-green-400 font-mono leading-relaxed whitespace-pre-wrap">{{ Str::limit($block['content'] ?? '', 600) }}</pre>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-2">* HTML dirender langsung di halaman publik</p>
                        </div>
                        @endif

                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>{{-- canvas --}}
    </div>{{-- main body --}}

    {{-- ╔══════════════════════════════════════════════════════════╗
         ║  BLOCK EDIT / ADD MODAL                                 ║
         ╚══════════════════════════════════════════════════════════╝ --}}
    @if($showBlockModal)
    @php $cm = $blockMeta[$blockType] ?? ['icon'=>'?','label'=>$blockType,'color'=>'#374151','bg'=>'#f9fafb','desc'=>'']; @endphp
    <div class="fixed inset-0 z-50 flex items-center justify-end" x-data>
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="$set('showBlockModal',false)"></div>

        {{-- Slide-in panel --}}
        <div class="relative z-10 h-full w-full max-w-md bg-white shadow-2xl flex flex-col"
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100">

            {{-- Panel header --}}
            <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 shrink-0">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-base shrink-0"
                    style="background:{{ $cm['bg'] }}; color:{{ $cm['color'] }}">
                    {{ $cm['icon'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900">{{ $editIndex !== null ? 'Edit' : 'Tambah' }}: {{ $cm['label'] }}</p>
                    <p class="text-[11px] text-gray-400 truncate">{{ $cm['desc'] }}</p>
                </div>
                <button wire:click="$set('showBlockModal',false)" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition text-lg leading-none">✕</button>
            </div>

            {{-- Panel body --}}
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-5">

                {{-- Title --}}
                @if(!in_array($blockType, ['divider']))
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Judul Section</label>
                    <input wire:model="blockTitle" type="text"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition"
                        placeholder="cth: Tentang Konferensi Ini">
                </div>
                @endif

                {{-- Content: hero --}}
                @if($blockType === 'hero')
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Tagline / Subtitle</label>
                    <textarea wire:model="blockContent" rows="3"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition resize-none"
                        placeholder="Tema atau deskripsi singkat konferensi..."></textarea>
                </div>
                @endif

                {{-- Content: text / text_image / highlight --}}
                @if(in_array($blockType, ['text','text_image','highlight']))
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Isi Konten</label>
                    <textarea wire:model="blockContent" rows="6"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none transition resize-none"
                        placeholder="Ketik isi teks yang akan ditampilkan di halaman..."></textarea>
                </div>
                @endif

                {{-- Content: html --}}
                @if($blockType === 'html')
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-gray-700">HTML Content</label>
                        <span class="text-[10px] text-red-500 font-medium bg-red-50 px-2 py-0.5 rounded-full">⚠ Pastikan kode aman</span>
                    </div>
                    <textarea wire:model="blockContent" rows="8"
                        class="w-full px-3.5 py-2.5 bg-gray-900 text-green-400 border border-gray-700 rounded-xl text-xs font-mono focus:ring-2 focus:ring-violet-400 outline-none transition resize-none"
                        placeholder="<div class=&quot;...&quot;>&#10;  <!-- kode HTML di sini -->&#10;</div>"></textarea>
                    <p class="text-[10px] text-amber-600 mt-1.5 flex items-center gap-1">
                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        Hanya masukkan script dari sumber yang dipercaya.
                    </p>
                </div>
                @endif

                {{-- CTA fields --}}
                @if($blockType === 'cta')
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Deskripsi <span class="font-normal text-gray-400">(opsional)</span></label>
                    <textarea wire:model="blockContent" rows="2"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-violet-300 outline-none transition resize-none"
                        placeholder="Teks pendeskripsi di bawah judul..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Teks Tombol</label>
                        <input wire:model="blockButtonText" type="text"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-violet-300 outline-none transition"
                            placeholder="Daftar Sekarang">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">URL Tombol</label>
                        <input wire:model="blockButtonUrl" type="text"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-violet-300 outline-none transition"
                            placeholder="https://...">
                    </div>
                </div>
                @endif

                {{-- Image upload --}}
                @if(in_array($blockType, ['hero','text_image']))
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Gambar
                        <span class="font-normal text-gray-400">— maks 5MB, JPG/PNG/WebP</span>
                    </label>
                    <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-violet-400 hover:bg-violet-50 transition bg-gray-50">
                        <input wire:model="blockImageUpload" type="file" accept="image/*" class="hidden">
                        <div wire:loading.remove wire:target="blockImageUpload" class="text-center">
                            <svg class="w-7 h-7 text-gray-300 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-gray-400">Klik untuk upload gambar</p>
                        </div>
                        <div wire:loading wire:target="blockImageUpload" class="text-xs text-violet-600 flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Mengupload...
                        </div>
                    </label>
                    @if($blockImageUrl)
                    <div class="mt-2.5 relative group">
                        <img src="{{ $blockImageUrl }}" class="w-full h-32 object-cover rounded-xl border border-gray-200" alt="">
                        <div class="absolute inset-0 bg-black/40 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="text-white text-xs font-medium">✓ Gambar tersimpan</span>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Background color --}}
                @if(!in_array($blockType, ['divider','html']))
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Warna Latar</label>
                    <input wire:model="blockBgColor" type="text"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-mono placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-violet-300 outline-none transition"
                        placeholder="bg-white  |  bg-indigo-50  |  #f0f4ff">
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        @foreach([
                            ['bg-white',       '#ffffff', 'Putih'],
                            ['bg-gray-50',     '#f9fafb', 'Abu Muda'],
                            ['bg-violet-50',   '#f5f3ff', 'Violet Muda'],
                            ['bg-indigo-50',   '#eef2ff', 'Indigo Muda'],
                            ['bg-blue-50',     '#eff6ff', 'Biru Muda'],
                            ['bg-amber-50',    '#fffbeb', 'Amber Muda'],
                            ['bg-green-50',    '#f0fdf4', 'Hijau Muda'],
                            ['bg-violet-600',  '#7c3aed', 'Violet'],
                            ['bg-indigo-700',  '#3730a3', 'Indigo'],
                            ['bg-gray-900',    '#111827', 'Gelap'],
                        ] as [$cls, $hex, $name])
                        <button type="button" wire:click="$set('blockBgColor','{{ $cls }}')"
                            title="{{ $name }}"
                            class="w-7 h-7 rounded-lg border-2 transition {{ $blockBgColor === $cls ? 'border-violet-500 scale-110 shadow-md' : 'border-gray-200 hover:border-gray-400' }}"
                            style="background:{{ $hex }}">
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Visibility toggle --}}
                <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                    <div class="relative">
                        <input wire:model="blockVisible" type="checkbox" class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-300 rounded-full peer-checked:bg-violet-600 transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Tampilkan di halaman publik</p>
                        <p class="text-[11px] text-gray-400">Matikan untuk menyembunyikan sementara</p>
                    </div>
                </label>

            </div>

            {{-- Panel footer --}}
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 shrink-0">
                <div class="flex gap-2">
                    <button wire:click="$set('showBlockModal',false)"
                        class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 font-medium hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button wire:click="saveBlock"
                        class="flex-1 py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-sm font-bold transition shadow-md shadow-violet-200 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="saveBlock">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            {{ $editIndex !== null ? 'Simpan Perubahan' : 'Tambah Blok' }}
                        </span>
                        <span wire:loading wire:target="saveBlock" class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
                @if($editIndex !== null)
                <p class="text-center text-[10px] text-gray-400 mt-2">Mengedit blok #{{ $editIndex + 1 }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

</div>
