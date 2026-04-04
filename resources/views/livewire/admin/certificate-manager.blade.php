<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Sertifikat</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola template dan sertifikat peserta</p>
            </div>
            <div class="flex items-center gap-2">
                <select wire:model.live="conferenceId" class="px-3 py-2 border border-gray-300 rounded-xl text-sm min-w-[200px] focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="0">-- Semua Konferensi --</option>
                    @foreach($conferences as $conf)
                    <option value="{{ $conf->id }}">{{ $conf->name }}</option>
                    @endforeach
                </select>
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

        {{-- Tabs --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button wire:click="$set('activeTab', 'certificates')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'certificates' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            Sertifikat
                        </span>
                    </button>
                    <button wire:click="$set('activeTab', 'templates')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'templates' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                            Template
                        </span>
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}
            <div class="p-6">
                @if($activeTab === 'certificates')
                    {{-- Certificates Tab --}}
                    <div class="flex flex-wrap gap-4 items-end mb-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe Sertifikat</label>
                            <select wire:model.live="type" class="px-3 py-2 border border-gray-300 rounded-xl text-sm min-w-[160px]">
                                <option value="participant">Peserta</option>
                                <option value="presenter">Presenter</option>
                                <option value="reviewer">Reviewer</option>
                                <option value="committee">Panitia</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Nama</label>
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari penerima..." class="px-3 py-2 border border-gray-300 rounded-xl text-sm min-w-[200px]">
                        </div>
                        <button wire:click="generateBulk" wire:confirm="Generate sertifikat untuk semua penerima yang memenuhi syarat?" 
                                class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl text-sm font-semibold hover:from-emerald-600 hover:to-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Generate Sertifikat
                        </button>
                    </div>

                    {{-- Certificates Table --}}
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
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
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $cert->certificate_number }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $cert->recipient_name }}</td>
                                    <td class="px-4 py-3 hidden md:table-cell">
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full
                                            {{ $cert->type === 'presenter' ? 'bg-purple-100 text-purple-700' : '' }}
                                            {{ $cert->type === 'participant' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $cert->type === 'reviewer' ? 'bg-amber-100 text-amber-700' : '' }}
                                            {{ $cert->type === 'committee' ? 'bg-emerald-100 text-emerald-700' : '' }}">
                                            {{ $cert->type_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-500">{{ $cert->conference?->name }}</td>
                                    <td class="px-4 py-3 hidden md:table-cell text-xs text-gray-500">{{ $cert->issued_at?->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.certificate.download', $cert->id) }}" 
                                               class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-800 text-xs font-medium px-3 py-1.5 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                PDF
                                            </a>
                                            <button wire:click="deleteCertificate({{ $cert->id }})" wire:confirm="Hapus sertifikat ini?"
                                                    class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12">
                                        <div class="flex flex-col items-center text-gray-400">
                                            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                            <p class="text-sm">Belum ada sertifikat</p>
                                            <p class="text-xs mt-1">Klik "Generate Sertifikat" untuk membuat sertifikat</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($certs->hasPages())
                    <div class="mt-4">{{ $certs->links() }}</div>
                    @endif

                @else
                    {{-- Templates Tab --}}
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-sm text-gray-500">Kelola template sertifikat dengan desain profesional</p>
                        <button wire:click="openTemplateModal" 
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl text-sm font-semibold hover:from-blue-600 hover:to-blue-700 transition shadow-lg shadow-blue-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Buat Template Baru
                        </button>
                    </div>

                    {{-- Template Grid --}}
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
                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <button wire:click="previewTemplate({{ $template->id }})" class="px-3 py-2 bg-white text-gray-800 rounded-lg text-xs font-medium hover:bg-gray-100 transition">
                                        👁️ Preview
                                    </button>
                                    <button wire:click="openTemplateModal({{ $template->id }})" class="px-3 py-2 bg-blue-500 text-white rounded-lg text-xs font-medium hover:bg-blue-600 transition">
                                        ✏️ Edit
                                    </button>
                                </div>
                            </div>
                            {{-- Info --}}
                            <div class="p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $template->name }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ $template->type_label }} • {{ ucfirst($template->orientation) }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-medium rounded">{{ strtoupper($template->paper_size) }}</span>
                                </div>
                                {{-- Images indicators --}}
                                <div class="flex items-center gap-2 mt-3">
                                    @if($template->signature_image)
                                    <span class="text-[10px] px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full">✍️ TTD</span>
                                    @endif
                                    @if($template->stamp_image)
                                    <span class="text-[10px] px-2 py-0.5 bg-purple-50 text-purple-600 rounded-full">🔖 Stempel</span>
                                    @endif
                                    @if($template->logo_image)
                                    <span class="text-[10px] px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-full">🏛️ Logo</span>
                                    @endif
                                </div>
                                {{-- Actions --}}
                                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                                    @if(!$template->is_default)
                                    <button wire:click="setDefaultTemplate({{ $template->id }})" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Set Default</button>
                                    @endif
                                    <button wire:click="duplicateTemplate({{ $template->id }})" class="text-xs text-gray-500 hover:text-gray-700">Duplikasi</button>
                                    <button wire:click="deleteTemplate({{ $template->id }})" wire:confirm="Hapus template ini?" class="text-xs text-red-500 hover:text-red-700 ml-auto">Hapus</button>
                                </div>
                            </div>
                        </div>
                        @empty
                        {{-- Empty State with Predefined Templates --}}
                        <div class="col-span-full">
                            <div class="text-center py-8 mb-8">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                                <p class="text-gray-500 mb-2">Belum ada template custom</p>
                                <p class="text-sm text-gray-400">Pilih dari template siap pakai di bawah ini</p>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    {{-- Predefined Templates Section --}}
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Template Siap Pakai</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                            @foreach($predefinedTemplates as $key => $pt)
                            <button wire:click="usePredefinedTemplate('{{ $key }}')" wire:click.prefetch="openTemplateModal"
                                    class="group p-4 bg-gradient-to-br from-gray-50 to-white border-2 border-dashed border-gray-200 rounded-xl hover:border-blue-400 hover:bg-blue-50/50 transition-all text-left">
                                <div class="w-12 h-12 rounded-xl mb-3 flex items-center justify-center text-2xl
                                    {{ $key === 'elegant-gold' ? 'bg-gradient-to-br from-amber-100 to-yellow-50' : '' }}
                                    {{ $key === 'modern-blue' ? 'bg-gradient-to-br from-blue-100 to-indigo-50' : '' }}
                                    {{ $key === 'classic-formal' ? 'bg-gradient-to-br from-slate-100 to-gray-50' : '' }}
                                    {{ $key === 'gradient-purple' ? 'bg-gradient-to-br from-purple-100 to-pink-50' : '' }}
                                    {{ $key === 'minimal-clean' ? 'bg-gradient-to-br from-gray-100 to-white' : '' }}">
                                    {{ $key === 'elegant-gold' ? '🏆' : '' }}
                                    {{ $key === 'modern-blue' ? '💎' : '' }}
                                    {{ $key === 'classic-formal' ? '📜' : '' }}
                                    {{ $key === 'gradient-purple' ? '🎨' : '' }}
                                    {{ $key === 'minimal-clean' ? '✨' : '' }}
                                </div>
                                <h4 class="font-semibold text-gray-800 text-sm group-hover:text-blue-600 transition">{{ $pt['name'] }}</h4>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $pt['preview'] }}</p>
                            </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Template Modal --}}
    @if($showTemplateModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center p-4 overflow-y-auto" wire:click.self="closeTemplateModal">
        <div class="bg-white rounded-2xl w-full max-w-5xl shadow-2xl my-8" wire:click.stop>
            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-2xl">
                <h3 class="text-lg font-bold text-white">{{ $editingTemplateId ? 'Edit Template' : 'Buat Template Baru' }}</h3>
                <button wire:click="closeTemplateModal" class="text-white/80 hover:text-white text-2xl">&times;</button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Left Column - Settings --}}
                    <div class="space-y-5">
                        {{-- Basic Info --}}
                        <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                            <h4 class="font-semibold text-gray-700 flex items-center gap-2">
                                <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs">1</span>
                                Informasi Dasar
                            </h4>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Template *</label>
                                <input wire:model="templateName" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Sertifikat Seminar 2026">
                                @error('templateName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe</label>
                                    <select wire:model="templateType" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                                        <option value="all">Semua Tipe</option>
                                        <option value="participant">Peserta</option>
                                        <option value="presenter">Presenter</option>
                                        <option value="reviewer">Reviewer</option>
                                        <option value="committee">Panitia</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Orientasi</label>
                                    <select wire:model="templateOrientation" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                                        <option value="landscape">Landscape</option>
                                        <option value="portrait">Portrait</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="templateIsDefault" type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Jadikan default</span>
                                </label>
                            </div>
                        </div>

                        {{-- Images Upload --}}
                        <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                            <h4 class="font-semibold text-gray-700 flex items-center gap-2">
                                <span class="w-6 h-6 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-xs">2</span>
                                Tanda Tangan & Gambar
                            </h4>
                            
                            {{-- Layout Info --}}
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-2">
                                <p class="text-xs text-blue-700">📐 <strong>Layout Tanda Tangan:</strong> TTD Ketua (kiri) | QR Code (tengah) | TTD Sekretaris (kanan)</p>
                            </div>

                            {{-- Signature 1 - Ketua --}}
                            <div class="border border-gray-200 rounded-lg p-3 bg-white">
                                <label class="block text-xs font-semibold text-gray-700 mb-2">✍️ Tanda Tangan Ketua (Kiri)</label>
                                <div class="flex items-center gap-3 mb-2">
                                    @if($existingSignature)
                                    <div class="relative">
                                        <img src="{{ asset('storage/'.$existingSignature) }}" class="h-12 border rounded">
                                        <button wire:click="removeExistingImage('signature')" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white rounded-full text-xs flex items-center justify-center">×</button>
                                    </div>
                                    @elseif($signatureFile)
                                    <img src="{{ $signatureFile->temporaryUrl() }}" class="h-12 border rounded">
                                    @endif
                                    <input wire:model="signatureFile" type="file" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <input wire:model="signature1Name" type="text" placeholder="Nama Ketua" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                                    <input wire:model="signature1Position" type="text" placeholder="Jabatan (cth: Ketua Panitia)" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Variabel: <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd1&#125;&#125;</code>, <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd1_nama&#125;&#125;</code>, <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd1_jabatan&#125;&#125;</code></p>
                            </div>

                            {{-- QR Code Info --}}
                            <div class="border border-purple-200 rounded-lg p-3 bg-purple-50">
                                <label class="block text-xs font-semibold text-purple-700 mb-2">📱 QR Code Verifikasi (Tengah)</label>
                                <p class="text-xs text-purple-600">QR Code akan digenerate otomatis berisi link verifikasi keaslian sertifikat.</p>
                                <p class="text-[10px] text-purple-500 mt-1">Variabel: <code class="bg-purple-200 px-1 rounded">&#123;&#123;qrcode&#125;&#125;</code></p>
                            </div>

                            {{-- Signature 2 - Sekretaris --}}
                            <div class="border border-gray-200 rounded-lg p-3 bg-white">
                                <label class="block text-xs font-semibold text-gray-700 mb-2">✍️ Tanda Tangan Sekretaris (Kanan)</label>
                                <div class="flex items-center gap-3 mb-2">
                                    @if($existingSignature2)
                                    <div class="relative">
                                        <img src="{{ asset('storage/'.$existingSignature2) }}" class="h-12 border rounded">
                                        <button wire:click="removeExistingImage('signature2')" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white rounded-full text-xs flex items-center justify-center">×</button>
                                    </div>
                                    @elseif($signature2File)
                                    <img src="{{ $signature2File->temporaryUrl() }}" class="h-12 border rounded">
                                    @endif
                                    <input wire:model="signature2File" type="file" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <input wire:model="signature2Name" type="text" placeholder="Nama Sekretaris" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                                    <input wire:model="signature2Position" type="text" placeholder="Jabatan (cth: Sekretaris)" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Variabel: <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd2&#125;&#125;</code>, <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd2_nama&#125;&#125;</code>, <code class="bg-gray-200 px-1 rounded">&#123;&#123;ttd2_jabatan&#125;&#125;</code></p>
                            </div>

                            {{-- Logo --}}
                            <div class="border border-gray-200 rounded-lg p-3 bg-white">
                                <label class="block text-xs font-semibold text-gray-700 mb-2">🏛️ Logo</label>
                                <div class="flex items-center gap-3">
                                    @if($existingLogo)
                                    <div class="relative">
                                        <img src="{{ asset('storage/'.$existingLogo) }}" class="h-12 border rounded">
                                        <button wire:click="removeExistingImage('logo')" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white rounded-full text-xs flex items-center justify-center">×</button>
                                    </div>
                                    @elseif($logoFile)
                                    <img src="{{ $logoFile->temporaryUrl() }}" class="h-12 border rounded">
                                    @endif
                                    <input wire:model="logoFile" type="file" accept="image/*" class="text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Variabel: <code class="bg-gray-200 px-1 rounded">&#123;&#123;logo&#125;&#125;</code></p>
                            </div>
                        </div>

                        {{-- Variables Reference --}}
                        <div class="bg-amber-50 rounded-xl p-4">
                            <h4 class="font-semibold text-amber-800 text-sm mb-2">📝 Variabel yang Tersedia</h4>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs">
                                <code class="text-amber-700">&#123;&#123;nama&#125;&#125;</code><span class="text-gray-600">Nama penerima</span>
                                <code class="text-amber-700">&#123;&#123;nomor&#125;&#125;</code><span class="text-gray-600">No. sertifikat</span>
                                <code class="text-amber-700">&#123;&#123;tipe&#125;&#125;</code><span class="text-gray-600">Tipe sertifikat</span>
                                <code class="text-amber-700">&#123;&#123;konferensi&#125;&#125;</code><span class="text-gray-600">Nama event</span>
                                <code class="text-amber-700">&#123;&#123;tanggal&#125;&#125;</code><span class="text-gray-600">Tanggal terbit</span>
                                <code class="text-amber-700">&#123;&#123;ttd1&#125;&#125;</code><span class="text-gray-600">TTD Ketua (gambar)</span>
                                <code class="text-amber-700">&#123;&#123;ttd1_nama&#125;&#125;</code><span class="text-gray-600">Nama Ketua</span>
                                <code class="text-amber-700">&#123;&#123;ttd1_jabatan&#125;&#125;</code><span class="text-gray-600">Jabatan Ketua</span>
                                <code class="text-amber-700">&#123;&#123;qrcode&#125;&#125;</code><span class="text-gray-600">QR Code Verifikasi</span>
                                <code class="text-amber-700">&#123;&#123;ttd2&#125;&#125;</code><span class="text-gray-600">TTD Sekretaris (gambar)</span>
                                <code class="text-amber-700">&#123;&#123;ttd2_nama&#125;&#125;</code><span class="text-gray-600">Nama Sekretaris</span>
                                <code class="text-amber-700">&#123;&#123;ttd2_jabatan&#125;&#125;</code><span class="text-gray-600">Jabatan Sekretaris</span>
                                <code class="text-amber-700">&#123;&#123;logo&#125;&#125;</code><span class="text-gray-600">Logo</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column - HTML Editor --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-700 flex items-center gap-2">
                                <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xs">3</span>
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
                                    class="px-2.5 py-1 text-xs bg-white border border-gray-200 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition">
                                {{ $pt['name'] }}
                            </button>
                            @endforeach
                        </div>

                        <textarea wire:model="templateHtml" rows="20" 
                                  class="w-full font-mono text-xs border border-gray-300 rounded-xl p-3 resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                                  placeholder="Masukkan kode HTML template sertifikat..."></textarea>
                        @error('templateHtml') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="flex justify-end gap-3 p-5 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                <button wire:click="closeTemplateModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-100 transition">Batal</button>
                <button wire:click="saveTemplate" class="px-5 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl text-sm font-semibold hover:from-blue-600 hover:to-blue-700 transition shadow-lg shadow-blue-200">
                    {{ $editingTemplateId ? 'Simpan Perubahan' : 'Buat Template' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Preview Modal --}}
    @if($showPreviewModal)
    <div class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4" wire:click.self="closePreviewModal">
        <div class="bg-white rounded-2xl w-full max-w-5xl shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-semibold text-gray-800">Preview Sertifikat</h3>
                <button wire:click="closePreviewModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-4 bg-gray-100 max-h-[75vh] overflow-auto">
                <div class="bg-white shadow-xl rounded-lg overflow-hidden mx-auto" style="max-width: 900px; aspect-ratio: 1.414/1;">
                    <iframe srcdoc="{{ $previewHtml }}" class="w-full h-full border-0" style="min-height: 600px;"></iframe>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
