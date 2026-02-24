<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-4">
            <div class="w-9 h-9 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            </div>
            <div>
                <h1 class="text-sm font-bold text-gray-900 leading-none">Camera-Ready Manager</h1>
                <p class="text-[11px] text-gray-400 mt-0.5">Review & setujui final paper dari author</p>
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
            <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6H16a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            </div>
            <p class="text-gray-500 font-semibold">Pilih konferensi terlebih dahulu</p>
        </div>
        @else
        {{-- Status tabs --}}
        <div class="flex items-center gap-2 mb-5">
            @foreach([
                ['pending',  'Menunggu Review', 'amber'],
                ['rejected', 'Ditolak',         'red'],
                ['approved', 'Disetujui',        'green'],
                ['all',      'Semua',            'gray'],
            ] as [$val, $lbl, $color])
            <button wire:click="$set('statusFilter','{{ $val }}')"
                class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition border {{ $statusFilter === $val ? 'bg-'.$color.'-100 text-'.$color.'-700 border-'.$color.'-300' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-300' }}">
                {{$lbl}}
                @if(isset($statusCounts[$val]))
                <span class="bg-{{ $color }}-200 text-{{ $color }}-800 px-1.5 py-0.5 rounded-full text-[10px]">{{ $statusCounts[$val] }}</span>
                @endif
            </button>
            @endforeach
            <div class="flex-1"></div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul..."
                class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-purple-300 w-60">
        </div>

        @if($papers->isEmpty())
        <div class="text-center py-20 text-gray-400 bg-white border border-gray-200 rounded-2xl">
            <p class="text-4xl mb-3">📂</p>
            <p>Tidak ada submission camera-ready dengan status ini.</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($papers as $paper)
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden hover:shadow-md transition">
                <div class="flex items-center gap-4 p-5">
                    {{-- Status stripe --}}
                    <div class="w-1 self-stretch rounded-full shrink-0 {{ $paper->camera_ready_status === 'approved' ? 'bg-green-500' : ($paper->camera_ready_status === 'rejected' ? 'bg-red-400' : 'bg-amber-400') }}"></div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-semibold text-gray-800 text-sm">{{ Str::limit($paper->title, 80) }}</p>
                            @php $cr = $paper->camera_ready_status; $crColor = $cr === 'approved' ? 'green' : ($cr === 'rejected' ? 'red' : 'amber'); @endphp
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-{{ $crColor }}-100 text-{{ $crColor }}-700 capitalize">{{ ucfirst($cr) }}</span>
                        </div>
                        <p class="text-xs text-gray-400">{{ $paper->user->name }} • {{ $paper->user->email }}</p>
                        @if($paper->camera_ready_submitted_at)
                        <p class="text-xs text-gray-400 mt-0.5">Upload: {{ $paper->camera_ready_submitted_at->diffForHumans() }}</p>
                        @endif
                        @if($paper->camera_ready_notes && $cr === 'rejected')
                        <p class="text-xs text-red-600 mt-1 bg-red-50 px-2 py-1 rounded-lg">"{{ $paper->camera_ready_notes }}"</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        @if($paper->camera_ready_path)
                        <a href="{{ Storage::url($paper->camera_ready_path) }}" target="_blank"
                            class="flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download
                        </a>
                        @endif
                        @if($cr === 'pending')
                        <button wire:click="approve({{ $paper->id }})"
                            class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition shadow-sm">✓ Setujui</button>
                        <button wire:click="openReject({{ $paper->id }})"
                            class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 rounded-lg text-xs font-bold hover:bg-red-100 transition">✕ Tolak</button>
                        @elseif($cr === 'approved')
                        <span class="text-xs text-green-600 font-semibold">✓ Disetujui</span>
                        @elseif($cr === 'rejected')
                        <button wire:click="approve({{ $paper->id }})"
                            class="px-3 py-1.5 bg-green-50 text-green-700 border border-green-200 rounded-lg text-xs font-medium hover:bg-green-100 transition">Setujui Sekarang</button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $papers->links() }}</div>
        @endif
        @endif
    </div>

    {{-- Reject Modal --}}
    @if($rejectPaperId)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
            <h3 class="text-base font-bold text-gray-900 mb-4">Tolak Camera-Ready</h3>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Catatan untuk Author <span class="text-red-500">*</span></label>
                <textarea wire:model="rejectNote" rows="4" placeholder="Jelaskan alasan penolakan dan apa yang perlu diperbaiki..."
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-300 resize-none"></textarea>
                @error('rejectNote')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 mt-5">
                <button wire:click="$set('rejectPaperId',null)" class="flex-1 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">Batal</button>
                <button wire:click="reject" class="flex-1 py-2.5 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 transition">Tolak & Kirim Notif</button>
            </div>
        </div>
    </div>
    @endif
</div>