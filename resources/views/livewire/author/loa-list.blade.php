<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Letter of Acceptance (LOA)</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar LOA yang telah diterbitkan untuk paper Anda</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    @if($papers->count())
    <div class="space-y-4">
        @foreach($papers as $paper)
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0 mr-4">
                    <h3 class="text-lg font-semibold text-gray-800 truncate">{{ $paper->title }}</h3>
                    <div class="flex items-center gap-3 mt-2 text-sm text-gray-500">
                        <span>Topik: {{ $paper->topic ?? '-' }}</span>
                        <span>&bull;</span>
                        <span>Diterima: {{ $paper->accepted_at->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    @php $color = $paper->status_color; @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($color==='green') bg-green-100 text-green-800
                        @elseif($color==='amber' || $color==='yellow') bg-yellow-100 text-yellow-800
                        @elseif($color==='purple') bg-purple-100 text-purple-800
                        @else bg-blue-100 text-blue-800 @endif">
                        {{ $paper->status_label }}
                    </span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- LOA Link --}}
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h4 class="text-sm font-bold text-green-800">Letter of Acceptance</h4>
                    </div>
                    <a href="{{ $paper->loa_link }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1 text-sm text-green-700 bg-green-100 hover:bg-green-200 px-3 py-1.5 rounded-lg font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Buka LOA
                    </a>
                </div>

                {{-- Payment Status --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <h4 class="text-sm font-bold text-gray-800">Pembayaran</h4>
                    </div>
                    @if($paper->payment)
                        <div class="text-sm">
                            <p class="text-gray-600">Tagihan: <span class="font-bold text-gray-800">Rp {{ number_format($paper->payment->amount, 0, ',', '.') }}</span></p>
                            <p class="text-gray-500 text-xs mt-1">{{ $paper->payment->invoice_number }}</p>

                            {{-- Info Rekening --}}
                            @php
                                $loaConf = $paper->conference;
                                $loaBankName = $loaConf->payment_bank_name ?? null;
                                $loaBankAccount = $loaConf->payment_bank_account ?? null;
                                $loaAccountHolder = $loaConf->payment_account_holder ?? null;
                            @endphp
                            @if($loaBankName || $loaBankAccount)
                            <div class="mt-2 bg-blue-50 border border-blue-100 rounded-lg p-2.5">
                                <p class="text-xs font-bold text-blue-800 mb-1">Transfer ke:</p>
                                <div class="text-xs space-y-0.5">
                                    @if($loaBankName)<p class="text-gray-700">Bank: <span class="font-medium">{{ $loaBankName }}</span></p>@endif
                                    @if($loaBankAccount)<p class="text-gray-700">No. Rekening: <span class="font-mono font-bold text-gray-900">{{ $loaBankAccount }}</span></p>@endif
                                    @if($loaAccountHolder)<p class="text-gray-700">a.n. <span class="font-medium">{{ $loaAccountHolder }}</span></p>@endif
                                </div>
                            </div>
                            @endif

                            @if($paper->payment->status === 'pending')
                                <a href="{{ route('author.paper.payment', $paper) }}" class="inline-flex items-center gap-1 mt-2 text-xs text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded font-medium transition">
                                    Upload Bukti Bayar
                                </a>
                            @elseif($paper->payment->status === 'uploaded')
                                <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Menunggu Verifikasi</span>
                            @elseif($paper->payment->status === 'verified')
                                <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">✓ Terverifikasi</span>
                            @elseif($paper->payment->status === 'rejected')
                                <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                <a href="{{ route('author.paper.payment', $paper) }}" class="inline-flex items-center gap-1 mt-1 text-xs text-red-600 hover:text-red-800 font-medium">
                                    Upload Ulang
                                </a>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic">Belum ada tagihan</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <h3 class="text-lg font-semibold text-gray-600 mb-1">Belum ada LOA</h3>
        <p class="text-sm text-gray-400">LOA akan tersedia setelah paper Anda diterima oleh reviewer.</p>
    </div>
    @endif
</div>
