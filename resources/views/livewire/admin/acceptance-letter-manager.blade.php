<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-4">
            <div class="w-9 h-9 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h1 class="text-sm font-bold text-gray-900 leading-none">Template LOA</h1>
                <p class="text-[11px] text-gray-400 mt-0.5">Kelola template Letter of Acceptance (LOA digenerate otomatis saat abstrak disetujui)</p>
            </div>
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 ml-2">
                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <select wire:model.live="conferenceId" class="bg-transparent text-sm text-gray-700 font-medium outline-none pr-2 min-w-[200px]">
                    <option value="0">— Pilih Konferensi —</option>
                    @foreach($conferences as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="flex-1"></div>
            
            @if($conferenceId)
            <button wire:click="openTemplateModal"
                class="flex items-center gap-1.5 px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Template
            </button>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-gray-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl text-sm font-medium">
        <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center shrink-0">
            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
        </div>
        {{ session('success') }}
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-6 py-6">
        @if(!$conferenceId)
        <div class="flex flex-col items-center justify-center h-[500px] bg-white border border-dashed border-gray-200 rounded-2xl text-center">
            <div class="w-20 h-20 bg-green-50 rounded-2xl flex items-center justify-center mb-5">
                <svg class="w-10 h-10 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-2">Pilih Konferensi</h3>
            <p class="text-sm text-gray-400">Pilih konferensi untuk mengelola template LOA.</p>
        </div>
        @else
        
        {{-- Info Box --}}
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-blue-800">LOA Digenerate Otomatis</p>
                <p class="text-xs text-blue-600">LOA akan digenerate dan dikirim otomatis ke author saat abstrak disetujui. Halaman ini hanya untuk mengelola template LOA.</p>
            </div>
        </div>

        {{-- Templates Section --}}
        <div>
            {{-- Template Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                @forelse($templates as $template)
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-lg transition-all duration-300 group">
                    {{-- Preview Area --}}
                    <div class="h-40 bg-gradient-to-br from-gray-100 to-gray-50 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center p-4">
                            <div class="w-full h-full bg-white rounded-lg shadow-sm border border-gray-200 p-2 text-[5px] overflow-hidden leading-tight">
                                {!! \Illuminate\Support\Str::limit(strip_tags($template->html_template), 200) !!}
                            </div>
                        </div>
                        @if($template->is_default)
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 bg-emerald-500 text-white text-[9px] font-bold rounded-full shadow">DEFAULT</span>
                        </div>
                        @endif
                        @if(!$template->is_active)
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-1 bg-gray-500 text-white text-[9px] font-bold rounded-full shadow">NONAKTIF</span>
                        </div>
                        @endif
                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <button wire:click="previewTemplate({{ $template->id }})" class="px-3 py-1.5 bg-white text-gray-800 rounded-lg text-xs font-medium hover:bg-gray-100 transition">
                                👁️ Preview
                            </button>
                            <button wire:click="openTemplateModal({{ $template->id }})" class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-medium hover:bg-emerald-600 transition">
                                ✏️ Edit
                            </button>
                        </div>
                    </div>
                    {{-- Info --}}
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800 text-sm">{{ $template->name }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $template->type_label }} • {{ ucfirst($template->orientation) }}</p>
                            </div>
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[9px] font-medium rounded">{{ strtoupper($template->paper_size) }}</span>
                        </div>
                        {{-- Images indicators --}}
                        <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                            @if($template->signature_image)<span class="text-[9px] px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded-full">✍️ TTD</span>@endif
                            @if($template->logo_image)<span class="text-[9px] px-1.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-full">🏛️ Logo</span>@endif
                            @if($template->letterhead_image)<span class="text-[9px] px-1.5 py-0.5 bg-amber-50 text-amber-600 rounded-full">📄 Kop</span>@endif
                            @if($template->stamp_image)<span class="text-[9px] px-1.5 py-0.5 bg-purple-50 text-purple-600 rounded-full">🔖 Stempel</span>@endif
                        </div>
                        {{-- Actions --}}
                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                            @if(!$template->is_default)
                            <button wire:click="setDefaultTemplate({{ $template->id }})" class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">Set Default</button>
                            @endif
                            <button wire:click="deleteTemplate({{ $template->id }})" wire:confirm="Hapus template ini?" class="text-xs text-red-500 hover:text-red-700 ml-auto">Hapus</button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12 text-gray-400">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    </div>
                    <p class="text-sm">Belum ada template custom</p>
                    <p class="text-xs mt-1">Pilih template siap pakai di bawah atau buat baru</p>
                </div>
                @endforelse
            </div>

            {{-- Predefined Templates --}}
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5">
                <h3 class="text-sm font-bold text-gray-700 mb-4">Template Siap Pakai</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($predefinedTemplates as $key => $pt)
                    <button wire:click="createFromPredefined('{{ $key }}')"
                        class="group p-4 bg-white border-2 border-dashed border-gray-200 rounded-xl hover:border-emerald-400 hover:bg-emerald-50/50 transition-all text-left">
                        <div class="w-10 h-10 rounded-lg mb-2 flex items-center justify-center text-xl
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
        @endif
    </div>

    {{-- Template Modal --}}
    @if($showTemplateModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" wire:click.self="closeTemplateModal">
        <div class="bg-white rounded-2xl w-full max-w-5xl shadow-2xl max-h-[90vh] flex flex-col" wire:click.stop>
            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-200 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-t-2xl shrink-0">
                <h3 class="text-lg font-bold text-white">{{ $editingTemplateId ? 'Edit Template LOA' : 'Buat Template LOA Baru' }}</h3>
                <button wire:click="closeTemplateModal" class="text-white/80 hover:text-white text-2xl">&times;</button>
            </div>

            <div class="p-6 overflow-y-auto flex-1">
                {{-- Validation Errors --}}
                @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-red-800">Terdapat kesalahan:</p>
                            <ul class="text-xs text-red-600 mt-1 list-disc pl-4">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
                
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
                                <input wire:model="templateName" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: LOA Prosiding 2026">
                                @error('templateName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe</label>
                                    <select wire:model="templateType" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                                        <option value="all">Semua</option>
                                        <option value="paper">Paper</option>
                                        <option value="abstract">Abstrak</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Orientasi</label>
                                    <select wire:model="templateOrientation" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                                        <option value="portrait">Portrait</option>
                                        <option value="landscape">Landscape</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Ukuran Kertas</label>
                                    <select wire:model="templatePaperSize" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                                        <option value="a4">A4</option>
                                        <option value="letter">Letter</option>
                                        <option value="legal">Legal</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input wire:model="templateIsDefault" type="checkbox" class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                        <span class="text-sm text-gray-700">Jadikan default</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Images Upload --}}
                        <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                            <h4 class="font-semibold text-gray-700 flex items-center gap-2">
                                <span class="w-6 h-6 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-xs">2</span>
                                Logo & Tanda Tangan
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
                                <p class="text-[10px] text-gray-400 mt-1">Variabel: <code class="bg-gray-200 px-1 rounded">&#123;&#123;kop_surat&#125;&#125;</code></p>
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
                                <label class="block text-xs font-semibold text-gray-700 mb-2">✍️ Tanda Tangan</label>
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
                                <p class="text-[10px] text-gray-400 mt-1">Variabel: <code class="bg-gray-200 px-1 rounded">&#123;&#123;stempel&#125;&#125;</code></p>
                            </div>
                        </div>

                        {{-- QR Code Info --}}
                        <div class="bg-purple-50 rounded-xl p-4">
                            <h4 class="font-semibold text-purple-800 text-sm mb-2">📱 QR Code Anti Pemalsuan</h4>
                            <p class="text-xs text-purple-700 mb-2">QR Code akan digenerate otomatis berisi link verifikasi keaslian LOA yang bisa di-scan.</p>
                            <p class="text-[10px] text-purple-600">Variabel: <code class="bg-purple-200 px-1 rounded">&#123;&#123;qrcode&#125;&#125;</code></p>
                        </div>

                        {{-- Variables Reference --}}
                        <div class="bg-amber-50 rounded-xl p-4">
                            <h4 class="font-semibold text-amber-800 text-sm mb-2">📝 Variabel Tersedia</h4>
                            <div class="grid grid-cols-2 gap-x-3 gap-y-0.5 text-[10px]">
                                <code class="text-amber-700">&#123;&#123;judul&#125;&#125;</code><span class="text-gray-600">Judul paper</span>
                                <code class="text-amber-700">&#123;&#123;nama&#125;&#125;</code><span class="text-gray-600">Nama penulis</span>
                                <code class="text-amber-700">&#123;&#123;email&#125;&#125;</code><span class="text-gray-600">Email</span>
                                <code class="text-amber-700">&#123;&#123;institusi&#125;&#125;</code><span class="text-gray-600">Instansi</span>
                                <code class="text-amber-700">&#123;&#123;nomor_loa&#125;&#125;</code><span class="text-gray-600">No. LOA</span>
                                <code class="text-amber-700">&#123;&#123;konferensi&#125;&#125;</code><span class="text-gray-600">Nama event</span>
                                <code class="text-amber-700">&#123;&#123;tanggal&#125;&#125;</code><span class="text-gray-600">Tanggal</span>
                                <code class="text-amber-700">&#123;&#123;tempat&#125;&#125;</code><span class="text-gray-600">Lokasi</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column - HTML Editor --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-700 flex items-center gap-2">
                                <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs">3</span>
                                Kode HTML Template
                            </h4>
                            <button wire:click="previewTemplate" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-medium transition flex items-center gap-1">
                                👁️ Preview
                            </button>
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

                        <textarea wire:model="templateHtml" rows="22" 
                                  class="w-full font-mono text-xs border border-gray-300 rounded-xl p-3 resize-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50"
                                  placeholder="Masukkan kode HTML template LOA..."></textarea>
                        @error('templateHtml') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="flex justify-end gap-3 p-5 border-t border-gray-200 bg-gray-50 rounded-b-2xl shrink-0">
                <button type="button" wire:click="closeTemplateModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-100 transition">Batal</button>
                <button type="button" wire:click="saveTemplate" wire:loading.attr="disabled" wire:target="saveTemplate"
                    class="px-5 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl text-sm font-semibold hover:from-emerald-600 hover:to-emerald-700 transition shadow-lg shadow-emerald-200 disabled:opacity-50">
                    <span wire:loading.remove wire:target="saveTemplate">{{ $editingTemplateId ? 'Simpan Perubahan' : 'Buat Template' }}</span>
                    <span wire:loading wire:target="saveTemplate">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Preview Modal --}}
    @if($showPreviewModal)
    <div class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4" wire:click.self="closePreviewModal">
        <div class="bg-white rounded-2xl w-full max-w-4xl shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-semibold text-gray-800">Preview LOA</h3>
                <button wire:click="closePreviewModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
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