<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Paper untuk Review</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar paper yang ditugaskan kepada Anda</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="flex gap-3 mb-4">
        <select wire:model.live="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
        </select>
    </div>

    <div class="space-y-4">
        @forelse($reviews as $review)
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 mb-1">{{ $review->paper->title }}</h3>
                    <p class="text-sm text-gray-500">Penulis: {{ $review->paper->user->name }}</p>
                    <p class="text-xs text-gray-400 mt-1">Topik: {{ $review->paper->topic }} &bull; Ditugaskan: {{ $review->created_at->format('d M Y') }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($review->status==='completed') bg-green-100 text-green-800
                        @elseif($review->status==='in_progress') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                    </span>
                    @if($review->recommendation)
                        <p class="text-xs text-gray-500 mt-1">{{ \App\Models\Review::RECOMMENDATION_LABELS[$review->recommendation] ?? '' }} ({{ $review->score }}/100)</p>
                    @endif
                </div>
            </div>

            <div class="flex gap-3 mt-4">
                @php $latestFile = $review->paper->files->whereIn('type', ['full_paper','revision'])->sortByDesc('created_at')->first(); @endphp
                @if($latestFile)
                    <a href="{{ asset('storage/'.$latestFile->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">📄 Download Paper</a>
                @endif
                @if($review->status !== 'completed')
                    <a href="{{ route('reviewer.review.form', $review) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">✏️ Beri Review</a>
                @else
                    <a href="{{ route('reviewer.review.form', $review) }}" class="text-gray-500 hover:text-gray-700 text-sm">📋 Lihat Review</a>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border p-12 text-center">
            <p class="text-gray-400">Belum ada paper yang ditugaskan untuk Anda review.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $reviews->links() }}</div>
</div>
