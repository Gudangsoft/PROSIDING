<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Kegiatan Prosiding</h2>
            <p class="text-sm text-gray-500">Kelola seminar, konferensi, dan kegiatan prosiding</p>
        </div>
        <a href="{{ route('admin.conferences.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Kegiatan
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                    <p class="text-xs text-gray-500">Total Kegiatan</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['published'] }}</p>
                    <p class="text-xs text-gray-500">Dipublikasikan</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['draft'] }}</p>
                    <p class="text-xs text-gray-500">Draft</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['active'] }}</p>
                    <p class="text-xs text-gray-500">Aktif Sekarang</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, akronim, atau tema..."
                class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <select wire:model.live="statusFilter" class="px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="published">Dipublikasikan</option>
            <option value="archived">Diarsipkan</option>
        </select>
    </div>

    {{-- Conference Table --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Nama Kegiatan</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Tanggal</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Tempat</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Data</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Aktif</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($conferences as $conf)
                    <tr class="hover:bg-gray-50 transition {{ $conf->is_active ? 'bg-green-50/30' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($conf->logo)
                                <img src="{{ asset('storage/' . $conf->logo) }}" class="w-10 h-10 rounded-lg object-cover border" alt="Logo">
                                @else
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ $conf->name }}</p>
                                    @if($conf->acronym)<p class="text-xs text-blue-600 font-medium">{{ $conf->acronym }}</p>@endif
                                    @if($conf->theme)<p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($conf->theme, 50) }}</p>@endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-700 text-xs">{{ $conf->date_range }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="px-1.5 py-0.5 rounded font-medium {{ \App\Models\Conference::VENUE_TYPE_COLORS[$conf->venue_type ?? 'offline'] ?? '' }}">{{ $conf->venue_type_label }}</span>
                                <span class="px-1.5 py-0.5 rounded font-medium {{ \App\Models\Conference::CONFERENCE_TYPE_COLORS[$conf->conference_type ?? 'nasional'] ?? 'bg-sky-100 text-sky-700' }}">{{ $conf->conference_type_label }}</span>
                            </span>
                            @if($conf->venue || $conf->city)
                                <br><span class="text-gray-500">{{ $conf->venue_display }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2 text-xs">
                                <span title="Tanggal Penting" class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-purple-50 text-purple-700 rounded">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $conf->important_dates_count }}
                                </span>
                                <span title="Panitia" class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-amber-50 text-amber-700 rounded">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $conf->committees_count }}
                                </span>
                                <span title="Topik" class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-cyan-50 text-cyan-700 rounded">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    {{ $conf->topics_count }}
                                </span>
                                <span title="Keynote Speaker" class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-rose-50 text-rose-700 rounded">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                    {{ $conf->keynote_speakers_count }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                @if($conf->status==='published') bg-green-100 text-green-800
                                @elseif($conf->status==='draft') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">{{ $conf->status_label }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="toggleActive({{ $conf->id }})"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                                {{ $conf->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow
                                    {{ $conf->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1" x-data="{ open: false }">
                                <button wire:click="viewDetail({{ $conf->id }})" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <a href="{{ route('admin.conferences.edit', $conf) }}" class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition" title="Lainnya">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition
                                        class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border py-1 z-20">
                                        <button wire:click="duplicate({{ $conf->id }})" @click="open = false"
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            Duplikasi
                                        </button>
                                        <hr class="my-1">
                                        <button wire:click="delete({{ $conf->id }})" wire:confirm="Yakin hapus kegiatan '{{ $conf->name }}'? Semua data terkait akan ikut terhapus."
                                            @click="open = false"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    {{-- Detail Expand Row --}}
                    @if($viewingId === $conf->id)
                    <tr class="bg-gray-50/50">
                        <td colspan="7" class="px-6 py-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                {{-- Cover Image --}}
                                <div class="md:col-span-2 lg:col-span-1">
                                    @if($conf->cover_image)
                                    <div class="rounded-lg overflow-hidden border">
                                        <img src="{{ asset('storage/' . $conf->cover_image) }}" class="w-full h-32 object-cover" alt="Cover">
                                    </div>
                                    @else
                                    <div class="w-full h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <p class="text-xs text-gray-400">Tidak ada cover</p>
                                    </div>
                                    @endif
                                </div>

                                {{-- Info Umum --}}
                                <div class="space-y-2">
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase">Informasi Umum</h4>
                                    <div class="space-y-1 text-sm">
                                        <p><span class="text-gray-500">Penyelenggara:</span> <span class="text-gray-800">{{ $conf->organizer ?? '-' }}</span></p>
                                        <p><span class="text-gray-500">Jenis:</span> <span class="px-1.5 py-0.5 rounded text-xs font-medium {{ \App\Models\Conference::CONFERENCE_TYPE_COLORS[$conf->conference_type ?? 'nasional'] ?? 'bg-sky-100 text-sky-700' }}">{{ $conf->conference_type_label }}</span></p>
                                        <p><span class="text-gray-500">Lokasi:</span> <span class="text-gray-800">{{ $conf->venue_type_label }} — {{ $conf->venue_display }}</span></p>
                                        <p><span class="text-gray-500">Tanggal:</span> <span class="text-gray-800">{{ $conf->date_range }}</span></p>
                                    </div>
                                </div>

                                {{-- Tanggal Penting --}}
                                <div class="space-y-2">
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase">Tanggal Penting ({{ $conf->important_dates_count }})</h4>
                                    @forelse($conf->importantDates->take(4) as $date)
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-600">{{ $date->title }}</span>
                                        <span class="{{ $date->is_past ? 'text-red-500 line-through' : 'text-gray-800 font-medium' }}">{{ $date->date->format('d M Y') }}</span>
                                    </div>
                                    @empty
                                    <p class="text-xs text-gray-400">Belum ada</p>
                                    @endforelse
                                    @if($conf->important_dates_count > 4)
                                    <p class="text-xs text-blue-600">+{{ $conf->important_dates_count - 4 }} lainnya</p>
                                    @endif
                                </div>

                                {{-- Deskripsi --}}
                                <div class="space-y-2">
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase">Deskripsi</h4>
                                    <p class="text-xs text-gray-600">{{ Str::limit($conf->description, 200) ?: 'Belum ada deskripsi.' }}</p>
                                </div>
                            </div>

                            {{-- Topik --}}
                            @if($conf->topics_count > 0)
                            <div class="mt-4 pt-4 border-t">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Topik ({{ $conf->topics_count }})</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($conf->topics as $topic)
                                    <span class="inline-flex items-center px-2.5 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">
                                        @if($topic->code)<span class="font-medium mr-1">{{ $topic->code }}:</span>@endif
                                        {{ $topic->name }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Keynote Speakers --}}
                            @if($conf->keynote_speakers_count > 0)
                            <div class="mt-4 pt-4 border-t">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Keynote Speakers ({{ $conf->keynote_speakers_count }})</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($conf->keynoteSpeakers as $speaker)
                                    <div class="flex items-center gap-3 p-2 bg-white rounded-lg border">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-bold text-indigo-600">{{ substr($speaker->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $speaker->title ? $speaker->title . ' ' : '' }}{{ $speaker->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $speaker->institution ?? '' }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="mt-4 pt-4 border-t flex justify-end">
                                <a href="{{ route('admin.conferences.edit', $conf) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit Kegiatan Ini
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <p class="text-gray-500 font-medium">Belum ada kegiatan prosiding</p>
                            <p class="text-gray-400 text-sm mt-1">Klik "Tambah Kegiatan" untuk membuat kegiatan baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($conferences->hasPages())
        <div class="px-6 py-3 border-t bg-gray-50">{{ $conferences->links() }}</div>
        @endif
    </div>
</div>
