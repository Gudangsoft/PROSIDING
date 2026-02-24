<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Abstrak</h2>
            <div class="text-sm text-gray-500">Total: {{ $abstracts->total() }} abstrak</div>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        {{-- Filters --}}
        <div class="flex flex-wrap gap-3 mb-6">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul / nama..." class="px-3 py-2 border border-gray-300 rounded-xl text-sm w-64">
            <select wire:model.live="statusFilter" class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu Review</option>
                <option value="approved">Disetujui</option>
                <option value="rejected">Ditolak</option>
                <option value="revision_required">Perlu Revisi</option>
            </select>
            <select wire:model.live="confFilter" class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
                <option value="">Semua Konferensi</option>
                @foreach($conferences as $conf)
                <option value="{{ $conf->id }}">{{ $conf->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Modal Review --}}
        @if($reviewingId)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-data>
            <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Review Abstrak</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model="reviewStatus" class="w-full px-3 py-2 border rounded-xl text-sm">
                            <option value="pending">Menunggu</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                            <option value="revision_required">Perlu Revisi</option>
                        </select>
                        @error('reviewStatus')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan untuk Author</label>
                        <textarea wire:model="reviewNotes" rows="4" class="w-full px-3 py-2 border rounded-xl text-sm resize-none" placeholder="Tuliskan catatan atau saran revisi..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-5">
                    <button wire:click="cancelReview" class="px-4 py-2 border border-gray-300 rounded-xl text-sm text-gray-700 hover:bg-gray-50">Batal</button>
                    <button wire:click="submitReview" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700">Simpan Review</button>
                </div>
            </div>
        </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Judul</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Author</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden lg:table-cell">Konferensi</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($abstracts as $abs)
                    @php $c = ['pending'=>'yellow','approved'=>'green','rejected'=>'red','revision_required'=>'orange'][$abs->status] ?? 'gray'; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800 line-clamp-1">{{ $abs->title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $abs->abstract }}</p>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <p class="text-gray-700">{{ $abs->user?->name }}</p>
                            <p class="text-xs text-gray-400">{{ $abs->user?->email }}</p>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell text-gray-600 text-xs">{{ $abs->conference?->name }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-{{ $c }}-100 text-{{ $c }}-700">{{ $abs->status_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="openReview({{ $abs->id }})" class="text-blue-600 hover:text-blue-800 text-xs font-medium px-3 py-1.5 border border-blue-200 rounded-lg hover:bg-blue-50 transition">Review</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-12 text-gray-400">Tidak ada abstrak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($abstracts->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">{{ $abstracts->links() }}</div>
            @endif
        </div>
    </div>
</div>
