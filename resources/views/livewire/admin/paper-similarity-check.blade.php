<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Cek Kemiripan Paper</h2>
            <p class="text-sm text-gray-500">Deteksi duplikasi antar submission berdasarkan judul dan abstrak</p>
        </div>

        @if(session('success'))<div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">✅ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">❌ {{ session('error') }}</div>@endif

        <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Konferensi</label>
                    <select wire:model.live="conferenceId" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                        <option value="0">— Pilih Konferensi —</option>
                        @foreach($conferences as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tampilkan mirip ≥ <span wire:text="$wire.threshold"></span>%</label>
                    <input wire:model.live="threshold" type="range" min="5" max="90" step="5" class="w-full">
                </div>
                <div>
                    <button wire:click="runSimilarityCheck"
                        wire:loading.attr="disabled"
                        class="w-full px-4 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-semibold hover:bg-orange-700 transition disabled:opacity-50">
                        <span wire:loading.remove>🔍 Jalankan Pengecekan ({{ $paperCount }} paper)</span>
                        <span wire:loading>Memproses... harap tunggu</span>
                    </button>
                </div>
            </div>
        </div>

        @if($results->isEmpty() && $conferenceId)
        <div class="text-center py-16 text-gray-400">
            <p class="text-5xl mb-3">✅</p>
            <p>Tidak ada pasang paper dengan kemiripan ≥ {{ $threshold }}%. Jalankan pengecekan untuk mendapatkan hasil terbaru.</p>
        </div>
        @elseif($results->isNotEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-700">{{ $results->count() }} pasang paper ditemukan</h3>
                <span class="text-xs text-gray-500">Threshold: ≥ {{ $threshold }}%</span>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 w-5/12">Paper A</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 w-5/12">Paper B</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 w-1/6">Kemiripan</th>
                        <th class="w-10"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($results as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @if($r->paper)
                            <a href="{{ route('admin.paper.detail', $r->paper) }}" class="text-blue-600 hover:underline text-xs font-medium line-clamp-2">{{ $r->paper->title }}</a>
                            <span class="inline-flex mt-1 px-1.5 py-0.5 text-[10px] rounded bg-gray-100 text-gray-600">{{ $r->paper->status }}</span>
                            @else <span class="text-gray-400 text-xs">Paper dihapus</span> @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($r->similarPaper)
                            <a href="{{ route('admin.paper.detail', $r->similarPaper) }}" class="text-blue-600 hover:underline text-xs font-medium line-clamp-2">{{ $r->similarPaper->title }}</a>
                            <span class="inline-flex mt-1 px-1.5 py-0.5 text-[10px] rounded bg-gray-100 text-gray-600">{{ $r->similarPaper->status }}</span>
                            @else <span class="text-gray-400 text-xs">Paper dihapus</span> @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full
                                {{ $r->similarity_percent >= 70 ? 'bg-red-100 text-red-700' : ($r->similarity_percent >= 40 ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ number_format($r->similarity_percent, 1) }}%
                            </span>
                        </td>
                        <td class="px-2">
                            <button wire:click="deleteSimilarity({{ $r->id }})" class="text-gray-400 hover:text-red-500 text-sm">×</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-700">
            💡 <strong>Warna indikator:</strong> 🔴 ≥ 70% kemungkinan duplikat &bull; 🟠 40–70% perlu diperiksa manual &bull; 🟡 di bawah 40% wajar
        </div>
        @endif
    </div>
</div>
