<div>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Program Book Generator</h2>
            <p class="text-sm text-gray-500">Generate buku prosiding / program conference dalam format PDF</p>
        </div>

        @if(session('error')) <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">⚠️ {{ session('error') }}</div> @endif

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-700 px-6 py-4">
                <h3 class="text-white font-bold text-lg">📚 Generate PDF</h3>
                <p class="text-purple-200 text-xs">Semua pengaturan tersimpan otomatis saat generate</p>
            </div>
            <div class="p-6 space-y-5">
                {{-- Conference --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Konferensi <span class="text-red-500">*</span></label>
                    <select wire:model.live="conferenceId" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                        <option value="0">— Pilih Konferensi —</option>
                        @foreach($conferences as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select>
                    @error('conferenceId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Title / Subtitle --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Judul Buku</label>
                        <input wire:model="bookTitle" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm" placeholder="Prosiding ICST 2024">
                        @error('bookTitle') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Subtitle (opsional)</label>
                        <input wire:model="bookSubtitle" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm" placeholder="International Conference on...">
                    </div>
                </div>

                {{-- Paper Status --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Sertakan Paper dengan Status</label>
                    <select wire:model.live="selectedStatus" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                        <option value="accepted">Accepted</option>
                        <option value="payment_verified">Payment Verified</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                {{-- Group By --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Kelompokkan Berdasarkan</label>
                    <select wire:model="groupBy" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                        <option value="topic">Topik</option>
                        <option value="none">Tidak Dikelompokkan</option>
                    </select>
                </div>

                {{-- Paper Count Preview --}}
                @if($conferenceId)
                <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex items-center gap-3 text-sm text-blue-800">
                    <span class="text-2xl">📝</span>
                    <div>
                        <span class="font-bold text-lg">{{ $paperCount }}</span> paper akan disertakan
                        <p class="text-xs text-blue-600">Status: <strong>{{ $selectedStatus }}</strong> di konferensi ini</p>
                    </div>
                </div>
                @endif

                {{-- Options --}}
                <div>
                    <p class="text-xs font-semibold text-gray-600 mb-2">Konten yang Disertakan</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach([
                            ['includeCover', '🎨 Halaman Cover'],
                            ['includeToc', '📋 Daftar Isi'],
                            ['includeAbstracts', '📄 Abstrak Paper'],
                            ['includeAuthors', '👤 Data Author'],
                            ['includeProgramSchedule', '📅 Jadwal Program'],
                        ] as [$prop, $label])
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 hover:bg-gray-100 {{ $$prop ? 'ring-2 ring-purple-300 bg-purple-50 border-purple-300' : '' }}">
                            <input wire:model.live="{{ $prop }}" type="checkbox" class="w-4 h-4 text-purple-600 rounded">
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Generate Button --}}
                <div class="pt-2">
                    <button wire:click="generate"
                        wire:loading.attr="disabled"
                        @if(!$conferenceId) disabled @endif
                        class="w-full px-6 py-3 bg-purple-600 text-white rounded-xl font-bold text-sm hover:bg-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="generate">⬇️ Generate & Unduh PDF</span>
                        <span wire:loading wire:target="generate" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                            Membuat PDF...
                        </span>
                    </button>
                    @if(!$conferenceId)
                    <p class="text-xs text-gray-400 text-center mt-2">Pilih konferensi terlebih dahulu</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tips --}}
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-4 text-sm text-amber-800">
            <p class="font-semibold mb-2">💡 Tips:</p>
            <ul class="space-y-1 text-xs list-disc list-inside text-amber-700">
                <li>PDF menggunakan font DejaVu — mendukung karakter Latin dan Unicode dasar</li>
                <li>Untuk logo konferensi, pastikan sudah diupload di pengaturan konferensi</li>
                <li>Paper diurutkan berdasarkan topik (jika opsi "Kelompokkan" dipilih)</li>
                <li>Proses generate mungkin memerlukan beberapa detik untuk banyak paper</li>
            </ul>
        </div>
    </div>
</div>
