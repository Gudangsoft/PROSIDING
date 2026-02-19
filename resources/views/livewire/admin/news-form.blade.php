<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.news') }}" class="text-sm text-blue-600 hover:text-blue-800">&larr; Kembali ke Daftar Berita</a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">{{ $isEdit ? 'Edit Berita' : 'Tulis Berita Baru' }}</h2>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul *</label>
                <input wire:model="title" type="text" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500" required>
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan</label>
                <textarea wire:model="excerpt" rows="2" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Ringkasan singkat untuk preview..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konten *</label>
                <textarea wire:model="content" rows="12" class="w-full px-3 py-2 border rounded-lg text-sm" required></textarea>
                @error('content')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select wire:model="category" class="w-full px-3 py-2 border rounded-lg text-sm">
                        @foreach(\App\Models\News::CATEGORY_LABELS as $val => $lbl)<option value="{{ $val }}">{{ $lbl }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kegiatan Terkait</label>
                    <select wire:model="conference_id" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <option value="">-- Tidak terkait kegiatan --</option>
                        @foreach($conferences as $conf)<option value="{{ $conf->id }}">{{ $conf->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                    <input wire:model="cover_image" type="file" accept="image/*" class="w-full text-sm">
                    @error('cover_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model="status" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <option value="draft">Draft</option>
                        <option value="published">Publikasikan</option>
                        <option value="archived">Arsipkan</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap gap-6">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input wire:model="is_featured" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-700">Featured</span>
                </label>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input wire:model="is_pinned" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-700">Pinned (di atas)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.news') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                <span wire:loading.remove wire:target="save">{{ $isEdit ? 'Simpan Perubahan' : 'Publikasikan' }}</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
