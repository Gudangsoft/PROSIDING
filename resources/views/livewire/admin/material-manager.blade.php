<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Materi & Benefit</h1>
            <p class="text-gray-500 text-sm mt-1">Upload materi, PPT, dan sertifikat untuk peserta konferensi</p>
        </div>
        <button wire:click="openForm()" type="button"
            class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700 transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Materi
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    {{-- Conference Selector --}}
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Konferensi</label>
        <select wire:model.live="conferenceId" class="w-full max-w-md px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500">
            @foreach($conferences as $conf)
                <option value="{{ $conf->id }}">{{ $conf->name }} ({{ $conf->is_active ? 'Aktif' : 'Nonaktif' }})</option>
            @endforeach
        </select>
    </div>

    {{-- Materials Table --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Judul</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Tipe</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">File</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Ukuran</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($materials as $mat)
                <tr class="hover:bg-gray-50 {{ !$mat->is_active ? 'opacity-50' : '' }}">
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $mat->getTypeIcon() }} {{ $mat->title }}</p>
                        @if($mat->description)
                            <p class="text-xs text-gray-400 mt-0.5 max-w-xs truncate">{{ $mat->description }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            @if($mat->type==='sertifikat') bg-yellow-100 text-yellow-800
                            @elseif($mat->type==='ppt') bg-blue-100 text-blue-800
                            @elseif($mat->type==='materi') bg-teal-100 text-teal-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $mat->getTypeLabel() }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ asset('storage/'.$mat->file_path) }}" target="_blank"
                            class="inline-flex items-center gap-1 text-blue-600 text-xs hover:underline truncate max-w-[160px]">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            {{ $mat->file_name }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $mat->file_size ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <button wire:click="toggleActive({{ $mat->id }})" type="button"
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium cursor-pointer
                            {{ $mat->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ $mat->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <button wire:click="openForm({{ $mat->id }})" type="button"
                                class="px-2 py-1 text-xs font-medium text-blue-600 border border-blue-200 rounded hover:bg-blue-50 cursor-pointer">Edit</button>
                            <button wire:click="delete({{ $mat->id }})" wire:confirm="Hapus materi '{{ $mat->title }}'?" type="button"
                                class="px-2 py-1 text-xs font-medium text-red-600 border border-red-200 rounded hover:bg-red-50 cursor-pointer">Hapus</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">Belum ada materi. Klik "Tambah Materi" untuk mulai upload.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form Modal --}}
    @if($showForm)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-bold text-gray-800">{{ $editingId ? 'Edit Materi' : 'Tambah Materi Baru' }}</h3>
                <button wire:click="resetForm" type="button" class="text-gray-400 hover:text-gray-600 text-xl cursor-pointer">&times;</button>
            </div>
            <form wire:submit="save" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                    <input wire:model="title" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500" placeholder="Contoh: Materi Sesi 1 — Prof. Dr. ..." />
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
                    <select wire:model="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500">
                        <option value="materi">📄 Materi</option>
                        <option value="ppt">📊 Presentasi (PPT)</option>
                        <option value="sertifikat">🏆 Sertifikat</option>
                        <option value="lainnya">📎 Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea wire:model="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500" placeholder="Keterangan singkat (opsional)"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        File {{ $editingId ? '(kosongkan jika tidak diganti)' : '' }} <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="file" type="file" accept=".pdf,.ppt,.pptx,.doc,.docx,.xls,.xlsx,.zip,.jpg,.jpeg,.png"
                        class="w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" />
                    <p class="text-xs text-gray-400 mt-1">Format: PDF, PPT, PPTX, DOC, DOCX, ZIP, JPG, PNG. Maks 20MB.</p>
                    @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                        <input wire:model="sortOrder" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500" />
                    </div>
                    <div class="flex items-center gap-2 pt-6">
                        <input wire:model="isActive" type="checkbox" id="isActive" class="rounded text-teal-600 focus:ring-teal-500" />
                        <label for="isActive" class="text-sm text-gray-700">Aktif (tampil ke peserta)</label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t">
                    <button type="submit" wire:loading.attr="disabled"
                        class="px-5 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700 transition cursor-pointer disabled:opacity-60">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? 'Simpan Perubahan' : 'Upload & Simpan' }}</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                    <button type="button" wire:click="resetForm" class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm cursor-pointer">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
