<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Helpdesk Management</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola tiket bantuan dari pengguna</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
        <p class="text-sm text-green-700">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4 border text-center">
            <p class="text-2xl font-bold text-gray-700">{{ $counts['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Tiket</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $counts['open'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Open</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $counts['in_progress'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Diproses</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border text-center">
            <p class="text-2xl font-bold text-green-600">{{ $counts['resolved'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Selesai</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari tiket atau nama pengguna..." class="flex-1 border-gray-300 rounded-lg shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
        <select wire:model.live="statusFilter" class="border-gray-300 rounded-lg shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Semua Status</option>
            @foreach(\App\Models\HelpdeskTicket::STATUSES as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>
        <select wire:model.live="categoryFilter" class="border-gray-300 rounded-lg shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Semua Kategori</option>
            @foreach(\App\Models\HelpdeskTicket::CATEGORIES as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>
        <select wire:model.live="priorityFilter" class="border-gray-300 rounded-lg shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Semua Prioritas</option>
            @foreach(\App\Models\HelpdeskTicket::PRIORITIES as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tickets Table --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        @if($tickets->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium text-gray-600">ID</th>
                        <th class="px-4 py-3 font-medium text-gray-600">Subjek</th>
                        <th class="px-4 py-3 font-medium text-gray-600">Pengguna</th>
                        <th class="px-4 py-3 font-medium text-gray-600">Kategori</th>
                        <th class="px-4 py-3 font-medium text-gray-600">Prioritas</th>
                        <th class="px-4 py-3 font-medium text-gray-600">Status</th>
                        <th class="px-4 py-3 font-medium text-gray-600">Balasan</th>
                        <th class="px-4 py-3 font-medium text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($tickets as $ticket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500">#{{ $ticket->id }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.helpdesk.detail', $ticket) }}" class="text-blue-600 hover:underline font-medium">{{ Str::limit($ticket->subject, 40) }}</a>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $ticket->user->name }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs text-gray-600">{{ $ticket->category_label }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-{{ $ticket->priority_color }}-500"></span>
                                <span class="text-xs">{{ $ticket->priority_label }}</span>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->status_color }}-100 text-{{ $ticket->status_color }}-700">
                                {{ $ticket->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-500">{{ $ticket->replies_count }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $ticket->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.helpdesk.detail', $ticket) }}" class="text-blue-500 hover:text-blue-700" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if($ticket->status === 'open')
                                <button wire:click="updateStatus({{ $ticket->id }}, 'in_progress')" class="text-indigo-500 hover:text-indigo-700" title="Proses">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                @endif
                                @if(in_array($ticket->status, ['open', 'in_progress']))
                                <button wire:click="updateStatus({{ $ticket->id }}, 'resolved')" class="text-green-500 hover:text-green-700" title="Selesaikan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                @endif
                                <button wire:click="delete({{ $ticket->id }})" wire:confirm="Hapus tiket ini?" class="text-red-500 hover:text-red-700" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">{{ $tickets->links() }}</div>
        @else
        <div class="px-6 py-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <p class="text-gray-500 text-sm">Belum ada tiket helpdesk.</p>
        </div>
        @endif
    </div>
</div>
