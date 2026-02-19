<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Halaman</h1>
            <p class="text-sm text-gray-500 mt-1">Buat dan kelola halaman statis dinamis</p>
        </div>
        <a href="{{ route('admin.pages.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Halaman Baru
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul halaman..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <select wire:model.live="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 min-w-[140px]">
                <option value="">Semua Status</option>
                @foreach(\App\Models\Page::STATUS_LABELS as $val => $lbl)
                <option value="{{ $val }}">{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500 w-12">#</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Judul</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Slug / URL</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Layout</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Status</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Views</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Terakhir diubah</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500 w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($pages as $page)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-400">{{ $page->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if($page->cover_image)
                                <img src="{{ asset('storage/' . $page->cover_image) }}" class="w-8 h-8 rounded object-cover shrink-0">
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ $page->title }}</p>
                                    @if($page->is_featured)
                                    <span class="text-[10px] bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded-full font-bold">FEATURED</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <code class="text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded font-mono">/page/{{ $page->slug }}</code>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs text-gray-500">{{ \App\Models\Page::LAYOUT_OPTIONS[$page->layout] ?? $page->layout }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($page->status === 'published')
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 px-2 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Publik
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Draf
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-gray-500">{{ number_format($page->views_count) }}</td>
                        <td class="px-4 py-3 text-center text-xs text-gray-400">{{ $page->updated_at->diffForHumans() }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                @if($page->status === 'published')
                                <a href="{{ route('page.show', $page->slug) }}" target="_blank" title="Lihat"
                                   class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @endif
                                <a href="{{ route('admin.pages.edit', $page) }}" title="Edit"
                                   class="p-1.5 rounded-lg text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button wire:click="toggleFeatured({{ $page->id }})" title="{{ $page->is_featured ? 'Hapus Featured' : 'Jadikan Featured' }}"
                                        class="p-1.5 rounded-lg transition {{ $page->is_featured ? 'text-yellow-500 bg-yellow-50' : 'text-gray-400 hover:text-yellow-500 hover:bg-yellow-50' }}">
                                    <svg class="w-4 h-4" fill="{{ $page->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </button>
                                <button wire:click="duplicate({{ $page->id }})" title="Duplikasi"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                                <button wire:click="delete({{ $page->id }})" wire:confirm="Hapus halaman '{{ $page->title }}'?" title="Hapus"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-gray-400 text-sm">Belum ada halaman.</p>
                            <a href="{{ route('admin.pages.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">Buat halaman pertama &rarr;</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pages->hasPages())
        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $pages->links() }}
        </div>
        @endif
    </div>
</div>
