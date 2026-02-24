<div class="min-h-screen bg-gray-50" x-data="{}">
    {{-- Header --}}
    <div class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-4">
            <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <h1 class="text-sm font-bold text-gray-900 leading-none">Analytics & Monitoring</h1>
                <p class="text-[11px] text-gray-400 mt-0.5">Statistik submission dan beban kerja reviewer</p>
            </div>
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 ml-2">
                <select wire:model.live="conferenceId" class="bg-transparent text-sm text-gray-700 font-medium outline-none pr-2 min-w-[200px]">
                    <option value="0">— Pilih Konferensi —</option>
                    @foreach($conferences as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            @if($conferenceId)
            <select wire:model.live="periodDays" class="px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-sm outline-none">
                <option value="7">7 hari terakhir</option>
                <option value="14">14 hari terakhir</option>
                <option value="30">30 hari terakhir</option>
                <option value="60">60 hari terakhir</option>
                <option value="90">90 hari terakhir</option>
            </select>
            @endif
            <div class="flex-1"></div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-6">
        @if(!$conferenceId)
        <div class="flex flex-col items-center justify-center h-[480px] bg-white border border-dashed border-gray-200 rounded-2xl text-center">
            <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <p class="text-gray-500 font-semibold">Pilih konferensi untuk melihat analytics</p>
        </div>
        @else

        {{-- Summary cards --}}
        @php
        $cards = [
            ['Total Paper',    $totals['total'],       'blue',  'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['Dalam Periode',  $totals['period_new'],  'violet','M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['Dalam Review',   $totals['in_review'],   'amber', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['Accepted',       $totals['accepted'],    'green', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['Selesai (Paid)', $totals['paid'],        'emerald','M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
            ['Completed',      $totals['completed'],   'teal',  'M5 13l4 4L19 7'],
        ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            @foreach($cards as [$lbl, $val, $color, $path])
            <div class="bg-white border border-gray-200 rounded-2xl p-4 text-center hover:shadow-sm transition">
                <div class="w-9 h-9 bg-{{ $color }}-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-4.5 h-4.5 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/></svg>
                </div>
                <p class="text-2xl font-black text-gray-900">{{ $val }}</p>
                <p class="text-[11px] text-gray-400 mt-0.5">{{ $lbl }}</p>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
            {{-- Timeline Chart --}}
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-5">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Timeline Submission ({{ $periodDays }} hari)</h3>
                <div style="height:220px">
                    <canvas id="timelineChart"></canvas>
                </div>
            </div>

            {{-- Status Breakdown --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Distribusi Status</h3>
                @if($statusBreak->isEmpty())
                <p class="text-center text-gray-400 text-sm py-8">Tidak ada data.</p>
                @else
                <div class="space-y-2">
                    @php $maxStatus = $statusBreak->max('total'); @endphp
                    @foreach($statusBreak as $s)
                    @php
                        $statusColors = ['submitted'=>'blue','screening'=>'yellow','in_review'=>'indigo','revision_required'=>'orange','revised'=>'cyan','accepted'=>'green','rejected'=>'red','payment_pending'=>'amber','payment_uploaded'=>'purple','payment_verified'=>'emerald','deliverables_pending'=>'teal','completed'=>'green'];
                        $sc = $statusColors[$s->status] ?? 'gray';
                        $pct = $maxStatus > 0 ? round($s->total / $maxStatus * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-0.5">
                            <span class="text-gray-600 capitalize">{{ str_replace('_', ' ', $s->status) }}</span>
                            <span class="font-bold text-gray-800">{{ $s->total }}</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-{{ $sc }}-500 transition-all" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Reviewer Workload --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800">Beban Kerja Reviewer</h3>
                <span class="text-xs text-gray-400">{{ $reviewerLoad->count() }} reviewer aktif</span>
            </div>
            @if($reviewerLoad->isEmpty())
            <div class="text-center py-12 text-gray-400 text-sm">Tidak ada reviewer untuk konferensi ini.</div>
            @else
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Reviewer</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wide">Ditugaskan</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wide">Selesai</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wide">Pending</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Progress</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($reviewerLoad as $rev)
                    @php
                        $pending = $rev->total_assigned - $rev->completed;
                        $pct = $rev->total_assigned > 0 ? round($rev->completed / $rev->total_assigned * 100) : 0;
                    @endphp
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-5 py-3">
                            <p class="font-semibold text-gray-800">{{ $rev->name }}</p>
                            <p class="text-xs text-gray-400">{{ $rev->email }}</p>
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-gray-700">{{ $rev->total_assigned }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-green-600">{{ $rev->completed }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold {{ $pending > 0 ? 'text-amber-600' : 'text-gray-400' }}">{{ $pending }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $pct >= 100 ? 'bg-green-500' : ($pct >= 50 ? 'bg-blue-500' : 'bg-amber-500') }}" style="width:{{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-8">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        @endif
    </div>
</div>

@if($conferenceId && $timeline->isNotEmpty())
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('timelineChart');
    if (!ctx) return;
    const labels = @json($timeline->keys());
    const data   = @json($timeline->values());
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Submissions',
                data,
                backgroundColor: 'rgba(99,102,241,0.2)',
                borderColor: 'rgba(99,102,241,1)',
                borderWidth: 2,
                borderRadius: 6,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display:false }, ticks: { maxTicksLimit: 10, font: { size:10 } } },
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size:10 } }, grid: { color:'#f3f4f6' } }
            }
        }
    });
});
</script>
@endpush
@endif