<div class="max-w-4xl mx-auto py-8 px-4">
    <a href="{{ route('author.paper.detail', $paper) }}" class="text-blue-600 hover:text-blue-800 text-sm">&larr; Kembali ke Detail Paper</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2 mb-1">Pembayaran</h1>
    <p class="text-gray-500 text-sm mb-6">{{ $paper->title }}</p>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Invoice Info --}}
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Detail Invoice</h3>
            @if($payment->id)
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">No. Invoice</span>
                    <span class="font-mono font-medium">{{ $payment->invoice_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jumlah</span>
                    <span class="font-bold text-lg text-blue-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Deskripsi</span>
                    <span>{{ $payment->description }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($payment->status==='verified') bg-green-100 text-green-800
                        @elseif($payment->status==='uploaded') bg-blue-100 text-blue-800
                        @elseif($payment->status==='rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
                @if($payment->paid_at)
                <div class="flex justify-between">
                    <span class="text-gray-500">Dibayar</span>
                    <span>{{ $payment->paid_at->format('d M Y H:i') }}</span>
                </div>
                @endif
                @if($payment->admin_notes)
                <div class="bg-red-50 p-3 rounded-lg mt-2">
                    <p class="text-red-700 text-xs font-medium">Catatan Admin:</p>
                    <p class="text-red-600 text-sm">{{ $payment->admin_notes }}</p>
                </div>
                @endif

                {{-- Info Rekening Bank --}}
                @php
                    $puConf = $paper->conference;
                    $puBankName = $puConf->payment_bank_name ?? null;
                    $puBankAccount = $puConf->payment_bank_account ?? null;
                    $puAccountHolder = $puConf->payment_account_holder ?? null;
                    $puInstructions = $puConf->payment_instructions ?? null;
                    $paymentMethods = $puConf->payment_methods ?? [];
                    $activePaymentMethods = array_filter($paymentMethods, fn($m) => $m['is_active'] ?? true);
                @endphp

                {{-- Multiple Payment Methods --}}
                @if(!empty($activePaymentMethods))
                <div class="space-y-3 mt-4">
                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Metode Pembayaran Tersedia
                    </h4>
                    @foreach($activePaymentMethods as $pm)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 bg-blue-600 text-white text-xs font-bold rounded">{{ $pm['type'] ?? 'Bank Transfer' }}</span>
                                <span class="font-bold text-gray-800">{{ $pm['name'] ?? '-' }}</span>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-500">Nominal</div>
                                <div class="text-lg font-bold text-blue-700">Rp {{ number_format($pm['amount'] ?? 0, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="space-y-1.5 text-sm">
                            @if(!empty($pm['account_number']))
                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    @if(str_contains($pm['type'] ?? '', 'Wallet') || str_contains($pm['type'] ?? '', 'QRIS'))
                                        No. HP / ID
                                    @else
                                        No. Rekening
                                    @endif
                                </span>
                                <span class="font-mono font-bold text-gray-800">{{ $pm['account_number'] }}</span>
                            </div>
                            @endif
                            @if(!empty($pm['account_holder']))
                            <div class="flex justify-between">
                                <span class="text-gray-600">Atas Nama</span>
                                <span class="font-bold text-gray-800">{{ $pm['account_holder'] }}</span>
                            </div>
                            @endif
                        </div>
                        @if(!empty($pm['instructions']))
                        <div class="mt-2 pt-2 border-t border-blue-200">
                            <p class="text-xs text-blue-700"><strong>Instruksi:</strong> {{ $pm['instructions'] }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Legacy Payment Info (if no payment methods) --}}
                @if(empty($activePaymentMethods) && ($puBankName || $puBankAccount))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-3">
                    <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Rekening Tujuan Pembayaran
                    </h4>
                    <div class="space-y-1.5 text-sm">
                        @if($puBankName)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Bank</span>
                            <span class="font-bold text-gray-800">{{ $puBankName }}</span>
                        </div>
                        @endif
                        @if($puBankAccount)
                        <div class="flex justify-between">
                            <span class="text-gray-500">No. Rekening</span>
                            <span class="font-mono font-bold text-gray-800">{{ $puBankAccount }}</span>
                        </div>
                        @endif
                        @if($puAccountHolder)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Atas Nama</span>
                            <span class="font-bold text-gray-800">{{ $puAccountHolder }}</span>
                        </div>
                        @endif
                    </div>
                    @if($puInstructions)
                    <div class="mt-2 pt-2 border-t border-blue-200">
                        <p class="text-xs text-blue-700">{{ $puInstructions }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @else
                <p class="text-gray-400 text-sm">Invoice belum dibuat oleh admin.</p>
            @endif
        </div>

        {{-- Upload Proof --}}
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Upload Bukti Pembayaran</h3>

            @if($payment->id && in_array($payment->status, ['pending', 'rejected']))
            <form wire:submit="uploadProof" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select wire:model="paymentMethod" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="QRIS">QRIS</option>
                        <option value="E-Wallet">E-Wallet</option>
                        <option value="Virtual Account">Virtual Account</option>
                    </select>
                    @error('paymentMethod') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran</label>
                    <input type="file" wire:model="proofFile" class="w-full text-sm" accept=".jpg,.jpeg,.png,.pdf">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, atau PDF. Maks. 5MB</p>
                    @error('proofFile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <div wire:loading wire:target="proofFile" class="text-blue-500 text-xs mt-1">Mengunggah...</div>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 font-medium text-sm">Upload Bukti Pembayaran</button>
            </form>
            @elseif($payment->status === 'uploaded')
                <div class="text-center py-6">
                    <div class="text-blue-500 text-4xl mb-2">⏳</div>
                    <p class="text-gray-600 font-medium">Menunggu verifikasi admin</p>
                    <p class="text-gray-400 text-sm mt-1">Bukti pembayaran telah diunggah</p>
                </div>
            @elseif($payment->status === 'verified')
                <div class="text-center py-6">
                    <div class="text-green-500 text-4xl mb-2">✅</div>
                    <p class="text-green-700 font-medium">Pembayaran Terverifikasi</p>
                    <p class="text-gray-400 text-sm mt-1">{{ $payment->verified_at?->format('d M Y H:i') }}</p>
                </div>
            @else
                <p class="text-gray-400 text-sm">Invoice belum tersedia.</p>
            @endif
        </div>
    </div>
</div>
