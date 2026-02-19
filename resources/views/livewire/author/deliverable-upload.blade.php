<div class="max-w-4xl mx-auto py-8 px-4">
    <a href="{{ route('author.paper.detail', $paper) }}" class="text-blue-600 hover:text-blue-800 text-sm">&larr; Kembali ke Detail Paper</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2 mb-1">Luaran Prosiding</h1>
    <p class="text-gray-500 text-sm mb-6">{{ $paper->title }}</p>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Author uploads --}}
        <div class="space-y-4">
            <h3 class="font-semibold text-gray-800">Upload Luaran Anda</h3>

            @foreach(['poster' => 'Poster', 'ppt' => 'PPT Presentasi', 'final_paper' => 'Full Paper Final'] as $type => $label)
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-medium text-gray-800">{{ $label }}</h4>
                    @if(isset($authorDeliverables[$type]))
                        <span class="text-green-600 text-xs font-medium">✓ Diunggah</span>
                    @else
                        <span class="text-gray-400 text-xs">Belum diunggah</span>
                    @endif
                </div>

                @if(isset($authorDeliverables[$type]))
                    <div class="bg-gray-50 rounded-lg p-3 mb-3 text-sm">
                        <p class="text-gray-700">{{ $authorDeliverables[$type]->original_name }}</p>
                        <p class="text-xs text-gray-400">{{ $authorDeliverables[$type]->created_at->format('d M Y H:i') }}</p>
                        <a href="{{ asset('storage/' . $authorDeliverables[$type]->file_path) }}" target="_blank" class="text-blue-600 text-xs">Download</a>
                    </div>
                @endif

                @php $fileProperty = match($type) { 'poster' => 'posterFile', 'ppt' => 'pptFile', 'final_paper' => 'finalPaperFile' }; @endphp
                <div class="flex gap-2">
                    <input type="file" wire:model="{{ $fileProperty }}" class="flex-1 text-sm" accept=".pdf,.ppt,.pptx,.jpg,.jpeg,.png">
                    <button wire:click="uploadDeliverable('{{ $type }}')" class="px-4 py-1.5 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700" wire:loading.attr="disabled">
                        Upload
                    </button>
                </div>
                @error($fileProperty) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <div wire:loading wire:target="{{ $fileProperty }}" class="text-blue-500 text-xs mt-1">Mengunggah...</div>
            </div>
            @endforeach
        </div>

        {{-- Admin sends --}}
        <div class="space-y-4">
            <h3 class="font-semibold text-gray-800">Luaran dari Panitia</h3>

            @if($adminDeliverables->count())
                @foreach($adminDeliverables as $deliverable)
                <div class="bg-white rounded-xl shadow-sm border p-5">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-medium text-gray-800">{{ \App\Models\Deliverable::TYPE_LABELS[$deliverable->type] ?? $deliverable->type }}</h4>
                        <span class="text-green-600 text-xs font-medium">{{ $deliverable->sent_at?->format('d M Y') }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">{{ $deliverable->original_name }}</p>
                    <a href="{{ asset('storage/' . $deliverable->file_path) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download
                    </a>
                </div>
                @endforeach
            @else
                <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-8 text-center">
                    <p class="text-gray-400 text-sm">Belum ada luaran dari panitia.</p>
                    <p class="text-gray-300 text-xs mt-1">Buku prosiding dan sertifikat akan dikirim setelah semua luaran Anda lengkap.</p>
                </div>
            @endif
        </div>
    </div>
</div>
