<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Paper Saya</h1>
            <p class="text-gray-500 text-sm mt-1">Daftar paper yang telah Anda submit</p>
        </div>
        <a href="{{ route('author.submit') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium text-sm">
            + Submit Paper Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <div class="flex gap-4 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul paper..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
        <select wire:model.live="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">Semua Status</option>
            @foreach(\App\Models\Paper::STATUS_LABELS as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    {{-- Papers Table --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">#</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Judul</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Topik</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Tanggal Submit</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($papers as $i => $paper)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $papers->firstItem() + $i }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $paper->title }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $paper->topic }}</td>
                    <td class="px-4 py-3">
                        @php $color = $paper->status_color; @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($color==='green') bg-green-100 text-green-800
                            @elseif($color==='red') bg-red-100 text-red-800
                            @elseif($color==='yellow' || $color==='amber') bg-yellow-100 text-yellow-800
                            @elseif($color==='blue' || $color==='cyan') bg-blue-100 text-blue-800
                            @elseif($color==='orange') bg-orange-100 text-orange-800
                            @elseif($color==='indigo' || $color==='purple') bg-indigo-100 text-indigo-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $paper->status_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $paper->submitted_at?->format('d M Y') ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('author.paper.detail', $paper) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Detail</a>
                        @if(in_array($paper->status, ['payment_pending', 'payment_uploaded']))
                            <a href="{{ route('author.paper.payment', $paper) }}" class="text-green-600 hover:text-green-800 font-medium text-sm ml-2">Bayar</a>
                        @endif
                        @if(in_array($paper->status, ['payment_verified', 'deliverables_pending', 'completed']))
                            <a href="{{ route('author.paper.deliverables', $paper) }}" class="text-purple-600 hover:text-purple-800 font-medium text-sm ml-2">Luaran</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                        <p class="text-lg mb-2">Belum ada paper</p>
                        <a href="{{ route('author.submit') }}" class="text-blue-600 hover:text-blue-800 text-sm">Submit paper pertama Anda</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $papers->links() }}</div>
</div>
