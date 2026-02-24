<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Sertifikat Otomatis</h2>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        {{-- Controls --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6">
            <div class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Konferensi</label>
                    <select wire:model.live="conferenceId" class="px-3 py-2 border border-gray-300 rounded-xl text-sm min-w-[220px]">
                        <option value="0">-- Pilih Konferensi --</option>
                        @foreach($conferences as $conf)
                        <option value="{{ $conf->id }}">{{ $conf->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe Sertifikat</label>
                    <select wire:model.live="type" class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
                        <option value="participant">Peserta</option>
                        <option value="presenter">Presenter</option>
                        <option value="reviewer">Reviewer</option>
                        <option value="committee">Panitia</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Nama</label>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari..." class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
                </div>
                <button wire:click="generateBulk" wire:confirm="Generate sertifikat untuk semua penerima yang memenuhi syarat?" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition">
                    ⚡ Generate Sertifikat
                </button>
                <button wire:click="$set('showTemplate', true)" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl text-sm hover:bg-gray-50 transition">
                    ✏️ Edit Template
                </button>
            </div>
        </div>

        {{-- Template Editor Modal --}}
        @if($showTemplate)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-3xl shadow-2xl">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Edit Template Sertifikat</h3>
                    <button wire:click="$set('showTemplate', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
                <div class="p-5">
                    <p class="text-xs text-gray-500 mb-2">Gunakan variabel: <code class="bg-gray-100 px-1 rounded">&#123;&#123;nama&#125;&#125;</code>, <code class="bg-gray-100 px-1 rounded">&#123;&#123;nomor&#125;&#125;</code>, <code class="bg-gray-100 px-1 rounded">&#123;&#123;tipe&#125;&#125;</code>, <code class="bg-gray-100 px-1 rounded">&#123;&#123;konferensi&#125;&#125;</code>, <code class="bg-gray-100 px-1 rounded">&#123;&#123;tanggal&#125;&#125;</code></p>
                    <textarea wire:model="templateHtml" rows="12" class="w-full font-mono text-xs border border-gray-300 rounded-xl p-3 resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-3 p-5 border-t border-gray-200">
                    <button wire:click="$set('showTemplate', false)" class="px-4 py-2 border border-gray-300 rounded-xl text-sm">Batal</button>
                    <button wire:click="saveTemplate" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold">Simpan Template</button>
                </div>
            </div>
        </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">No. Sertifikat</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Nama Penerima</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Tipe</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden lg:table-cell">Konferensi</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Tanggal</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($certs as $cert)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $cert->certificate_number }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $cert->recipient_name }}</td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-blue-50 text-blue-700 rounded-full">{{ $cert->type_label }}</span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-500">{{ $cert->conference?->name }}</td>
                        <td class="px-4 py-3 hidden md:table-cell text-xs text-gray-500">{{ $cert->issued_at?->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.certificate.download', $cert->id) }}" class="text-emerald-600 hover:text-emerald-800 text-xs font-medium px-3 py-1.5 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition">↓ PDF</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-12 text-gray-400">Belum ada sertifikat untuk filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($certs->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">{{ $certs->links() }}</div>
            @endif
        </div>
    </div>
</div>
