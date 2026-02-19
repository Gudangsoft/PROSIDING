<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengumuman</h2>
            <p class="text-sm text-gray-500">Kelola pengumuman resmi prosiding</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Buat Pengumuman
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="flex flex-wrap gap-3 mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari pengumuman..." class="w-full sm:w-64 px-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        <select wire:model.live="typeFilter" class="px-3 py-2 border rounded-lg text-sm">
            <option value="">Semua Tipe</option>
            @foreach(\App\Models\Announcement::TYPE_LABELS as $val => $lbl)<option value="{{ $val }}">{{ $lbl }}</option>@endforeach
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
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Tipe</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Prioritas</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Audience</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Tanggal</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($announcements as $ann)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <p class="font-medium text-gray-800">{{ Str::limit($ann->title, 50) }}</p>
                                @if($ann->is_pinned)<span class="text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded">Pinned</span>@endif
                            </div>
                            @if($ann->creator)<p class="text-xs text-gray-400">oleh {{ $ann->creator->name }}</p>@endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                @if($ann->type_color==='blue') bg-blue-100 text-blue-800
                                @elseif($ann->type_color==='yellow') bg-yellow-100 text-yellow-800
                                @elseif($ann->type_color==='green') bg-green-100 text-green-800
                                @elseif($ann->type_color==='red') bg-red-100 text-red-800
                                @elseif($ann->type_color==='orange') bg-orange-100 text-orange-800
                                @elseif($ann->type_color==='indigo') bg-indigo-100 text-indigo-800
                                @else bg-gray-100 text-gray-800 @endif">{{ $ann->type_label }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-medium
                                @if($ann->priority==='urgent') text-red-600
                                @elseif($ann->priority==='high') text-orange-600
                                @else text-gray-500 @endif">{{ $ann->priority_label }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-wrap gap-1 justify-center">
                                @if(is_array($ann->audience))
                                    @foreach($ann->audience as $aud)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                            {{ ucfirst($aud) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-500">{{ ucfirst($ann->audience) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                @if($ann->status==='published') bg-green-100 text-green-800
                                @elseif($ann->status==='draft') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">{{ ucfirst($ann->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $ann->published_at?->format('d M Y') ?? $ann->created_at->format('d M Y') }}
                            @if($ann->is_expired)<br><span class="text-red-500">Expired</span>@endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="togglePinned({{ $ann->id }})" title="Pin/Unpin" class="text-gray-400 hover:text-yellow-600">
                                    <svg class="w-4 h-4" fill="{{ $ann->is_pinned ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                </button>
                                <a href="{{ route('admin.announcements.edit', $ann) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                                <button wire:click="delete({{ $ann->id }})" wire:confirm="Yakin hapus pengumuman ini?" class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada pengumuman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 border-t">{{ $announcements->links() }}</div>
    </div>
</div>
