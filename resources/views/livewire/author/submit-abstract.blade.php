<div>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('author.abstracts') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Daftar Abstrak</a>
            <h2 class="text-2xl font-bold text-gray-800 mt-2">{{ $isEdit ? 'Edit Abstrak' : 'Submit Abstrak' }}</h2>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        <form wire:submit="save" class="space-y-5">
            {{-- Konferensi --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konferensi <span class="text-red-500">*</span></label>
                <select wire:model.live="conference_id" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Konferensi --</option>
                    @foreach($conferences as $conf)
                    <option value="{{ $conf->id }}">{{ $conf->name }}</option>
                    @endforeach
                </select>
                @error('conference_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Judul & Abstrak --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Makalah <span class="text-red-500">*</span></label>
                    <input wire:model="title" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan judul makalah...">
                    @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Abstrak <span class="text-red-500">*</span> <span class="text-gray-400 font-normal">(min. 100 karakter)</span></label>
                    <textarea wire:model="abstract" rows="8" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="Tulis abstrak makalah Anda..."></textarea>
                    <div class="mt-1 text-xs text-gray-400 text-right">{{ strlen($abstract) }} karakter</div>
                    @error('abstract')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Kunci</label>
                        <input wire:model="keywords" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm" placeholder="kata1; kata2; kata3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bidang Topik</label>
                        @if(!empty($topics))
                        <select wire:model="topic" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                            <option value="">-- Pilih Topik --</option>
                            @foreach($topics as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach
                        </select>
                        @else
                        <input wire:model="topic" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm" placeholder="Topik penelitian">
                        @endif
                    </div>
                </div>
            </div>

            {{-- Penulis --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">Daftar Penulis</h3>
                    <button type="button" wire:click="addAuthor" class="text-xs text-blue-600 hover:text-blue-800 font-medium px-3 py-1.5 border border-blue-200 rounded-lg hover:bg-blue-50 transition">+ Tambah Penulis</button>
                </div>
                <div class="space-y-3">
                    @foreach($authors as $i => $author)
                    <div class="flex gap-2 items-start p-3 border border-gray-100 rounded-xl bg-gray-50">
                        <div class="flex-1 grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <input wire:model="authors.{{ $i }}.name" type="text" placeholder="Nama Penulis *" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                            <input wire:model="authors.{{ $i }}.email" type="email" placeholder="Email" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                            <input wire:model="authors.{{ $i }}.institution" type="text" placeholder="Institusi" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <label class="flex items-center gap-1 text-xs text-gray-500 whitespace-nowrap">
                                <input wire:model="authors.{{ $i }}.is_correspondent" type="checkbox" class="rounded"> Korespondensi
                            </label>
                            @if(count($authors) > 1)
                            <button type="button" wire:click="removeAuthor({{ $i }})" class="text-red-400 hover:text-red-600 text-lg leading-none">&times;</button>
                            @endif
                        </div>
                    </div>
                    @error("authors.$i.name")<p class="text-xs text-red-500">{{ $message }}</p>@enderror
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('author.abstracts') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                    <span wire:loading.remove>{{ $isEdit ? 'Simpan Perubahan' : 'Kirim Abstrak' }}</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
