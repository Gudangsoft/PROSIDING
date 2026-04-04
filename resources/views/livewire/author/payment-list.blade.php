<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pembayaran Saya</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola pembayaran registrasi abstrak dan konferensi Anda</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    @if($payments->count())
    <div class="space-y-4">
        @foreach($payments as $payment)
        @php
            $conference = $payment->abstract?->conference ?? $payment->registrationPackage?->conference;
            $title = $payment->abstract?->title ?? 'Registrasi Partisipan';
        @endphp
        <div class="bg-white rounded-xl shadow-sm border p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        @if($payment->type === 'abstract')
                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Abstrak</span>
                        @else
                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-teal-100 text-teal-700">Partisipan</span>
                        @endif
                        
                        @if($payment->status === 'verified')
                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Lunas</span>
                        @elseif($payment->status === 'uploaded')
                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Menunggu Verifikasi</span>
                        @elseif($payment->status === 'rejected')
                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-700">Ditolak</span>
                        @else
                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Belum Bayar</span>
                        @endif

                        <span class="text-xs text-gray-400">{{ $conference?->name ?? '-' }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 text-sm leading-snug">{{ $title }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Invoice: <span class="font-mono">{{ $payment->invoice_number }}</span></p>
                    
                    @if($payment->admin_notes && $payment->status === 'rejected')
                    <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700">
                        <strong>Alasan Ditolak:</strong> {{ $payment->admin_notes }}
                    </div>
                    @endif
                </div>
                
                <div class="text-right shrink-0">
                    <p class="text-lg font-bold text-gray-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    
                    @if($payment->status === 'pending' || $payment->status === 'rejected')
                    <button wire:click="openUploadModal({{ $payment->id }})" class="mt-2 px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition cursor-pointer">
                        Upload Bukti Bayar
                    </button>
                    @elseif($payment->status === 'uploaded')
                    <p class="text-xs text-blue-600 mt-2">Menunggu verifikasi admin...</p>
                    @elseif($payment->status === 'verified')
                    <p class="text-xs text-green-600 mt-2">✓ Pembayaran selesai</p>
                    @endif
                </div>
            </div>

            {{-- Bank Info --}}
            @if(in_array($payment->status, ['pending', 'rejected']) && $conference)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-600 mb-2">Transfer ke:</p>
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-sm">
                    @if($conference->payment_bank_name)
                    <p class="text-gray-700">Bank: <span class="font-semibold">{{ $conference->payment_bank_name }}</span></p>
                    @endif
                    @if($conference->payment_bank_account)
                    <p class="text-gray-700">No. Rekening: <span class="font-mono font-bold text-blue-700">{{ $conference->payment_bank_account }}</span></p>
                    @endif
                    @if($conference->payment_account_holder)
                    <p class="text-gray-700">a.n. <span class="font-semibold">{{ $conference->payment_account_holder }}</span></p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Payment Proof Preview --}}
            @if($payment->payment_proof)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-600 mb-2">Bukti Pembayaran:</p>
                <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 text-sm hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Lihat Bukti
                </a>
                <span class="text-xs text-gray-400 ml-2">via {{ $payment->payment_method ?? '-' }}</span>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <h3 class="text-lg font-semibold text-gray-600 mb-1">Belum ada tagihan</h3>
        <p class="text-sm text-gray-400">Tagihan pembayaran akan muncul setelah abstrak Anda disetujui.</p>
    </div>
    @endif

    {{-- Upload Modal --}}
    @if($showUploadModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Upload Bukti Pembayaran</h3>
            </div>
            <form wire:submit="uploadProof" class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                    <select wire:model="paymentMethod" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="QRIS">QRIS</option>
                        <option value="E-Wallet">E-Wallet (GoPay/OVO/Dana)</option>
                        <option value="Virtual Account">Virtual Account</option>
                    </select>
                    @error('paymentMethod') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-400 transition">
                        <input type="file" wire:model="proofFile" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm">
                        <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, PDF. Maksimal 5MB.</p>
                    </div>
                    @error('proofFile') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    <div wire:loading wire:target="proofFile" class="text-sm text-blue-600 mt-1">Mengunggah...</div>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" wire:click="closeUploadModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition cursor-pointer" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="uploadProof">Upload</span>
                        <span wire:loading wire:target="uploadProof">Mengirim...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
