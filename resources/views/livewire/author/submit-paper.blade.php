<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Submit Paper Baru</h1>
        <p class="text-gray-500 text-sm mt-1">Unggah paper Anda untuk prosiding LPKD-APJI</p>
    </div>

    <form wire:submit="submit" class="bg-white rounded-xl shadow-sm border p-6 space-y-6">
        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Paper <span class="text-red-500">*</span></label>
            <input type="text" wire:model="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Masukkan judul paper...">
            @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Abstract --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Abstrak <span class="text-red-500">*</span></label>
            <textarea wire:model="abstract" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Tulis abstrak paper Anda..."></textarea>
            @error('abstract') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Keywords --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kata Kunci <span class="text-red-500">*</span></label>
            <input type="text" wire:model="keywords" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Pisahkan dengan koma, contoh: pendidikan, kejuruan, kurikulum">
            @error('keywords') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Topic --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Topik <span class="text-red-500">*</span></label>
            <select wire:model="selectedTopic" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                <option value="">-- Pilih Topik --</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->name }}">{{ $topic->name }}</option>
                @endforeach
            </select>
            @error('selectedTopic') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Contributors / Co-Authors --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kontributor / Penulis Lain</label>
            <p class="text-xs text-gray-400 mb-3">Anda otomatis terdaftar sebagai penulis utama. Tambahkan co-author di bawah jika ada.</p>

            {{-- Penulis Utama (non-editable) --}}
            <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 mb-3">
                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }} &bull; {{ Auth::user()->institution ?? '-' }}</p>
                </div>
                <span class="text-xs font-medium text-blue-700 bg-blue-100 px-2 py-0.5 rounded">Penulis Utama</span>
            </div>

            {{-- Daftar Kontributor yang Ditambahkan --}}
            @foreach($contributors as $index => $contrib)
            <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 mb-2">
                <div class="w-8 h-8 bg-gray-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $index + 2 }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">{{ $contrib['name'] }}</p>
                    <p class="text-xs text-gray-500">{{ $contrib['email'] }} &bull; {{ $contrib['institution'] }}</p>
                </div>
                <button type="button" wire:click="removeContributor({{ $index }})" class="text-red-400 hover:text-red-600 transition p-1" title="Hapus kontributor">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            @endforeach

            {{-- Form Tambah Kontributor --}}
            <div class="border border-dashed border-gray-300 rounded-lg p-4 mt-3">
                <p class="text-sm font-medium text-gray-600 mb-3">Tambah Kontributor</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <input type="text" wire:model="contribName" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Nama lengkap">
                        @error('contribName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="email" wire:model="contribEmail" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Email">
                        @error('contribEmail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="contribInstitution" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Institusi / Afiliasi">
                        @error('contribInstitution') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <button type="button" wire:click="addContributor" class="mt-3 px-4 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"/></svg>
                    Tambah Kontributor
                </button>
            </div>
        </div>

        {{-- File Upload --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- File Abstrak (Wajib) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Abstrak (PDF/DOC/DOCX) <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition">
                    <input type="file" wire:model="abstractFile" class="w-full text-sm" accept=".pdf,.doc,.docx">
                    <p class="text-xs text-gray-400 mt-1">Maks. 10MB</p>
                </div>
                @error('abstractFile') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <div wire:loading wire:target="abstractFile" class="text-sm text-blue-500 mt-1">Mengunggah...</div>
            </div>

            {{-- File Full Paper (Opsional) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Full Paper (PDF/DOC/DOCX, opsional)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition">
                    <input type="file" wire:model="paperFile" class="w-full text-sm" accept=".pdf,.doc,.docx">
                    <p class="text-xs text-gray-400 mt-1">Maks. 10MB</p>
                </div>
                @error('paperFile') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <div wire:loading wire:target="paperFile" class="text-sm text-blue-500 mt-1">Mengunggah...</div>
            </div>

            {{-- File Turnitin (Opsional) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Turnitin (PDF, opsional)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition">
                    <input type="file" wire:model="turnitinFile" class="w-full text-sm" accept=".pdf">
                    <p class="text-xs text-gray-400 mt-1">Maks. 10MB</p>
                </div>
                @error('turnitinFile') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <div wire:loading wire:target="turnitinFile" class="text-sm text-blue-500 mt-1">Mengunggah...</div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('author.papers') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submit">Submit Paper</span>
                <span wire:loading wire:target="submit">Mengirim...</span>
            </button>
        </div>
    </form>
</div>
