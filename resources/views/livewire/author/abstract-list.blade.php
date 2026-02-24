<div>
    <div class="max-w-5xl mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Abstrak Saya</h2>
                <p class="text-sm text-gray-500 mt-1">Daftar abstrak yang pernah Anda kirimkan</p>
            </div>
            <a href="{{ route('author.abstract.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl font-medium hover:bg-blue-700 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Submit Abstrak
            </a>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        @if($abstracts->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p class="text-gray-500">Belum ada abstrak yang dikirimkan.</p>
            <a href="{{ route('author.abstract.create') }}" class="inline-flex items-center gap-2 mt-4 bg-blue-600 text-white px-5 py-2.5 rounded-xl font-medium text-sm hover:bg-blue-700">Submit Abstrak Pertama</a>
        </div>
        @else
        <div class="space-y-4">
            @foreach($abstracts as $abs)
            @php
                $colors = ['pending'=>'yellow','approved'=>'green','rejected'=>'red','revision_required'=>'orange'];
                $c = $colors[$abs->status] ?? 'gray';
            @endphp
            <div class="bg-white rounded-2xl border border-gray-200 p-5 hover:shadow-sm transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-{{ $c }}-100 text-{{ $c }}-700">{{ $abs->status_label }}</span>
                            <span class="text-xs text-gray-400">{{ $abs->conference?->name }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 text-sm leading-snug">{{ $abs->title }}</h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $abs->abstract }}</p>
                        @if($abs->reviewer_notes)
                        <div class="mt-2 p-2.5 bg-yellow-50 border border-yellow-200 rounded-lg text-xs text-yellow-800">
                            <strong>Catatan Reviewer:</strong> {{ $abs->reviewer_notes }}
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        @if(in_array($abs->status, ['pending', 'revision_required']))
                        <a href="{{ route('author.abstract.edit', $abs->id) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium px-3 py-1.5 rounded-lg border border-blue-200 hover:bg-blue-50 transition">Edit</a>
                        <button wire:click="delete({{ $abs->id }})" wire:confirm="Yakin hapus abstrak ini?" class="text-red-500 hover:text-red-700 text-xs font-medium px-3 py-1.5 rounded-lg border border-red-200 hover:bg-red-50 transition">Hapus</button>
                        @endif
                        @if($abs->status === 'approved')
                        <a href="{{ route('submit.paper') }}" class="text-green-600 hover:text-green-800 text-xs font-medium px-3 py-1.5 rounded-lg border border-green-200 hover:bg-green-50 transition">→ Full Paper</a>
                        @endif
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">Dikirim {{ $abs->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
