<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('author.papers') }}" class="flex items-center gap-1.5 text-sm text-blue-600 hover:underline mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Paper
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Permintaan Revisi</h1>
        <p class="text-sm text-gray-500 mt-1">Lihat catatan revisi dari editor dan upload paper yang sudah direvisi.</p>
    </div>

    @if(session('success'))
    <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">
        <svg class="w-5 h-5 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Paper info --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Paper</p>
        <h2 class="text-base font-bold text-gray-800 mb-2">{{ $paper->title }}</h2>
        <span class="text-xs bg-{{ $paper->status_color }}-100 text-{{ $paper->status_color }}-700 font-semibold px-2.5 py-1 rounded-full">{{ $paper->status_label }}</span>
    </div>

    @if($revisionRequests->isEmpty())
    <div class="bg-white border border-dashed border-gray-200 rounded-2xl p-12 text-center">
        <p class="text-4xl mb-3">📋</p>
        <p class="text-gray-500 font-semibold">Belum ada permintaan revisi</p>
        <p class="text-gray-400 text-sm mt-1">Editor belum mengirim catatan revisi untuk paper ini.</p>
    </div>
    @else
    <div class="space-y-5">
        @foreach($revisionRequests as $req)
        @php $isOpen = $req->resolved_at === null; @endphp
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 {{ $isOpen ? 'bg-orange-50' : 'bg-gray-50' }}">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ $isOpen ? 'bg-orange-200 text-orange-700' : 'bg-green-200 text-green-700' }} font-black text-sm">
                        {{ $isOpen ? '!' : '✓' }}
                    </div>
                    <div>
                        <p class="text-sm font-bold {{ $isOpen ? 'text-orange-800' : 'text-green-800' }}">
                            {{ $isOpen ? 'Revisi Diperlukan' : 'Revisi Selesai' }}
                        </p>
                        <p class="text-xs text-gray-400">Dikirim oleh {{ $req->admin->name }} • {{ $req->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if($req->deadline)
                <div class="text-right">
                    <p class="text-xs font-bold {{ $req->deadline->isPast() && $isOpen ? 'text-red-600' : 'text-gray-600' }}">
                        {{ $req->deadline->format('d M Y') }}
                        @if($req->deadline->isPast() && $isOpen) <span class="text-red-500">LEWAT</span> @endif
                    </p>
                    <p class="text-[10px] text-gray-400">Deadline</p>
                </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="p-5">
                <div class="mb-4">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Catatan Editor</p>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $req->note }}</div>
                </div>

                @if($req->revised_file_path)
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-green-800">Revisi sudah diupload</p>
                        @if($req->author_response)<p class="text-xs text-green-600 mt-0.5">Komentar: {{ $req->author_response }}</p>@endif
                    </div>
                    <a href="{{ Storage::url($req->revised_file_path) }}" target="_blank"
                        class="text-xs font-semibold text-blue-600 hover:underline">Lihat File</a>
                </div>
                @endif

                {{-- Upload form (only for open requests without file) --}}
                @if($isOpen && !$req->revised_file_path)
                <div class="border-t border-gray-100 pt-4 mt-4">
                    <p class="text-sm font-bold text-gray-700 mb-3">Upload Revisi</p>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1.5">Komentar / Catatan Perubahan <span class="font-normal text-gray-400">(opsional)</span></label>
                            <textarea wire:model="response" rows="2" placeholder="Jelaskan perubahan yang Anda buat..."
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-300 resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1.5">File Revisi <span class="text-red-500">*</span></label>
                            <label class="flex items-center gap-3 p-4 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                                <svg class="w-6 h-6 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6H16a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <div class="flex-1 min-w-0">
                                    @if($revisedFile)
                                    <p class="text-sm font-semibold text-green-700">{{ $revisedFile->getClientOriginalName() }}</p>
                                    <p class="text-xs text-gray-400">{{ number_format($revisedFile->getSize() / 1024, 0) }} KB</p>
                                    @else
                                    <p class="text-sm text-gray-500">Pilih file PDF/DOC/DOCX (maks 20 MB)</p>
                                    @endif
                                </div>
                                <input wire:model="revisedFile" type="file" accept=".pdf,.doc,.docx,.zip" class="hidden">
                            </label>
                            @error('revisedFile')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <button wire:click="submitRevision({{ $req->id }})"
                            class="w-full py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-sm font-bold transition shadow-md shadow-orange-100 flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="submitRevision({{ $req->id }})">Kirim Revisi</span>
                            <span wire:loading wire:target="submitRevision({{ $req->id }})" class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                Mengirim...
                            </span>
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>