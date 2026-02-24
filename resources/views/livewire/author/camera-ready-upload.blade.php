<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('author.papers') }}" class="flex items-center gap-1.5 text-sm text-blue-600 hover:underline mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Paper
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Upload Camera-Ready</h1>
        <p class="text-sm text-gray-500 mt-1">Upload final paper Anda yang telah selesai direvisi dan siap cetak.</p>
    </div>

    @if(session('success'))
    <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">
        <svg class="w-5 h-5 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Paper info card --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Paper</p>
        <h2 class="text-base font-bold text-gray-800 mb-1">{{ $paper->title }}</h2>
        <div class="flex items-center gap-3 flex-wrap">
            <span class="text-xs bg-{{ $paper->status_color }}-100 text-{{ $paper->status_color }}-700 font-semibold px-2.5 py-1 rounded-full">{{ $paper->status_label }}</span>
            @php $cr = $paper->camera_ready_status; @endphp
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $cr === 'approved' ? 'bg-green-100 text-green-700' : ($cr === 'rejected' ? 'bg-red-100 text-red-700' : ($cr === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500')) }}">
                Camera-ready: {{ match($cr) { 'approved'=>'✓ Disetujui', 'rejected'=>'✕ Ditolak', 'pending'=>'⏳ Menunggu Review', default=>'Belum diupload' } }}
            </span>
        </div>
        @if($paper->camera_ready_notes && $cr === 'rejected')
        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
            <p class="font-semibold mb-1">Catatan dari editor:</p>
            <p>{{ $paper->camera_ready_notes }}</p>
        </div>
        @endif
        @if($paper->camera_ready_path && $cr === 'approved')
        <div class="mt-3 flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-xl">
            <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="flex-1">
                <p class="text-sm font-semibold text-green-800">Camera-ready Anda sudah disetujui!</p>
                <p class="text-xs text-green-600">Proses selesai. Terima kasih sudah berpartisipasi.</p>
            </div>
        </div>
        @endif
    </div>

    @if($cr !== 'approved')
    {{-- Upload form --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-4">
            {{ $cr === 'rejected' ? 'Upload Ulang File Camera-Ready' : 'Upload File Camera-Ready' }}
        </h3>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2">File Camera-Ready <span class="text-red-500">*</span></label>
                <label class="flex flex-col items-center gap-3 p-8 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6H16a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <div class="text-center">
                        <p class="text-sm font-semibold text-gray-600">Klik untuk pilih file</p>
                        <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX, atau ZIP — Maks. 20 MB</p>
                    </div>
                    <input wire:model="file" type="file" accept=".pdf,.doc,.docx,.zip" class="hidden">
                </label>
                @if($file)
                <div class="mt-2 flex items-center gap-2 text-sm text-green-700 bg-green-50 px-3 py-2 rounded-lg border border-green-200">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                    File dipilih: {{ $file->getClientOriginalName() }}
                    <span class="text-gray-400 ml-auto">{{ number_format($file->getSize() / 1024, 0) }} KB</span>
                </div>
                @endif
                @error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="bg-blue-50 border border-blue-200 text-blue-700 text-xs px-4 py-3 rounded-xl">
                <p class="font-semibold mb-1">Panduan Camera-Ready:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <li>Gunakan template yang disediakan panitia</li>
                    <li>Pastikan semua komentar reviewer sudah diterapkan</li>
                    <li>Cek format halaman, margin, dan font</li>
                    <li>Nama file: gunakan format <code class="bg-blue-100 px-1 rounded">CameraReady_NamaPenulis.pdf</code></li>
                </ul>
            </div>

            <button wire:click="upload"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition shadow-md shadow-blue-100 flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="upload">
                    <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6H16a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    Upload Camera-Ready
                </span>
                <span wire:loading wire:target="upload" class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Mengupload...
                </span>
            </button>
        </div>
    </div>
    @endif
</div>