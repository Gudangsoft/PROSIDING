<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Auto-assign Reviewer</h2>
                <p class="text-sm text-gray-500 mt-1">Otomatis assign reviewer berdasarkan kecocokan topik bidang</p>
            </div>
            <select wire:model.live="conferenceId" class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
                <option value="0">-- Pilih Konferensi --</option>
                @foreach($conferences as $conf)
                <option value="{{ $conf->id }}">{{ $conf->name }}</option>
                @endforeach
            </select>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Action Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <h3 class="font-semibold text-gray-700 mb-3">Proses Auto-assign</h3>
                <p class="text-xs text-gray-500 mb-4">Sistem akan mencocokkan topik paper dengan bidang keahlian reviewer, mempertimbangkan beban kerja reviewer saat ini.</p>
                <div class="space-y-2">
                    <button wire:click="preview" class="w-full px-4 py-2.5 border border-blue-500 text-blue-600 rounded-xl text-sm font-medium hover:bg-blue-50 transition">
                        🔍 Preview Hasil Assign
                    </button>
                    <button wire:click="assign" wire:confirm="Yakin eksekusi auto-assign? Proses ini tidak bisa dibatalkan." class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                        <span wire:loading.remove>⚡ Eksekusi Auto-assign</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </div>

            {{-- Reviewer Stats --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-5">
                <h3 class="font-semibold text-gray-700 mb-3">Status Reviewer</h3>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @forelse($reviewerStats as $rev)
                    <div class="flex items-center justify-between text-sm p-2 rounded-lg bg-gray-50">
                        <div>
                            <p class="font-medium text-gray-800">{{ $rev->name }}</p>
                            <p class="text-xs text-gray-400">{{ is_array($rev->reviewer_topics) ? implode(', ', $rev->reviewer_topics) : 'Belum ada topik' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-700">{{ $rev->active_reviews }} / {{ $rev->max_review_load }}</p>
                            <p class="text-xs text-gray-400">aktif / maks</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-400 text-sm text-center py-4">Belum ada reviewer dengan topik keahlian.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Preview Results Modal --}}
        @if($showResult)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-3xl shadow-2xl max-h-[85vh] flex flex-col">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Preview Auto-assign ({{ count($assignments) }} paper)</h3>
                    <button wire:click="$set('showResult', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
                <div class="overflow-y-auto flex-1">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600">Judul Paper</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600">Topik</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600">Reviewer</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600">Skor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($assignments as $a)
                            <tr class="{{ $a['reviewerId'] ? 'bg-white' : 'bg-red-50' }}">
                                <td class="px-4 py-3 text-gray-800 text-xs max-w-[200px] truncate">{{ $a['paperTitle'] }}</td>
                                <td class="px-4 py-3 text-xs text-gray-500">{{ $a['paperTopic'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-xs {{ $a['reviewerId'] ? 'text-blue-700 font-medium' : 'text-red-500 italic' }}">{{ $a['reviewerName'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($a['reviewerId'])
                                    <span class="inline-flex px-2 py-0.5 text-xs font-bold rounded-full {{ $a['matchScore'] > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $a['matchScore'] }}</span>
                                    @else
                                    <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-600">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-5 border-t border-gray-200 flex justify-end gap-3">
                    <button wire:click="$set('showResult', false)" class="px-4 py-2 border border-gray-300 rounded-xl text-sm">Tutup</button>
                    <button wire:click="assign" wire:confirm="Eksekusi auto-assign sesuai preview?" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold">Eksekusi Assign</button>
                </div>
            </div>
        </div>
        @endif

        {{-- Current Assignments --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200"><h3 class="font-semibold text-gray-700">Review yang Sudah Di-assign</h3></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Paper</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Reviewer</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 hidden lg:table-cell">Rekomendasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($assigned as $review)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800 text-xs max-w-[200px] truncate">{{ $review->paper?->title }}</td>
                        <td class="px-4 py-3 hidden md:table-cell text-gray-700 text-xs">{{ $review->reviewer?->name }}</td>
                        <td class="px-4 py-3 text-center"><span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">{{ $review->status }}</span></td>
                        <td class="px-4 py-3 text-center hidden lg:table-cell text-xs text-gray-500">{{ $review->recommendation ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-10 text-gray-400">Belum ada assign reviewer.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($assigned->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">{{ $assigned->links() }}</div>
            @endif
        </div>
    </div>
</div>
