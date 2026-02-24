@php
$fieldMeta = [
    'text'     => ['icon' => 'Aa',  'label' => 'Teks Singkat',      'color' => '#2563eb', 'bg' => '#eff6ff', 'desc' => 'Input satu baris, cocok untuk nama, nomor, atau kode'],
    'textarea' => ['icon' => '¶',   'label' => 'Teks Panjang',      'color' => '#7c3aed', 'bg' => '#f5f3ff', 'desc' => 'Area teks multi-baris untuk abstrak atau deskripsi'],
    'number'   => ['icon' => '#',   'label' => 'Angka',             'color' => '#059669', 'bg' => '#ecfdf5', 'desc' => 'Input numerik, mendukung validasi min/max'],
    'select'   => ['icon' => '⌄',   'label' => 'Dropdown',          'color' => '#d97706', 'bg' => '#fffbeb', 'desc' => 'Pilihan dari daftar opsi yang Anda tentukan'],
    'checkbox' => ['icon' => '✓',   'label' => 'Centang (Ya/Tidak)', 'color' => '#0891b2', 'bg' => '#ecfeff', 'desc' => 'Toggle persetujuan atau konfirmasi ya/tidak'],
    'file'     => ['icon' => '⬆',   'label' => 'Upload File',       'color' => '#dc2626', 'bg' => '#fff1f2', 'desc' => 'Tombol upload dokumen, gambar, atau file lainnya'],
];
@endphp

