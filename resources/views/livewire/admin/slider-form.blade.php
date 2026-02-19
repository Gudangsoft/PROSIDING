<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.sliders') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800">{{ $editMode ? 'Edit Slider' : 'Tambah Slider' }}</h1>
    </div>

    <form wire:submit.prevent="save">
        {{-- Image Upload --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Gambar Slider</h2>

            <div class="space-y-4">
                {{-- Preview --}}
                <div class="relative h-64 bg-gray-100 rounded-lg overflow-hidden border-2 border-dashed border-gray-300">
                    @if($image)
                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif($existingImage)
                        <img src="{{ asset('storage/' . $existingImage) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm">Ukuran rekomendasi: 1920x600 px</p>
                        </div>
                    @endif
                </div>

                <div>
                    <input type="file" wire:model="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WebP. Maksimal 5MB</p>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Konten</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input type="text" wire:model="title" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Judul utama slider">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subjudul</label>
                    <input type="text" wire:model="subtitle" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Subjudul atau tagline">
                    @error('subtitle') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea wire:model="description" rows="3" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Deskripsi singkat..."></textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Tombol CTA</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol 1</label>
                    <input type="text" wire:model="button_text" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Daftar Sekarang">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Tombol 1</label>
                    <input type="text" wire:model="button_url" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol 2</label>
                    <input type="text" wire:model="button_text_2" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Lihat Detail">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Tombol 2</label>
                    <input type="text" wire:model="button_url_2" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://...">
                </div>
            </div>
        </div>

        {{-- Appearance --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Tampilan</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Posisi Teks</label>
                    <select wire:model="text_position" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach(\App\Models\Slider::POSITIONS as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warna Teks</label>
                    <select wire:model="text_color" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach(\App\Models\Slider::TEXT_COLORS as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Overlay</label>
                    <input type="text" wire:model="overlay_color" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="rgba(0,0,0,0.4)">
                    <p class="text-xs text-gray-400 mt-1">Format RGBA</p>
                </div>
            </div>
        </div>

        {{-- Schedule & Status --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Jadwal & Status</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                    <input type="number" wire:model="sort_order" min="0" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" wire:model="start_date" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan untuk langsung aktif</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berakhir</label>
                    <input type="date" wire:model="end_date" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan untuk tanpa batas</p>
                </div>
            </div>

            <div class="mt-4">
                <label class="flex items-center gap-3">
                    <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Aktifkan slider ini</span>
                </label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.sliders') }}" class="px-5 py-2.5 text-gray-600 hover:text-gray-800 text-sm font-medium">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ $editMode ? 'Perbarui Slider' : 'Simpan Slider' }}
            </button>
        </div>
    </form>
</div>
