<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Berita</h2>
            <p class="text-sm text-gray-500">Kelola berita dan informasi terkait prosiding</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tulis Berita
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="flex flex-wrap gap-3 mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari berita..." class="w-full sm:w-64 px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        <select wire:model.live="categoryFilter" class="px-3 py-2 border rounded-lg text-sm">
            <option value="">Semua Kategori</option>
            @foreach(\App\Models\News::CATEGORY_LABELS as $val => $lbl)<option value="{{ $val }}">{{ $lbl }}</option>@endforeach
        </select>
        <select wire:model.live="statusFilter" class="px-3 py-2 border rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="published">Published</option>
            <option value="archived">Archived</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Judul</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Kategori</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Views</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Tanggal</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($newsItems as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <p class="font-medium text-gray-800">{{ Str::limit($item->title, 50) }}</p>
                                @if($item->is_pinned)<span class="text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded">Pinned</span>@endif
                                @if($item->is_featured)<span class="text-xs bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded">Featured</span>@endif
                            </div>
                            @if($item->author)<p class="text-xs text-gray-400">oleh {{ $item->author->name }}</p>@endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                @if($item->category_color==='blue') bg-blue-100 text-blue-800
                                @elseif($item->category_color==='green') bg-green-100 text-green-800
                                @elseif($item->category_color==='yellow') bg-yellow-100 text-yellow-800
                                @elseif($item->category_color==='purple') bg-purple-100 text-purple-800
                                @elseif($item->category_color==='indigo') bg-indigo-100 text-indigo-800
                                @else bg-gray-100 text-gray-800 @endif">{{ $item->category_label }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                @if($item->status==='published') bg-green-100 text-green-800
                                @elseif($item->status==='draft') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">{{ ucfirst($item->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500">{{ $item->views_count }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="togglePinned({{ $item->id }})" title="Pin/Unpin" class="text-gray-400 hover:text-yellow-600">
                                    <svg class="w-4 h-4" fill="{{ $item->is_pinned ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                </button>
                                <button wire:click="toggleFeatured({{ $item->id }})" title="Feature/Unfeature" class="text-gray-400 hover:text-purple-600">
                                    <svg class="w-4 h-4" fill="{{ $item->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </button>
                                <a href="{{ route('admin.news.edit', $item) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                                <button wire:click="delete({{ $item->id }})" wire:confirm="Yakin hapus berita ini?" class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada berita.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 border-t">{{ $newsItems->links() }}</div>
    </div>
</div>