<div x-data="{ activeTab: 'fields', showGuide: false }" class="min-h-screen bg-gray-50/70">

    {{-- ╔══════════════════════════════════════════════════════════╗
         ║  STICKY HEADER                                          ║
         ╚══════════════════════════════════════════════════════════╝ --}}
    <div class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center gap-4 py-3">
                {{-- Brand --}}
                <div class="flex items-center gap-3 shrink-0">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-violet-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"/></svg>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold text-gray-900 leading-none">Form Builder</h1>
                        <p class="text-[11px] text-gray-400 mt-0.5 leading-none">Submission Fields</p>
                    </div>
                </div>

                {{-- Conference picker --}}
                <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <select wire:model.live="conferenceId" class="bg-transparent text-sm text-gray-700 font-medium outline-none pr-4 cursor-pointer min-w-[200px]">
                        <option value="0">— Pilih Konferensi —</option>
                        @foreach($conferences as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select>
                </div>

                @if($conferenceId)
                <div class="flex items-center gap-1 text-xs text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1.5 rounded-lg">
                    <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                    {{ count($fields) }} field terdaftar
                </div>
                @endif

                <div class="flex-1"></div>

                {{-- Help --}}
                <button @click="showGuide = !showGuide"
                    :class="showGuide ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'"
                    class="flex items-center gap-1.5 px-3 py-1.5 border rounded-lg text-xs font-medium transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Panduan
                </button>

                {{-- Tab switcher --}}
                <div class="flex items-center bg-gray-100 rounded-xl p-1 gap-0.5">
                    <button @click="activeTab = 'fields'"
                        :class="activeTab === 'fields' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        Field List
                    </button>
                    <button @click="activeTab = 'preview'"
                        :class="activeTab === 'preview' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Preview Form
                    </button>
                </div>

                {{-- Add field button --}}
                @if($conferenceId)
                <button wire:click="openAddField"
                    class="flex items-center gap-1.5 px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-blue-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Field
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Toast --}}
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
         ║  PANDUAN (collapsible)                                  ║
         ╚══════════════════════════════════════════════════════════╝ --}}
    <div x-show="showGuide" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-6 py-5">
            <div class="flex items-start justify-between gap-6 mb-5">
                <div>
                    <h3 class="text-base font-bold">Cara Menggunakan Form Builder</h3>
                    <p class="text-blue-200 text-xs mt-0.5">Tambahkan field custom yang muncul di form submission paper author</p>
                </div>
                <button @click="showGuide=false" class="text-blue-300 hover:text-white transition mt-0.5 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                @foreach([
                    ['1','Pilih Konferensi','Pilih konferensi yang ingin dikustomisasi form submissionnya.'],
                    ['2','Tambah Field','Klik "+ Tambah Field" atau pilih tipe dari sidebar kiri.'],
                    ['3','Atur Urutan','Gunakan ▲▼ untuk mengubah urutan kemunculan di form author.'],
                    ['4','Preview Form','Buka tab Preview untuk melihat tampilan form seperti yang dilihat author.'],
                ] as [$n,$t,$d])
                <div class="bg-white/10 backdrop-blur rounded-xl p-3.5 flex gap-3">
                    <div class="w-6 h-6 bg-white/20 rounded-full text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $n }}</div>
                    <div>
                        <p class="text-sm font-semibold leading-tight">{{ $t }}</p>
                        <p class="text-[11px] text-blue-200 mt-1 leading-relaxed">{{ $d }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
                @foreach($fieldMeta as $type => $m)
                <div class="bg-white/10 rounded-xl p-2.5 flex gap-2.5 items-start">
                    <span class="font-mono font-black text-sm shrink-0 mt-0.5">{{ $m['icon'] }}</span>
                    <div>
                        <p class="text-xs font-bold leading-tight">{{ $m['label'] }}</p>
                        <p class="text-[10px] text-blue-200 mt-0.5 leading-tight">{{ $m['desc'] }}</p>
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

        {{-- ───────── LEFT SIDEBAR ───────── --}}
        <div class="w-52 shrink-0 sticky top-[61px]" style="max-height:calc(100vh - 80px); overflow-y:auto">
            <div class="space-y-3 pb-6">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Tambah Field</p>

                @foreach($fieldMeta as $type => $m)
                <button wire:click="openAddField" @click="$wire.set('fieldType','{{ $type }}')" @if(!$conferenceId) disabled @endif
                    class="w-full text-left group flex items-center gap-3 px-3 py-2.5 bg-white border border-gray-200 rounded-xl hover:border-gray-300 hover:shadow-sm active:scale-[0.98] transition-all duration-150 {{ !$conferenceId ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-sm shrink-0 transition-transform group-hover:scale-110"
                        style="background:{{ $m['bg'] }}; color:{{ $m['color'] }}">
                        {{ $m['icon'] }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-gray-800 leading-tight">{{ $m['label'] }}</p>
                        <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ Str::limit($m['desc'], 30) }}</p>
                    </div>
                </button>
                @endforeach

                @if(!$conferenceId)
                <p class="text-[10px] text-center text-gray-400 px-2 leading-relaxed">Pilih konferensi di header terlebih dahulu</p>
                @endif

                <div class="border-t border-gray-100 pt-3">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1 mb-2">Info</p>
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 space-y-1.5 text-[11px] text-blue-700">
                        <div class="flex gap-1.5 items-start"><span class="font-black shrink-0 mt-0.5">▲▼</span><span>Ubah urutan kemunculan field</span></div>
                        <div class="flex gap-1.5 items-start"><span class="font-black shrink-0 mt-0.5">✱</span><span>Field wajib membuat author tidak bisa lewati</span></div>
                        <div class="flex gap-1.5 items-start"><span class="font-black shrink-0 mt-0.5">key</span><span>Key otomatis dibuat dari label</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─────────── CANVAS ─────────── --}}
        <div class="flex-1 min-w-0">

            {{-- ═══ FIELDS TAB ═══ --}}
            <div x-show="activeTab === 'fields'"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0">

                @if(!$conferenceId)
                <div class="flex flex-col items-center justify-center h-[480px] bg-white border border-dashed border-gray-200 rounded-2xl text-center px-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-violet-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Pilih Konferensi</h3>
                    <p class="text-sm text-gray-400 max-w-xs">Pilih konferensi dari dropdown di atas untuk mengatur field custom form submissionnya.</p>
                </div>

                @elseif(empty($fields))
                <div class="flex flex-col items-center justify-center h-[480px] bg-white border border-dashed border-gray-200 rounded-2xl text-center px-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-violet-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Field</h3>
                    <p class="text-sm text-gray-400 mb-6 max-w-xs">Form submission hanya berisi field standar. Klik tombol di bawah untuk menambahkan field custom.</p>
                    <div class="flex gap-2 flex-wrap justify-center">
                        <button wire:click="openAddField" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-md shadow-blue-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Field Pertama
                        </button>
                    </div>
                </div>

                @else
                <div class="space-y-2">
                    @foreach($fields as $i => $field)
                    @php
                        $m      = $fieldMeta[$field['type']] ?? ['icon'=>'?','label'=>$field['type'],'color'=>'#374151','bg'=>'#f9fafb'];
                        $isLast = $i === count($fields) - 1;
                        $isReq  = $field['required'] ?? false;
                    @endphp
                    <div class="group relative bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-md hover:border-gray-300 transition-all"
                        wire:key="field-{{ $i }}">
                        {{-- Accent stripe --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl" style="background:{{ $m['color'] }}"></div>

                        <div class="flex items-center gap-3 p-4 pl-5">
                            {{-- Sort --}}
                            <div class="flex flex-col shrink-0 gap-0.5">
                                <button wire:click="moveUp({{ $i }})" {{ $i === 0 ? 'disabled' : '' }}
                                    class="w-5 h-5 flex items-center justify-center rounded text-gray-300 {{ $i === 0 ? 'cursor-not-allowed' : 'hover:bg-gray-100 hover:text-gray-600 cursor-pointer' }} text-[10px] transition">▲</button>
                                <span class="text-[10px] font-mono text-gray-300 text-center leading-none">{{ $i + 1 }}</span>
                                <button wire:click="moveDown({{ $i }})" {{ $isLast ? 'disabled' : '' }}
                                    class="w-5 h-5 flex items-center justify-center rounded text-gray-300 {{ $isLast ? 'cursor-not-allowed' : 'hover:bg-gray-100 hover:text-gray-600 cursor-pointer' }} text-[10px] transition">▼</button>
                            </div>

                            {{-- Type icon --}}
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm shrink-0"
                                style="background:{{ $m['bg'] }}; color:{{ $m['color'] }}">
                                {{ $m['icon'] }}
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                    <span class="text-sm font-bold text-gray-800">{{ $field['label'] }}</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full" style="background:{{ $m['bg'] }}; color:{{ $m['color'] }}">{{ $m['label'] }}</span>
                                    @if($isReq)
                                    <span class="text-[10px] font-bold bg-red-50 text-red-600 border border-red-200 px-2 py-0.5 rounded-full">✱ Wajib</span>
                                    @else
                                    <span class="text-[10px] text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full">Opsional</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <span class="text-[11px] text-gray-400 font-mono bg-gray-50 px-2 py-0.5 rounded">{{ $field['key'] }}</span>
                                    @if($field['help'] ?? null)
                                    <span class="text-xs text-gray-500 italic">{{ $field['help'] }}</span>
                                    @endif
                                    @if($field['type'] === 'select' && !empty($field['options']))
                                    <div class="flex gap-1 flex-wrap">
                                        @foreach(array_slice($field['options'], 0, 4) as $opt)
                                        <span class="text-[10px] bg-amber-50 text-amber-700 border border-amber-200 px-1.5 py-0.5 rounded-md">{{ $opt }}</span>
                                        @endforeach
                                        @if(count($field['options']) > 4)
                                        <span class="text-[10px] text-gray-400">+{{ count($field['options'])-4 }} lagi</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-1.5 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="openEditField({{ $i }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 transition text-sm font-bold">✏</button>
                                <button wire:click="deleteField({{ $i }})" wire:confirm="Hapus field '{{ $field['label'] }}'?"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-500 hover:bg-red-100 transition text-sm">✕</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 flex items-center justify-between bg-white border border-dashed border-gray-200 rounded-2xl px-5 py-3">
                    <p class="text-xs text-gray-400">
                        {{ count($fields) }} field custom •
                        {{ count(array_filter($fields, fn($f) => $f['required'] ?? false)) }} wajib •
                        urutan = urutan muncul di form author
                    </p>
                    <button wire:click="openAddField" class="flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Field
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

                @if(!$conferenceId || empty($fields))
                <div class="flex flex-col items-center justify-center h-[480px] bg-white border border-dashed border-gray-200 rounded-2xl text-center px-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <p class="text-gray-500 font-semibold">{{ !$conferenceId ? 'Pilih konferensi terlebih dahulu' : 'Tambahkan field di tab Field List' }}</p>
                </div>
                @else
                <div class="mb-3 flex items-center gap-2">
                    <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-2 rounded-xl text-xs">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Ini adalah preview tampilan form seperti yang dilihat oleh author saat submit paper.
                    </div>
                </div>

                {{-- Mock browser frame --}}
                <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-lg">
                    <div class="bg-gray-100 border-b border-gray-200 px-4 py-2.5 flex items-center gap-3">
                        <div class="flex gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                        </div>
                        <div class="flex-1 bg-white border border-gray-200 rounded-md px-2.5 py-0.5 text-[11px] text-gray-400 flex items-center gap-1.5 max-w-xs mx-auto">
                            <svg class="w-2.5 h-2.5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                            {{ config('app.url') }}/author/submit
                        </div>
                    </div>
                    <div class="bg-white p-8 overflow-y-auto" style="max-height: 600px">
                        {{-- Standard fields header --}}
                        <div class="max-w-2xl mx-auto">
                            <div class="mb-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-1">Submit Paper</h2>
                                <p class="text-sm text-gray-500">Isi semua informasi yang dibutuhkan untuk submit paper Anda.</p>
                            </div>

                            {{-- Standard (built-in) fields --}}
                            <div class="mb-6 pb-6 border-b border-gray-100">
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">Field Standar</p>
                                <div class="space-y-4 opacity-50 pointer-events-none">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Paper <span class="text-red-500">*</span></label>
                                        <input type="text" disabled class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400" value="(judul paper)">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Abstrak <span class="text-red-500">*</span></label>
                                        <div class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400 h-20">(abstrak paper)</div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">File Paper <span class="text-red-500">*</span></label>
                                        <div class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400">(upload file)</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Custom fields --}}
                            @if(!empty($fields))
                            <div>
                                <p class="text-[11px] font-bold text-blue-500 uppercase tracking-widest mb-4">Field Tambahan (Custom)</p>
                                <div class="space-y-5">
                                    @foreach($fields as $field)
                                    @php $isReq = $field['required'] ?? false; @endphp
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                            {{ $field['label'] }}
                                            @if($isReq)<span class="text-red-500 ml-0.5">*</span>@endif
                                            <span class="ml-1.5 text-[10px] font-normal text-gray-400 font-mono">({{ $field['type'] }})</span>
                                        </label>

                                        @if($field['type'] === 'text')
                                        <input type="text" disabled placeholder="{{ $field['help'] ?? 'Isi '.$field['label'].'...' }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400">

                                        @elseif($field['type'] === 'textarea')
                                        <textarea disabled rows="3" placeholder="{{ $field['help'] ?? 'Isi '.$field['label'].'...' }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400 resize-none"></textarea>

                                        @elseif($field['type'] === 'number')
                                        <input type="number" disabled placeholder="{{ $field['help'] ?? '0' }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400">

                                        @elseif($field['type'] === 'select')
                                        <select disabled class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400">
                                            <option>— Pilih salah satu —</option>
                                            @foreach($field['options'] ?? [] as $opt)
                                            <option>{{ $opt }}</option>
                                            @endforeach
                                        </select>

                                        @elseif($field['type'] === 'checkbox')
                                        <label class="flex items-center gap-3 px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl cursor-not-allowed">
                                            <input type="checkbox" disabled class="w-4 h-4 rounded">
                                            <span class="text-sm text-gray-400">{{ $field['help'] ?? $field['label'] }}</span>
                                        </label>

                                        @elseif($field['type'] === 'file')
                                        <div class="flex items-center gap-3 px-3.5 py-2.5 bg-gray-50 border border-dashed border-gray-300 rounded-xl">
                                            <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                            <span class="text-sm text-gray-400">{{ $field['help'] ?? 'Pilih file untuk diupload...' }}</span>
                                        </div>
                                        @endif

                                        @if(($field['help'] ?? null) && $field['type'] !== 'checkbox')
                                        <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                            {{ $field['help'] }}
                                        </p>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="mt-8 pt-6 border-t border-gray-100">
                                <div class="flex gap-3 justify-end">
                                    <div class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-400 opacity-50">Batal</div>
                                    <div class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold opacity-50">Submit Paper</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>{{-- canvas --}}
    </div>{{-- main body --}}

    {{-- ╔══════════════════════════════════════════════════════════╗
         ║  FIELD MODAL (slide-in panel)                           ║
         ╚══════════════════════════════════════════════════════════╝ --}}
    @if($showFieldModal)
    @php $cm = $fieldMeta[$fieldType] ?? ['icon'=>'?','label'=>$fieldType,'color'=>'#374151','bg'=>'#f9fafb','desc'=>'']; @endphp
    <div class="fixed inset-0 z-50 flex items-center justify-end">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="$set('showFieldModal',false)"></div>

        <div class="relative z-10 h-full w-full max-w-md bg-white shadow-2xl flex flex-col"
            x-data x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100">

            {{-- Header --}}
            <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 shrink-0">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-base shrink-0"
                    style="background:{{ $cm['bg'] }}; color:{{ $cm['color'] }}">
                    {{ $cm['icon'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900">{{ $editIndex !== null ? 'Edit' : 'Tambah' }} Field: {{ $cm['label'] }}</p>
                    <p class="text-[11px] text-gray-400 truncate">{{ $cm['desc'] }}</p>
                </div>
                <button wire:click="$set('showFieldModal',false)" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition text-lg leading-none">✕</button>
            </div>

            {{-- Body --}}
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">

                {{-- Field type picker --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Tipe Field</label>
                    <div class="grid grid-cols-2 gap-1.5">
                        @foreach($fieldMeta as $type => $m)
                        <button type="button" wire:click="$set('fieldType','{{ $type }}')"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl border transition text-left {{ $fieldType === $type ? 'border-2 shadow-sm' : 'border-gray-200 hover:border-gray-300 bg-white' }}"
                            style="{{ $fieldType === $type ? 'border-color:'.$m['color'].'; background:'.$m['bg'] : '' }}">
                            <span class="font-black text-sm shrink-0 w-5 text-center" style="color:{{ $m['color'] }}">{{ $m['icon'] }}</span>
                            <span class="text-xs font-semibold truncate" style="color:{{ $fieldType === $type ? $m['color'] : '#374151' }}">{{ $m['label'] }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Label --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Label Field <span class="text-red-500">*</span></label>
                    <input wire:model.live="fieldLabel" type="text"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-300 focus:border-blue-400 outline-none transition"
                        placeholder="cth: Nomor SINTA Penulis">
                    @error('fieldLabel')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-[10px] text-gray-400 mt-1">Key otomatis: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600">{{ $fieldKey ?: \Illuminate\Support\Str::snake(\Illuminate\Support\Str::slug($fieldLabel, '_')) ?: 'field_key' }}</code></p>
                </div>

                {{-- Options for select --}}
                @if($fieldType === 'select')
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Opsi Pilihan <span class="text-red-500">*</span></label>
                    <textarea wire:model="fieldOptions" rows="3"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-300 outline-none transition resize-none"
                        placeholder="Opsi A, Opsi B, Opsi C"></textarea>
                    <p class="text-[10px] text-gray-400 mt-1">Pisahkan setiap opsi dengan koma</p>
                    @if($fieldOptions)
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach(array_filter(array_map('trim', explode(',', $fieldOptions))) as $opt)
                        <span class="text-[10px] bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full">{{ $opt }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                {{-- Help text --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Teks Bantuan <span class="font-normal text-gray-400">(opsional)</span></label>
                    <input wire:model="fieldHelp" type="text"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-300 outline-none transition"
                        placeholder="Petunjuk atau contoh isian untuk author">
                    <p class="text-[10px] text-gray-400 mt-1">Ditampilkan di bawah input sebagai petunjuk bagi author</p>
                </div>

                {{-- Required toggle --}}
                <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                    <div class="relative">
                        <input wire:model="fieldRequired" type="checkbox" class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-300 rounded-full peer-checked:bg-blue-600 transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Field wajib diisi</p>
                        <p class="text-[11px] text-gray-400">Author tidak bisa submit tanpa mengisi field ini</p>
                    </div>
                </label>

                {{-- Live mini preview --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-3 py-2 flex items-center gap-2">
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Mini Preview</span>
                    </div>
                    <div class="p-4 bg-white">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            {{ $fieldLabel ?: 'Label Field' }}
                            @if($fieldRequired)<span class="text-red-500 ml-0.5">*</span>@endif
                        </label>
                        @if($fieldType === 'text')
                        <input type="text" disabled placeholder="{{ $fieldHelp ?: 'Input teks...' }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400">
                        @elseif($fieldType === 'textarea')
                        <textarea disabled rows="2" placeholder="{{ $fieldHelp ?: 'Input teks panjang...' }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400 resize-none"></textarea>
                        @elseif($fieldType === 'number')
                        <input type="number" disabled placeholder="{{ $fieldHelp ?: '0' }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400">
                        @elseif($fieldType === 'select')
                        <select disabled class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-400">
                            <option>— Pilih —</option>
                            @foreach(array_filter(array_map('trim', explode(',', $fieldOptions))) as $opt)
                            <option>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @elseif($fieldType === 'checkbox')
                        <label class="flex items-center gap-3 px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                            <input type="checkbox" disabled class="w-4 h-4 rounded">
                            <span class="text-sm text-gray-400">{{ $fieldHelp ?: $fieldLabel ?: 'Centang jika ya' }}</span>
                        </label>
                        @elseif($fieldType === 'file')
                        <div class="flex items-center gap-3 px-3.5 py-2.5 bg-gray-50 border border-dashed border-gray-300 rounded-xl">
                            <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="text-sm text-gray-400">{{ $fieldHelp ?: 'Pilih file...' }}</span>
                        </div>
                        @endif
                        @if($fieldHelp && !in_array($fieldType, ['checkbox']))
                        <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            {{ $fieldHelp }}
                        </p>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 shrink-0">
                <div class="flex gap-2">
                    <button wire:click="$set('showFieldModal',false)"
                        class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 font-medium hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button wire:click="saveField"
                        class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition shadow-md shadow-blue-200 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="saveField">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            {{ $editIndex !== null ? 'Simpan Perubahan' : 'Tambah Field' }}
                        </span>
                        <span wire:loading wire:target="saveField" class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
                @if($editIndex !== null)
                <p class="text-center text-[10px] text-gray-400 mt-2">Mengedit field #{{ $editIndex + 1 }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

</div>
