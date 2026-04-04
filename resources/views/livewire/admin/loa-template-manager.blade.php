<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Template Letter of Acceptance</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola template LOA untuk Paper dan Abstrak</p>
            </div>
            <div class="flex items-center gap-3">
                <select wire:model.live="conferenceId" class="px-3 py-2 border border-gray-300 rounded-xl text-sm min-w-[200px] focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="0">-- Semua Konferensi --</option>
                    @foreach($conferences as $conf)
                    <option value="{{ $conf->id }}">{{ $conf->name }}</option>
                    @endforeach
                </select>
                <button wire:click="openModal" 
                        class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl text-sm font-semibold hover:from-emerald-600 hover:to-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Template
                </button>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Search --}}
        <div class="mb-6">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari template..." 
                   class="px-4 py-2 border border-gray-300 rounded-xl text-sm w-full max-w-xs focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>

        {{-- Template Grid --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($templates as $template)
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        {{-- Preview Area --}}
                        <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-50 relative overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center p-4">
                                <div class="w-full h-full bg-white rounded-lg shadow-sm border border-gray-200 p-3 text-[6px] overflow-hidden">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($template->html_template), 300) !!}
                                </div>
                            </div>
                            @if($template->is_default)
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 bg-emerald-500 text-white text-[10px] font-bold rounded-full shadow">DEFAULT</span>
                            </div>
                            @endif
                            @if(!$template->is_active)
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-1 bg-gray-500 text-white text-[10px] font-bold rounded-full shadow">NONAKTIF</span>
                            </div>
                            @endif
                            {{-- Hover Overlay --}}
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <button wire:click="preview({{ $template->id }})" class="px-3 py-2 bg-white text-gray-800 rounded-lg text-xs font-medium hover:bg-gray-100 transition">
                                    👁️ Preview
                                </button>
                                <button wire:click="openModal({{ $template->id }})" class="px-3 py-2 bg-emerald-500 text-white rounded-lg text-xs font-medium hover:bg-emerald-600 transition">
                                    ✏️ Edit
                                </button>
                            </div>
                        </div>
                        {{-- Info --}}
                        <div class="p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $template->name }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($template->type === 'paper')
                                            <span class="text-blue-600">Paper</span>
                                        @elseif($template->type === 'abstract')
                                            <span class="text-purple-600">Abstrak</span>
                                        @else
                                            <span class="text-emerald-600">Semua Tipe</span>
                                        @endif
                                        • {{ ucfirst($template->orientation) }}
                                    </p>
                                </div>
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-medium rounded">{{ strtoupper($template->paper_size) }}</span>
                            </div>
                            {{-- Images indicators --}}
                            <div class="flex items-center gap-2 mt-3 flex-wrap">
                                @if($template->signature_image)
                                <span class="text-[10px] px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full">✍️ TTD 1</span>
                                @endif
                                @if($template->signature2_image)
                                <span class="text-[10px] px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-full">✍️ TTD 2</span>
                                @endif
                                @if($template->stamp_image)
                                <span class="text-[10px] px-2 py-0.5 bg-purple-50 text-purple-600 rounded-full">🔖 Stempel</span>
                                @endif
                                @if($template->logo_image)
                                <span class="text-[10px] px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-full">🏛️ Logo</span>
                                @endif
                                @if($template->letterhead_image)
                                <span class="text-[10px] px-2 py-0.5 bg-amber-50 text-amber-600 rounded-full">📄 Kop</span>
                                @endif
                            </div>
                            {{-- Actions --}}
                            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                                @if(!$template->is_default)
                                <button wire:click="setDefault({{ $template->id }})" class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">Set Default</button>
                                @endif
                                <button wire:click="toggleActive({{ $template->id }})" class="text-xs {{ $template->is_active ? 'text-amber-600 hover:text-amber-800' : 'text-blue-600 hover:text-blue-800' }}">
                                    {{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                                <button wire:click="duplicate({{ $template->id }})" class="text-xs text-gray-500 hover:text-gray-700">Duplikasi</button>
                                <button wire:click="delete({{ $template->id }})" wire:confirm="Hapus template ini?" class="text-xs text-red-500 hover:text-red-700 ml-auto">Hapus</button>
                            </div>
                        </div>
                    </div>
                    @empty
                    {{-- Empty State --}}
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 mb-2">Belum ada template LOA</p>
                            <p class="text-sm text-gray-400 mb-6">Pilih dari template siap pakai di bawah ini atau buat template baru</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                @if($templates->hasPages())
                <div class="mt-6">{{ $templates->links() }}</div>
                @endif
            </div>

            {{-- Predefined Templates Section --}}
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Template Siap Pakai</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($predefinedTemplates as $key => $pt)
                    <button wire:click="usePredefinedTemplate('{{ $key }}')" wire:click.prefetch="openModal"
                            class="group p-4 bg-white border-2 border-dashed border-gray-200 rounded-xl hover:border-emerald-400 hover:bg-emerald-50/50 transition-all text-left">
                        <div class="w-12 h-12 rounded-xl mb-3 flex items-center justify-center text-2xl
                            {{ $key === 'formal-classic' ? 'bg-gradient-to-br from-slate-100 to-gray-50' : '' }}
                            {{ $key === 'modern-simple' ? 'bg-gradient-to-br from-blue-100 to-indigo-50' : '' }}
                            {{ $key === 'academic-formal' ? 'bg-gradient-to-br from-amber-100 to-yellow-50' : '' }}">
                            {{ $key === 'formal-classic' ? '📜' : '' }}
                            {{ $key === 'modern-simple' ? '💎' : '' }}
                            {{ $key === 'academic-formal' ? '🎓' : '' }}
                        </div>
                        <h4 class="font-semibold text-gray-800 text-sm group-hover:text-emerald-600 transition">{{ $pt['name'] }}</h4>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $pt['preview'] ?? 'Template LOA profesional' }}</p>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center p-4 overflow-y-auto" wire:click.self="closeModal">
        <div class="bg-white rounded-2xl w-full max-w-5xl shadow-2xl my-8" wire:click.stop>
            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-200 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-t-2xl">
                <h3 class="text-lg font-bold text-white">{{ $editingId ? 'Edit Template LOA' : 'Buat Template LOA Baru' }}</h3>
                <button wire:click="closeModal" class="text-white/80 hover:text-white text-2xl">&times;</button>
            </div>

            <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Left Column - Settings --}}
                    <div class="space-y-5">
                        {{-- Basic Info --}}
                        <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                            <h4 class="font-semibold text-gray-700 flex items-center gap-2">
                                <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xs">1</span>
                                Informasi Dasar
                            </h4>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Template *</label>
                                <input wire:model="name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: LOA Prosiding 2026">
                                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe</label>
                                    <select wire:model="type" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                                        <option value="all">Semua (Paper & Abstrak)</option>
                                        <option value="paper">Paper</option>
                                        <option value="abstract">Abstrak</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Orientasi</label>
                                    <select wire:model="orientation" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                                        <option value="portrait">Portrait</option>
                                        <option value="landscape">Landscape</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Ukuran Kertas</label>
                                    <select wire:model="paperSize" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                                        <option value="a4">A4</option>
                                        <option value="letter">Letter</option>
                                        <option value="legal">Legal</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input wire:model="isDefault" type="checkbox" class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                        <span class="text-sm text-gray-700">Jadikan default</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Signature & Images --}}
                        <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                            <h4 class="font-semibold text-gray-700 flex items-center gap-2">
                                <span class="w-6 h-6 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-xs">2</span>
                                Tanda Tangan & Gambar
                            </h4>

                            {{-- Letterhead --}}
                            <div class="border border-gray-200 rounded-lg p-3 bg-white">
                                <label class="block text-xs font-semibold text-gray-700 mb-2">📄 Kop Surat / Letterhead</label>
                                <div class="flex items-center gap-3">
                                    @if($existingLetterhead)
                                    <img src="{{ asset('storage/'.$existingLetterhead) }}" class="h-10 border rounded">
                                    @elseif($letterheadFile)
                                    <img src="{{ $letterheadFile->temporaryUrl() }}" class="h-10 border rounded">
                                    @endif
                                    <input wire:model="letterheadFile" type="file" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-amber-50 file:text-amber-700">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Variabel: <code class="bg-gray-200 px-1 rounded">&#123;&#123;kop_surat&#125;&#125;</code> atau <code class="bg-gray-200 px-1 rounded">&#123;&#123;letterhead&#125;&#125;</code></p>
                            </div>

                            {{-- Logo --}}
                            <div class="border border-gray-200 rounded-lg p-3 bg-white">
                                <label class="block text-xs font-semibold text-gray-700 mb-2">🏛️ Logo</label>
                                <div class="flex items-center gap-3">
                                    @if($existingLogo)
                                    <img src="{{ asset('storage/'.$existingLogo) }}" class="h-10 border rounded">
                                    @elseif($logoFile)
                                    <img src="{{ $logoFile->temporaryUrl() }}" class="h-10 border rounded">
                                    @endif
                                    <input wire:model="logoFile" type="file" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-emerald-50 file:text-emerald-700">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Variabel: <code class="bg-gray-200 px-1 rounded">&#123;&#123;logo&#125;&#125;</code></p>
                            </div>

                            {{-- Signature 1 --}}
                            <div class="border border-gray-200 rounded-lg p-3 bg-white">
                                <label class="block text-xs font-semibold text-gray-700 mb-2">✍️ Tanda Tangan 1</label>
                                <div class="flex items-center gap-3 mb-2">
                                    @if($existingSignature)
                                    <img src="{{ asset('storage/'.$existingSignature) }}" class="h-10 border rounded">
                                    @elseif($signatureFile)
                                    <img src="{{ $signatureFile->temporaryUrl() }}" class="h-10 border rounded">
                                    @endif
                                    <input wire:model="signatureFile" type="file" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700">
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <input wire:model="signatureName" type="text" placeholder="Nama penanda tangan" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                                    <input wire:model="signaturePosition" type="text" placeholder="Jabatan" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Variabel: <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd&#125;&#125;</code>, <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd_nama&#125;&#125;</code>, <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd_jabatan&#125;&#125;</code></p>
                            </div>

                            {{-- Signature 2 --}}
                            <div class="border border-gray-200 rounded-lg p-3 bg-white">
                                <label class="block text-xs font-semibold text-gray-700 mb-2">✍️ Tanda Tangan 2 (Opsional)</label>
                                <div class="flex items-center gap-3 mb-2">
                                    @if($existingSignature2)
                                    <img src="{{ asset('storage/'.$existingSignature2) }}" class="h-10 border rounded">
                                    @elseif($signature2File)
                                    <img src="{{ $signature2File->temporaryUrl() }}" class="h-10 border rounded">
                                    @endif
                                    <input wire:model="signature2File" type="file" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700">
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <input wire:model="signature2Name" type="text" placeholder="Nama penanda tangan 2" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                                    <input wire:model="signature2Position" type="text" placeholder="Jabatan" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Variabel: <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd2&#125;&#125;</code>, <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd2_nama&#125;&#125;</code>, <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd2_jabatan&#125;&#125;</code></p>
                            </div>

                            {{-- Stamp --}}
                            <div class="border border-gray-200 rounded-lg p-3 bg-white">
                                <label class="block text-xs font-semibold text-gray-700 mb-2">🔖 Stempel (Opsional)</label>
                                <div class="flex items-center gap-3">
                                    @if($existingStamp)
                                    <img src="{{ asset('storage/'.$existingStamp) }}" class="h-10 border rounded">
                                    @elseif($stampFile)
                                    <img src="{{ $stampFile->temporaryUrl() }}" class="h-10 border rounded">
                                    @endif
                                    <input wire:model="stampFile" type="file" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-purple-50 file:text-purple-700">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Variabel: <code class="bg-gray-200 px-1 rounded">&#123;&#123;stempel&#125;&#125;</code> atau <code class="bg-gray-200 px-1 rounded">&#123;&#123;stamp&#125;&#125;</code></p>
                            </div>
                        </div>

                        {{-- Variables Reference --}}
                        <div class="bg-amber-50 rounded-xl p-4">
                            <h4 class="font-semibold text-amber-800 text-sm mb-2">📝 Variabel yang Tersedia</h4>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs">
                                <code class="text-amber-700">&#123;&#123;judul&#125;&#125;</code><span class="text-gray-600">Judul paper/abstrak</span>
                                <code class="text-amber-700">&#123;&#123;nama&#125;&#125;</code><span class="text-gray-600">Nama penulis</span>
                                <code class="text-amber-700">&#123;&#123;email&#125;&#125;</code><span class="text-gray-600">Email penulis</span>
                                <code class="text-amber-700">&#123;&#123;institusi&#125;&#125;</code><span class="text-gray-600">Instansi/afiliasi</span>
                                <code class="text-amber-700">&#123;&#123;nomor_loa&#125;&#125;</code><span class="text-gray-600">No. LOA</span>
                                <code class="text-amber-700">&#123;&#123;konferensi&#125;&#125;</code><span class="text-gray-600">Nama event</span>
                                <code class="text-amber-700">&#123;&#123;tanggal&#125;&#125;</code><span class="text-gray-600">Tanggal terbit</span>
                                <code class="text-amber-700">&#123;&#123;tahun&#125;&#125;</code><span class="text-gray-600">Tahun</span>
                                <code class="text-amber-700">&#123;&#123;topik&#125;&#125;</code><span class="text-gray-600">Topik/track</span>
                                <code class="text-amber-700">&#123;&#123;tanggal_konferensi&#125;&#125;</code><span class="text-gray-600">Tanggal event</span>
                                <code class="text-amber-700">&#123;&#123;tempat&#125;&#125;</code><span class="text-gray-600">Lokasi event</span>
                            </div>
                        </div>

                        {{-- QR Code Info --}}
                        <div class="bg-purple-50 rounded-xl p-4">
                            <h4 class="font-semibold text-purple-800 text-sm mb-2">📱 QR Code Verifikasi</h4>
                            <p class="text-xs text-purple-700 mb-2">QR Code akan digenerate otomatis berisi link verifikasi keaslian LOA.</p>
                            <p class="text-[10px] text-purple-600">Variabel: <code class="bg-purple-200 px-1 rounded">&#123;&#123;qrcode&#125;&#125;</code></p>
                        </div>
                    </div>

                    {{-- Right Column - HTML Editor --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-700 flex items-center gap-2">
                                <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs">3</span>
                                Kode HTML Template
                            </h4>
                        </div>
                        
                        {{-- Quick Templates --}}
                        <div class="flex flex-wrap gap-2">
                            @foreach($predefinedTemplates as $key => $pt)
                            <button wire:click="usePredefinedTemplate('{{ $key }}')" 
                                    class="px-2.5 py-1 text-xs bg-white border border-gray-200 rounded-lg hover:border-emerald-400 hover:bg-emerald-50 transition">
                                {{ $pt['name'] }}
                            </button>
                            @endforeach
                        </div>

                        <textarea wire:model="htmlTemplate" rows="25" 
                                  class="w-full font-mono text-xs border border-gray-300 rounded-xl p-3 resize-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50"
                                  placeholder="Masukkan kode HTML template LOA..."></textarea>
                        @error('htmlTemplate') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="flex justify-end gap-3 p-5 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                <button wire:click="closeModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-100 transition">Batal</button>
                <button wire:click="save" class="px-5 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl text-sm font-semibold hover:from-emerald-600 hover:to-emerald-700 transition shadow-lg shadow-emerald-200">
                    {{ $editingId ? 'Simpan Perubahan' : 'Buat Template' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Preview Modal --}}
    @if($showPreviewModal)
    <div class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4" wire:click.self="closePreview">
        <div class="bg-white rounded-2xl w-full max-w-4xl shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-semibold text-gray-800">Preview LOA</h3>
                <div class="flex items-center gap-2">
                    <button wire:click="closePreview" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
            </div>
            <div class="p-4 bg-gray-100 max-h-[75vh] overflow-auto">
                <div class="bg-white shadow-xl rounded-lg overflow-hidden mx-auto" style="max-width: 800px;">
                    <iframe srcdoc="{{ $previewHtml }}" class="w-full border-0" style="min-height: 800px;"></iframe>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
