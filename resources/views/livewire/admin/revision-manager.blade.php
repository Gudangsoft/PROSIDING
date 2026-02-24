<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-4">
            <div class="w-9 h-9 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
            <div>
                <h1 class="text-sm font-bold text-gray-900 leading-none">Revision Manager</h1>
                <p class="text-[11px] text-gray-400 mt-0.5">Kirim & track permintaan revisi ke author</p>
            </div>
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 ml-2">
                <select wire:model.live="conferenceId" class="bg-transparent text-sm text-gray-700 font-medium outline-none pr-2 min-w-[200px]">
                    <option value="0">— Pilih Konferensi —</option>
                    @foreach($conferences as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="flex-1"></div>
        </div>
    </div>

    @if(session('success'))
    <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-gray-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl text-sm font-medium">
        <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center shrink-0">
            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
        </div>
        {{ session('success') }}
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-6 py-6">
        @if(!$conferenceId)
        <div class="flex flex-col items-center justify-center h-[480px] bg-white border border-dashed border-gray-200 rounded-2xl text-center">
            <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
            <p class="text-gray-500 font-semibold">Pilih konferensi terlebih dahulu</p>
        </div>
        @else
        {{-- Filter --}}
        <div class="flex items-center gap-3 mb-5">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul paper..."
                class="flex-1 max-w-sm px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-orange-300">
            <select wire:model.live="statusFilter" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm outline-none">
                <option value="">Semua Status</option>
                <option value="revision_required">Perlu Revisi</option>
                <option value="revised">Sudah Direvisi</option>
                <option value="in_review">Dalam Review</option>
                <option value="accepted">Accepted</option>
            </select>
        </div>

        @if($papers->isEmpty())
        <div class="text-center py-20 text-gray-400 bg-white border border-gray-200 rounded-2xl">Tidak ada paper ditemukan.</div>
        @else
        <div class="space-y-3">
            @foreach($papers as $paper)
            @php $latestReq = $paper->revisionRequests->first(); $isRevisionOpen = $paper->status === 'revision_required'; @endphp
            <div class="group bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-md transition">
                <div class="flex items-start gap-4 p-5">
                    <div class="w-1 self-stretch rounded-full shrink-0 {{ $paper->status === 'revision_required' ? 'bg-orange-400' : ($paper->status === 'revised' ? 'bg-cyan-500' : 'bg-gray-200') }}"></div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <p class="font-semibold text-gray-800 text-sm">{{ Str::limit($paper->title, 80) }}</p>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-{{ $paper->status_color }}-100 text-{{ $paper->status_color }}-700">{{ $paper->status_label }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">{{ $paper->user->name }}</p>

                        @if($latestReq)
                        <div class="bg-orange-50 border border-orange-100 rounded-xl p-3 text-xs">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-semibold text-orange-800 mb-1">Catatan revisi terakhir:</p>
                                    <p class="text-gray-700 leading-relaxed">{{ Str::limit($latestReq->note, 200) }}</p>
                                    @if($latestReq->deadline)
                                    <p class="text-gray-500 mt-1">Deadline: <strong class="{{ $latestReq->deadline->isPast() ? 'text-red-600' : 'text-gray-800' }}">{{ $latestReq->deadline->format('d M Y') }}</strong>
                                        @if($latestReq->deadline->isPast()) <span class="text-red-500 font-semibold">(LEWAT)</span> @endif
                                    </p>
                                    @endif
                                </div>
                                @if($latestReq->resolved_at)
                                <span class="shrink-0 bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full">✓ Selesai</span>
                                @else
                                <button wire:click="markResolved({{ $latestReq->id }})"
                                    class="shrink-0 bg-green-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg hover:bg-green-700 transition">Tandai Selesai</button>
                                @endif
                            </div>
                            @if($latestReq->revised_file_path)
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-green-700 font-medium">Author sudah upload revisi:</span>
                                <a href="{{ Storage::url($latestReq->revised_file_path) }}" target="_blank"
                                    class="text-blue-600 hover:underline font-medium">Download</a>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="shrink-0 flex flex-col gap-2 items-end">
                        <button wire:click="openSendRevision({{ $paper->id }})"
                            class="flex items-center gap-1.5 px-3 py-1.5 bg-orange-600 text-white rounded-xl text-xs font-bold hover:bg-orange-700 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Kirim Revisi
                        </button>
                        <a href="{{ route('admin.paper.detail', $paper) }}" class="text-xs text-blue-600 hover:underline">Lihat Detail →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $papers->links() }}</div>
        @endif
        @endif
    </div>

    {{-- Send Revision Modal --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl" x-data
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center gap-3 p-5 border-b border-gray-100">
                <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">Kirim Permintaan Revisi</h3>
                <button wire:click="$set('showModal',false)" class="ml-auto w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400">✕</button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Catatan Revisi <span class="text-red-500">*</span></label>
                    <textarea wire:model="note" rows="5" placeholder="Jelaskan secara detail bagian yang perlu direvisi..."
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-orange-300 resize-none"></textarea>
                    @error('note')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Deadline Revisi <span class="font-normal text-gray-400">(opsional)</span></label>
                    <input wire:model="deadline" type="date"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-orange-300">
                    @error('deadline')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="bg-orange-50 text-orange-700 text-xs p-3 rounded-xl border border-orange-200">
                    Author akan mendapat notifikasi in-app dan status paper berubah ke "Revision Required".
                </div>
            </div>
            <div class="flex gap-3 px-5 py-4 border-t border-gray-100">
                <button wire:click="$set('showModal',false)" class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">Batal</button>
                <button wire:click="sendRevision" class="flex-1 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-bold hover:bg-orange-700 transition shadow-md shadow-orange-200">
                    <span wire:loading.remove wire:target="sendRevision">Kirim Permintaan</span>
                    <span wire:loading wire:target="sendRevision">Mengirim...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>