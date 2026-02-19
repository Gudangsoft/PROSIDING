<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
    {{-- Back Button --}}
    <a href="{{ route('helpdesk') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Helpdesk
    </a>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
        <p class="text-sm text-green-700">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Ticket Header --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800">{{ $ticket->subject }}</h1>
                <div class="flex items-center gap-3 mt-2 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $ticket->status_color }}-100 text-{{ $ticket->status_color }}-700">
                        {{ $ticket->status_label }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-gray-500">
                        <span class="w-2 h-2 rounded-full bg-{{ $ticket->priority_color }}-500"></span>
                        {{ $ticket->priority_label }}
                    </span>
                    <span class="text-gray-400">{{ $ticket->category_label }}</span>
                    <span class="text-gray-400">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
            @if($ticket->status !== 'closed')
            <button wire:click="closeTicket" wire:confirm="Tutup tiket ini?" class="text-sm text-gray-500 hover:text-red-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            @endif
        </div>

        {{-- Original Message --}}
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                    {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $ticket->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $ticket->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $ticket->message }}</div>
        </div>
    </div>

    {{-- Replies --}}
    @if($replies->count())
    <div class="space-y-4 mb-6">
        @foreach($replies as $reply)
        <div class="bg-white rounded-xl shadow-sm border p-5 {{ $reply->is_admin_reply ? 'border-l-4 border-l-blue-500' : '' }}">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-full {{ $reply->is_admin_reply ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }} flex items-center justify-center font-bold text-xs">
                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $reply->user->name }}
                        @if($reply->is_admin_reply)
                        <span class="text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded ml-1">Admin</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="text-sm text-gray-700 whitespace-pre-wrap ml-10">{{ $reply->message }}</div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Reply Form --}}
    @if($ticket->status !== 'closed')
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Balas</h3>
        <form wire:submit.prevent="sendReply">
            <textarea wire:model="replyMessage" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis balasan..."></textarea>
            @error('replyMessage') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            <div class="flex justify-end mt-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Balasan
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="bg-gray-50 rounded-xl border p-6 text-center">
        <p class="text-sm text-gray-500">Tiket ini sudah ditutup.</p>
    </div>
    @endif
</div>
