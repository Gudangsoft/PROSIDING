<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Template Email</h2>
        <p class="text-sm text-gray-500 mt-1">Kustomisasi subjek dan isi email otomatis yang dikirim sistem per kegiatan. Jika dikosongkan, sistem menggunakan template default.</p>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 text-sm flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 text-sm">{{ session('error') }}</div>
    @endif

    {{-- Select Conference --}}
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kegiatan / Konferensi</label>
        <select wire:model.live="selectedConferenceId" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 max-w-lg">
            <option value="">— Pilih kegiatan —</option>
            @foreach($conferences as $conf)
            <option value="{{ $conf->id }}">{{ $conf->name }}{{ $conf->acronym ? ' (' . $conf->acronym . ')' : '' }}</option>
            @endforeach
        </select>
        @if($conferences->isEmpty())
        <p class="text-xs text-amber-600 mt-2">Belum ada kegiatan. <a href="{{ route('admin.conferences.create') }}" class="underline">Buat kegiatan terlebih dahulu</a>.</p>
        @endif
    </div>

    @if($selectedConferenceId)
    <form wire:submit="save">
        @php
            $colorMap = [
                'blue'    => ['bg' => 'bg-blue-50',    'border' => 'border-blue-200',   'badge' => 'bg-blue-100 text-blue-700',    'icon' => 'text-blue-500'],
                'green'   => ['bg' => 'bg-green-50',   'border' => 'border-green-200',  'badge' => 'bg-green-100 text-green-700',  'icon' => 'text-green-500'],
                'yellow'  => ['bg' => 'bg-yellow-50',  'border' => 'border-yellow-200', 'badge' => 'bg-yellow-100 text-yellow-700','icon' => 'text-yellow-500'],
                'indigo'  => ['bg' => 'bg-indigo-50',  'border' => 'border-indigo-200', 'badge' => 'bg-indigo-100 text-indigo-700','icon' => 'text-indigo-500'],
                'purple'  => ['bg' => 'bg-purple-50',  'border' => 'border-purple-200', 'badge' => 'bg-purple-100 text-purple-700','icon' => 'text-purple-500'],
                'emerald' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200','badge' => 'bg-emerald-100 text-emerald-700','icon' => 'text-emerald-500'],
                'red'     => ['bg' => 'bg-red-50',     'border' => 'border-red-200',    'badge' => 'bg-red-100 text-red-700',     'icon' => 'text-red-500'],
                'orange'  => ['bg' => 'bg-orange-50',  'border' => 'border-orange-200', 'badge' => 'bg-orange-100 text-orange-700','icon' => 'text-orange-500'],
            ];
        @endphp

        <div class="space-y-4">
            @foreach($emailTypes as $typeKey => $typeCfg)
            @php
                $color = $typeCfg['color'] ?? 'blue';
                $cm = $colorMap[$color] ?? $colorMap['blue'];
                $tpl = $emailTemplates[$typeKey] ?? ['id' => null, 'subject' => '', 'body' => '', 'is_active' => true];
                $isCustomized = !empty($tpl['subject']) || !empty($tpl['body']);
            @endphp
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden" wire:key="tpl-{{ $typeKey }}">
                {{-- Card Header --}}
                <div class="flex items-center gap-3 px-5 py-3.5 {{ $cm['bg'] }} border-b {{ $cm['border'] }}">
                    <div class="w-8 h-8 rounded-lg border {{ $cm['border'] }} {{ $cm['bg'] }} flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 {{ $cm['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typeCfg['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold text-gray-800 text-sm">{{ $typeCfg['label'] }}</span>
                            @if($isCustomized)
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $cm['badge'] }}">✏ Dikustomisasi</span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Default</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $typeCfg['desc'] }}</p>
                    </div>
                    @if($isCustomized)
                    <button type="button"
                        wire:click="resetTemplate('{{ $typeKey }}')"
                        wire:confirm="Reset template ini ke default? Kustomisasi akan dihapus."
                        class="shrink-0 text-xs px-3 py-1.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset Default
                    </button>
                    @endif
                </div>

                {{-- Card Body --}}
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">
                            Subjek Email
                            <span class="text-gray-400 font-normal ml-1">— kosongkan untuk gunakan default</span>
                        </label>
                        <input type="text"
                            wire:model="emailTemplates.{{ $typeKey }}.subject"
                            placeholder="{{ $typeCfg['default_subject'] }}"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">
                            Isi Email
                            <span class="text-gray-400 font-normal ml-1">— HTML diperbolehkan, kosongkan untuk gunakan default</span>
                        </label>
                        <textarea wire:model="emailTemplates.{{ $typeKey }}.body"
                            rows="5"
                            placeholder="Tulis isi email di sini... Contoh HTML: <p>Halo <b>{{ '{{ $name }}' }}</b>, ...</p>"
                            class="w-full px-3 py-2 border rounded-lg text-sm font-mono focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    {{-- Variables --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-500 font-medium">Variabel:</span>
                        @foreach($typeCfg['vars'] as $var)
                        <code class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs border border-gray-200 cursor-pointer hover:bg-gray-200 transition"
                            title="Klik untuk salin"
                            onclick="navigator.clipboard.writeText('{{ $var }}');this.textContent='✓ Disalin!';setTimeout(()=>this.textContent='{{ $var }}',1500)">{{ $var }}</code>
                        @endforeach
                    </div>

                    {{-- Active toggle --}}
                    <div class="flex items-center gap-2 pt-1 border-t">
                        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600">
                            <input type="checkbox"
                                wire:model="emailTemplates.{{ $typeKey }}.is_active"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            Aktifkan template kustom ini
                        </label>
                        <span class="text-xs text-gray-400">(nonaktif = gunakan template default)</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Save Button --}}
        <div class="mt-6 flex justify-end">
            <button type="submit"
                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm inline-flex items-center gap-2">
                <span wire:loading.remove wire:target="save">
                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Semua Template
                </span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Menyimpan...
                </span>
            </button>
        </div>
    </form>
    @else
    <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
        <svg class="w-14 h-14 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <p class="text-gray-500 font-medium">Pilih kegiatan di atas</p>
        <p class="text-gray-400 text-sm mt-1">Template email akan ditampilkan setelah kegiatan dipilih.</p>
    </div>
    @endif
</div>
