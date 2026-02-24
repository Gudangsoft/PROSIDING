<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Statistik & Analitik</h2>
            <div class="flex items-center gap-3">
                <select wire:model.live="conferenceId" class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
                    <option value="0">Semua Konferensi</option>
                    @foreach($conferences as $conf)
                    <option value="{{ $conf->id }}">{{ $conf->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="period" class="px-3 py-2 border border-gray-300 rounded-xl text-sm">
                    <option value="7">7 Hari</option>
                    <option value="30" selected>30 Hari</option>
                    <option value="90">90 Hari</option>
                </select>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Paper</p>
                <p class="text-3xl font-bold text-blue-600 mt-1">{{ $totalPapers }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $acceptedPapers }} diterima</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pengguna</p>
                <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $totalUsers }}</p>
                <p class="text-xs text-gray-400 mt-1">Author & Peserta</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pendapatan</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-xs text-red-400 mt-1">{{ $pendingPayments }} pembayaran pending</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Konversi Author</p>
                <p class="text-3xl font-bold text-violet-600 mt-1">{{ $conversionRate }}%</p>
                <p class="text-xs text-gray-400 mt-1">{{ $authorsWithPaper }} / {{ $registeredAuthors }} submit paper</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Submissions Chart --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-5">
                <h3 class="font-semibold text-gray-700 mb-4">Submission Paper ({{ $period }} hari terakhir)</h3>
                <canvas id="submissionsChart" height="100"></canvas>
            </div>
            {{-- Status Pie --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <h3 class="font-semibold text-gray-700 mb-4">Distribusi Status</h3>
                <canvas id="statusChart"></canvas>
                <div class="mt-3 space-y-1">
                    @foreach($statusDist as $status => $count)
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-600">{{ \App\Models\Paper::STATUS_LABELS[$status] ?? $status }}</span>
                        <span class="font-semibold text-gray-800">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Revenue Chart --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-700 mb-4">Pendapatan Harian (Rp)</h3>
            <canvas id="revenueChart" height="80"></canvas>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    (function() {
        const labels  = @json($chartLabels);
        const subData = @json($chartData);
        const revData = @json($revenueData);
        const statusLabels = @json(array_keys($statusDist));
        const statusData   = @json(array_values($statusDist));

        @php
            $statusNamesMap = collect(array_keys($statusDist))
                ->mapWithKeys(fn($k) => [$k, \App\Models\Paper::STATUS_LABELS[$k] ?? $k])
                ->toArray();
        @endphp
        const statusNames = @json($statusNamesMap);

        const palette = ['#3b82f6','#6366f1','#22c55e','#f59e0b','#ef4444','#14b8a6','#a855f7','#f97316','#06b6d4','#84cc16'];

        // Submissions
        new Chart(document.getElementById('submissionsChart'), {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Submission', data: subData, backgroundColor: '#3b82f680', borderColor: '#3b82f6', borderWidth: 1.5, borderRadius: 4 }] },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });

        // Status Pie
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: { labels: statusLabels.map(k => statusNames[k] ?? k), datasets: [{ data: statusData, backgroundColor: palette }] },
            options: { plugins: { legend: { display: false } }, cutout: '60%' }
        });

        // Revenue
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: { labels, datasets: [{ label: 'Pendapatan', data: revData, borderColor: '#22c55e', backgroundColor: '#22c55e20', tension: 0.4, fill: true, pointRadius: 3 }] },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });
    })();
    </script>
    @endpush
</div>
