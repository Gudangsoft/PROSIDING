<div class="max-w-4xl mx-auto py-8 px-4">
    <a href="{{ route('reviewer.reviews') }}" class="text-blue-600 hover:text-blue-800 text-sm">&larr; Kembali ke Daftar Review</a>

    <div class="mt-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Review Paper</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $review->paper->title }}</p>
        <p class="text-gray-400 text-xs mt-1">Penulis: {{ $review->paper->user->name }}</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Paper Files --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-4">
                <h3 class="font-semibold text-gray-800 mb-3">File Paper</h3>
                @foreach($review->paper->files->sortByDesc('created_at') as $file)
                <div class="p-3 bg-gray-50 rounded-lg mb-2">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $file->original_name }}</p>
                    <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_',' ',$file->type)) }}</p>
                    <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank" class="text-blue-600 text-xs font-medium">Download</a>
                </div>
                @endforeach

                <div class="mt-4 pt-4 border-t">
                    <h4 class="font-medium text-gray-700 text-sm mb-2">Abstrak</h4>
                    <p class="text-xs text-gray-600 whitespace-pre-line max-h-60 overflow-y-auto">{{ $review->paper->abstract }}</p>
                </div>
            </div>
        </div>

        {{-- Review Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Form Review</h3>

                @if($review->status === 'completed')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4 text-sm text-green-700">
                        Review telah selesai pada {{ $review->reviewed_at?->format('d M Y H:i') }}.
                    </div>
                @endif

                <div class="space-y-5">
                    {{-- Comments for Author --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Komentar untuk Penulis <span class="text-red-500">*</span></label>
                        <textarea wire:model="comments" rows="6" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Berikan komentar dan saran untuk penulis..." {{ $review->status === 'completed' ? 'disabled' : '' }}></textarea>
                        @error('comments') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Comments for Editor --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan untuk Editor (rahasia)</label>
                        <textarea wire:model="commentsForEditor" rows="3" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Catatan khusus untuk editor (tidak dilihat penulis)..." {{ $review->status === 'completed' ? 'disabled' : '' }}></textarea>
                    </div>

                    {{-- Recommendation --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rekomendasi <span class="text-red-500">*</span></label>
                        <select wire:model="recommendation" class="w-full px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none" {{ $review->status === 'completed' ? 'disabled' : '' }}>
                            <option value="">-- Pilih Rekomendasi --</option>
                            <option value="accept">Accept</option>
                            <option value="minor_revision">Minor Revision</option>
                            <option value="major_revision">Major Revision</option>
                            <option value="reject">Reject</option>
                        </select>
                        @error('recommendation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Upload Reviewed File --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload File Hasil Review (.doc/.docx)</label>
                        <p class="text-xs text-gray-400 mb-2">Upload file paper yang sudah diberi catatan/koreksi oleh reviewer.</p>

                        @if($review->review_file_path && !$reviewFile)
                        <div class="flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-lg mb-2">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><path d="M14 2v6h6" fill="none" stroke="currentColor" stroke-width="1"/></svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $review->review_file_name }}</p>
                                <p class="text-xs text-green-600">File sudah diupload</p>
                            </div>
                            <a href="{{ asset('storage/' . $review->review_file_path) }}" target="_blank" class="text-blue-600 text-xs font-medium hover:underline">Download</a>
                        </div>
                        @endif

                        @if($review->status !== 'completed')
                        <div class="relative">
                            <input type="file" wire:model="reviewFile" accept=".doc,.docx" class="w-full text-sm border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <div wire:loading wire:target="reviewFile" class="mt-1 text-xs text-blue-600">Mengupload file...</div>
                        </div>
                        @if($reviewFile)
                        <div class="flex items-center gap-2 mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/></svg>
                            <span class="text-sm text-gray-700 flex-1 truncate">{{ $reviewFile->getClientOriginalName() }}</span>
                            <button wire:click="removeReviewFile" type="button" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                        </div>
                        @endif
                        @error('reviewFile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    {{-- Actions --}}
                    @if($review->status !== 'completed')
                    <div class="flex gap-3 pt-4 border-t">
                        <button wire:click="saveDraft" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-sm">Simpan Draft</button>
                        <button wire:click="saveReview" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm">Submit Review</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
