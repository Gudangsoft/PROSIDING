<div class="max-w-5xl mx-auto px-4 py-8">
    {{-- Welcome --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Halo, {{ auth()->user()->name }} 👋</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau status paper dan aktivitas Anda di sini.</p>
        </div>
        @if($activeConference)
        <div class="hidden sm:block text-right">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-0.5">Konferensi Aktif</p>
            <p class="text-sm font-bold text-gray-800">{{ $activeConference->name }}</p>
        </div>
        @endif
    </div>

    {{-- Pending Actions --}}
    @if($pendingActions->count())
    <div class="mb-6 space-y-2">
        @foreach($pendingActions as $action)
        @php
            $colorMap = [
                'revision' => ['bg-orange-50','border-orange-200','text-orange-800','text-orange-600','bg-orange-600 hover:bg-orange-700'],
                'camera_ready' => ['bg-blue-50','border-blue-200','text-blue-800','text-blue-600','bg-blue-600 hover:bg-blue-700'],
                'payment' => ['bg-yellow-50','border-yellow-200','text-yellow-800','text-yellow-600','bg-yellow-600 hover:bg-yellow-700'],
            ];
            $c = $colorMap[$action['type']] ?? ['bg-gray-50','border-gray-200','text-gray-800','text-gray-600','bg-gray-600 hover:bg-gray-700'];
        @endphp
        <div class="flex items-center gap-4 px-4 py-3 {{ $c[0] }} border {{ $c[1] }} rounded-xl">
            <div class="flex-1">
                <p class="text-sm font-bold {{ $c[2] }}">{{ $action['label'] }}</p>
                <p class="text-xs {{ $c[3] }} truncate max-w-sm mt-0.5">{{ $action['paper']->title }}</p>
            </div>
            <a href="{{ $action['url'] }}" class="text-xs font-bold text-white px-3.5 py-1.5 rounded-lg {{ $c[4] }} transition whitespace-nowrap">
                Tindak Lanjut →
            </a>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
        @php
            $statCards = [
                ['label' => 'Total Paper', 'key' => 'total', 'icon' => '📄', 'color' => 'gray'],
                ['label' => 'Dalam Review', 'key' => 'under_review', 'icon' => '🔍', 'color' => 'blue'],
                ['label' => 'Diterima', 'key' => 'accepted', 'icon' => '✅', 'color' => 'green'],
                ['label' => 'Revisi', 'key' => 'revision_required', 'icon' => '✏️', 'color' => 'orange'],
                ['label' => 'Camera-Ready', 'key' => 'camera_ready_pending', 'icon' => '📷', 'color' => 'purple'],
                ['label' => 'Selesai', 'key' => 'completed', 'icon' => '🏆', 'color' => 'teal'],
            ];
        @endphp
        @foreach($statCards as $sc)
        <div class="bg-white border border-gray-200 rounded-2xl p-4 text-center">
            <p class="text-2xl mb-1">{{ $sc['icon'] }}</p>
            <p class="text-2xl font-black text-gray-900">{{ $stats[$sc['key']] }}</p>
            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $sc['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Papers list --}}
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <p class="font-bold text-gray-900 text-sm">Paper Saya</p>
                <a href="{{ route('author.papers') }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            @if($papers->isEmpty())
            <div class="p-12 text-center">
                <p class="text-4xl mb-3">📭</p>
                <p class="text-gray-500 font-semibold">Belum ada paper disubmit</p>
                <a href="{{ route('author.paper.submit') }}" class="mt-3 inline-block text-sm text-blue-600 hover:underline font-medium">Submit sekarang →</a>
            </div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($papers->take(6) as $paper)
                <div class="px-5 py-4 flex items-start gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $paper->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ optional($paper->conference)->name ?? '-' }}</p>
                        @if($paper->pendingRevisionRequests && $paper->pendingRevisionRequests->count())
                        <p class="text-xs font-semibold text-orange-600 mt-1">⚠ {{ $paper->pendingRevisionRequests->count() }} revisi menunggu</p>
                        @endif
                    </div>
                    <div class="text-right shrink-0">
                        <span class="inline-block text-[11px] font-bold px-2 py-0.5 rounded-full
                            {{ match($paper->status) {
                                'accepted', 'payment_verified', 'completed' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'revision_required' => 'bg-orange-100 text-orange-700',
                                'in_review', 'screening' => 'bg-blue-100 text-blue-700',
                                default => 'bg-gray-100 text-gray-600'
                            } }}">
                            {{ $paper->status_label }}</span>
                        <div class="flex gap-1 mt-2 justify-end">
                            @if(in_array($paper->status, ['revision_required', 'revised']))
                            <a href="{{ route('author.paper.revisions', $paper) }}"
                                class="text-[11px] text-orange-600 border border-orange-200 hover:bg-orange-50 px-2 py-0.5 rounded-lg font-semibold">Revisi</a>
                            @endif
                            @if(in_array($paper->status, ['accepted', 'payment_verified']))
                            <a href="{{ route('author.paper.camera-ready', $paper) }}"
                                class="text-[11px] text-blue-600 border border-blue-200 hover:bg-blue-50 px-2 py-0.5 rounded-lg font-semibold">Camera-Ready</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Notifications --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <p class="font-bold text-gray-900 text-sm">Notifikasi Terbaru</p>
                <span class="text-[11px] font-semibold text-gray-400">5 terakhir</span>
            </div>
            @if($recentNotifs->isEmpty())
            <div class="p-10 text-center">
                <p class="text-3xl mb-2">🔔</p>
                <p class="text-gray-400 text-sm">Belum ada notifikasi.</p>
            </div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($recentNotifs as $notif)
                <div class="px-4 py-3 flex gap-3 {{ $notif->read_at ? '' : 'bg-blue-50' }}">
                    <div class="w-2 h-2 rounded-full mt-1.5 shrink-0 {{ $notif->read_at ? 'bg-gray-300' : 'bg-blue-500' }}"></div>
                    <div>
                        <p class="text-xs font-semibold text-gray-800">{{ $notif->title }}</p>
                        <p class="text-[11px] text-gray-500 mt-0.5 leading-relaxed">{{ Str::limit($notif->message, 70) }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-4 py-3 border-t border-gray-100">
                <a href="{{ route('author.notifications') }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat semua notifikasi →</a>
            </div>
            @endif
        </div>
    </div>
</div>