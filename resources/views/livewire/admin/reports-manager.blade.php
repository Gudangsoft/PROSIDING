<div>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Export Laporan</h2>

        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            {{-- Jenis Laporan --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-3">
                <h3 class="font-semibold text-gray-700">Jenis Laporan</h3>
                <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer {{ $reportType==='papers' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }}">
                    <input wire:model.live="reportType" type="radio" value="papers" class="text-blue-600">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Laporan Paper</p>
                        <p class="text-xs text-gray-500">Semua paper beserta status dan info similarity</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer {{ $reportType==='participants' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:bg-gray-50' }}">
                    <input wire:model.live="reportType" type="radio" value="participants" class="text-indigo-600">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Laporan Peserta</p>
                        <p class="text-xs text-gray-500">Daftar peserta & author terdaftar</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer {{ $reportType==='revenue' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:bg-gray-50' }}">
                    <input wire:model.live="reportType" type="radio" value="revenue" class="text-emerald-600">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Laporan Pendapatan</p>
                        <p class="text-xs text-gray-500">Pembayaran yang sudah terverifikasi</p>
                    </div>
                </label>
            </div>

            {{-- Filter Options --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-4">
                <h3 class="font-semibold text-gray-700">Filter</h3>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Konferensi</label>
                    <select wire:model.live="conferenceId" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                        <option value="0">Semua Konferensi</option>
                        @foreach($conferences as $conf)
                        <option value="{{ $conf->id }}">{{ $conf->name }}</option>
                        @endforeach
                    </select>
                </div>

                @if($reportType === 'papers')
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Status Paper</label>
                    <select wire:model.live="paperStatus" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\Paper::STATUS_LABELS as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if($reportType === 'revenue')
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Dari</label>
                        <input wire:model.live="startDate" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Sampai</label>
                        <input wire:model.live="endDate" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                    </div>
                </div>
                @endif

                {{-- Preview --}}
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">{{ $stats['label'] }}</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                    </div>
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>

        {{-- Download Button --}}
        <div class="flex justify-end">
            <a href="{{ $this->exportUrl() }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-emerald-700 transition shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Download Excel
            </a>
        </div>
    </div>
</div>
