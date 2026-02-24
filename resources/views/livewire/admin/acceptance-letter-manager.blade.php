<div class="min-h-screen bg-gray-50" x-data="{}">
    {{-- Header --}}
    <div class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-4">
            <div class="w-9 h-9 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h1 class="text-sm font-bold text-gray-900 leading-none">Acceptance Letter</h1>
                <p class="text-[11px] text-gray-400 mt-0.5">Generate & kelola surat penerimaan paper</p>
            </div>
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 ml-2">
                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <select wire:model.live="conferenceId" class="bg-transparent text-sm text-gray-700 font-medium outline-none pr-2 min-w-[200px]">
                    <option value="0">— Pilih Konferensi —</option>
                    @foreach($conferences as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="flex-1"></div>
            @if($conferenceId)
            <button wire:click="generateBulk" wire:confirm="Generate acceptance letter untuk semua paper yang belum punya surat? Proses ini tidak bisa dibatalkan."
                class="flex items-center gap-1.5 px-4 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Generate Semua
            </button>
            @endif
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
        <div class="flex flex-col items-center justify-center h-[500px] bg-white border border-dashed border-gray-200 rounded-2xl text-center">
            <div class="w-20 h-20 bg-green-50 rounded-2xl flex items-center justify-center mb-5">
                <svg class="w-10 h-10 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-2">Pilih Konferensi</h3>
            <p class="text-sm text-gray-400">Pilih konferensi untuk mengelola acceptance letter.</p>
        </div>
        @else
        {{-- Filter bar --}}
        <div class="flex items-center gap-3 mb-5">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul paper atau nama author..."
                class="flex-1 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-green-300">
            <select wire:model.live="statusFilter" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm outline-none">
                <option value="">Semua Status</option>
                <option value="issued">Sudah Terbit</option>
                <option value="not_issued">Belum Terbit</option>
            </select>
        </div>

        @if($papers->isEmpty())
        <div class="text-center py-20 text-gray-400">Tidak ada paper yang memenuhi filter.</div>
        @else
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Paper / Author</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Surat</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($papers as $paper)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-800 text-sm leading-snug">{{ Str::limit($paper->title, 70) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $paper->user->name }} • {{ $paper->user->email }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-{{ $paper->status_color }}-100 text-{{ $paper->status_color }}-700">{{ $paper->status_label }}</span>
                        </td>
                        <td class="px-4 py-4">
                            @if($paper->acceptance_letter_path)
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="text-xs text-green-700 font-medium">Terbit</span>
                                <span class="text-xs text-gray-400">{{ $paper->acceptance_letter_sent_at?->format('d M Y') }}</span>
                            </div>
                            @else
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                                <span class="text-xs text-gray-400">Belum terbit</span>
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2 justify-end">
                                @if($paper->acceptance_letter_path)
                                <a href="{{ Storage::url($paper->acceptance_letter_path) }}" target="_blank"
                                    class="flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </a>
                                <button wire:click="generateLetter({{ $paper->id }})" class="px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-medium hover:bg-amber-100 transition">Regenerate</button>
                                <button wire:click="deleteLetter({{ $paper->id }})" wire:confirm="Hapus acceptance letter ini?"
                                    class="w-7 h-7 flex items-center justify-center bg-red-50 text-red-500 border border-red-200 rounded-lg hover:bg-red-100 transition">✕</button>
                                @else
                                <button wire:click="generateLetter({{ $paper->id }})"
                                    class="flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Generate
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $papers->links() }}</div>
        @endif
        @endif
    </div>
</div>