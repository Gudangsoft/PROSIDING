<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Bukti Pembayaran</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola bukti pembayaran registrasi partisipan Anda</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-4 mb-4 text-sm flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    @if(!$payment || $payment->status !== 'verified')
    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6 flex items-start gap-4">
        <div class="shrink-0 bg-orange-100 rounded-full p-2">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        </div>
        <div>
            <p class="font-semibold text-orange-800">Akun Anda Belum Aktif</p>
            @if(!$payment || $payment->status === 'pending')
                <p class="text-sm text-orange-700 mt-1">Silakan upload bukti pembayaran di bawah ini. Setelah diverifikasi admin, akun Anda akan aktif penuh.</p>
            @elseif($payment->status === 'uploaded')
                <p class="text-sm text-orange-700 mt-1">Bukti pembayaran Anda sedang dalam proses verifikasi. Anda akan mendapat notifikasi email saat akun aktif.</p>
            @elseif($payment->status === 'rejected')
                <p class="text-sm text-orange-700 mt-1">Bukti pembayaran Anda <strong>ditolak</strong>. Silakan upload ulang bukti yang valid.</p>
            @endif
        </div>
    </div>
    @endif

    {{-- Payment Status Card --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="font-semibold text-gray-800">Status Pembayaran</h2>
        </div>
        <div class="p-6">
            @if($payment)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</p>
                        <p class="font-mono text-sm text-gray-800 mt-1">{{ $payment->invoice_number }}</p>
                    </div>
                    @if($payment->registrationPackage)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Paket Registrasi</p>
                        <p class="text-base font-semibold text-teal-600 mt-1">{{ $payment->registrationPackage->name }}</p>
                        @if($payment->registrationPackage->description)
                            <p class="text-sm text-gray-600 mt-0.5">{{ $payment->registrationPackage->description }}</p>
                        @endif
                    </div>
                    @endif
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</p>
                        <p class="text-lg font-bold text-gray-800 mt-1">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                    @if($payment->payment_method)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pembayaran</p>
                        <p class="text-sm text-gray-700 mt-1">{{ $payment->payment_method }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</p>
                        <div class="mt-1">
                            @if($payment->status === 'verified')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Lunas — Terverifikasi
                                </span>
                            @elseif($payment->status === 'uploaded')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Menunggu Verifikasi Admin
                                </span>
                            @elseif($payment->status === 'rejected')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                    Ditolak
                                </span>
                                @if($payment->admin_notes)
                                    <p class="text-sm text-red-600 mt-2 bg-red-50 border border-red-100 rounded-lg p-3">
                                        <strong>Alasan:</strong> {{ $payment->admin_notes }}
                                    </p>
                                @endif
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                    Pending — Belum Upload
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($payment->paid_at)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</p>
                        <p class="text-sm text-gray-700 mt-1">{{ $payment->paid_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                    @if($payment->verified_at)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Verifikasi</p>
                        <p class="text-sm text-gray-700 mt-1">{{ $payment->verified_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>

                {{-- Proof Preview --}}
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Bukti Pembayaran</p>
                    @if($payment->payment_proof)
                        @php
                            $ext = pathinfo($payment->payment_proof, PATHINFO_EXTENSION);
                        @endphp
                        @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                            <img src="{{ asset('storage/'.$payment->payment_proof) }}" alt="Bukti Pembayaran" class="rounded-lg border max-h-80 w-full object-contain bg-gray-50">
                        @else
                            <div class="bg-gray-50 border rounded-lg p-6 text-center">
                                <svg class="w-12 h-12 text-red-500 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                <p class="text-sm text-gray-600">File PDF</p>
                                <a href="{{ asset('storage/'.$payment->payment_proof) }}" target="_blank" class="text-sm text-blue-600 hover:underline mt-1 inline-block">Buka File →</a>
                            </div>
                        @endif
                    @else
                        <div class="bg-gray-50 border rounded-lg p-8 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm text-gray-400">Belum ada bukti pembayaran</p>
                        </div>
                    @endif
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <p class="text-gray-500">Belum ada data pembayaran.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Reupload Form (show if rejected or pending or no payment) --}}
    @if(!$payment || $payment->status === 'rejected' || $payment->status === 'pending')
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="font-semibold text-gray-800">
                @if($payment && $payment->status === 'rejected')
                    Upload Ulang Bukti Pembayaran
                @elseif($payment)
                    Upload Bukti Pembayaran
                @else
                    Upload Bukti Pembayaran
                @endif
            </h2>
        </div>
        <div class="p-6">
            @if($payment && $payment->status === 'rejected')
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                <p class="text-sm text-red-700">Bukti pembayaran Anda ditolak. Silakan upload ulang dengan bukti yang benar.</p>
            </div>
            @endif

            <form wire:submit="reupload" class="space-y-5">
                {{-- Package Selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Paket Registrasi <span class="text-red-500">*</span></label>
                    <select wire:model.live="selectedPackageId" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500">
                        <option value="">-- Pilih Paket --</option>
                        @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}">
                                {{ $pkg->name }} — {{ $pkg->is_free ? 'GRATIS' : 'Rp ' . number_format($pkg->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('selectedPackageId') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Package Details (show when package selected) --}}
                @if($selectedPackage)
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $selectedPackage->name }}</h3>
                            @if($selectedPackage->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $selectedPackage->description }}</p>
                            @endif
                            
                            @if($selectedPackage->features && count($selectedPackage->features) > 0)
                            <div class="mt-3">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Benefit Paket:</p>
                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-1.5 text-sm text-gray-700">
                                    @foreach($selectedPackage->features as $feature)
                                        <li class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-green-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="mt-3 pt-3 border-t border-blue-200">
                                <p class="text-xs text-gray-500">Harga Paket:</p>
                                @if($selectedPackage->is_free)
                                    <span class="inline-flex items-center gap-1.5 mt-1 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-lg font-bold">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        GRATIS
                                    </span>
                                @else
                                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($selectedPackage->price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Methods and Amount (hidden for free packages) --}}
                @if(!$selectedPackage->is_free)

                {{-- Payment Methods (show if available) --}}
                @if(count($paymentMethods) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Metode Pembayaran <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        @foreach($paymentMethods as $index => $method)
                            @if($method['is_active'] ?? true)
                            <label class="flex items-start gap-3 border rounded-lg p-3 cursor-pointer hover:bg-gray-50 transition {{ $selectedPaymentMethodIndex == $index ? 'border-teal-500 bg-teal-50' : 'border-gray-300' }}">
                                <input type="radio" wire:model.live="selectedPaymentMethodIndex" value="{{ $index }}" 
                                    class="mt-1 text-teal-600 focus:ring-teal-500">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                                            {{ $method['type'] }}
                                        </span>
                                        <p class="font-semibold text-gray-800">{{ $method['name'] }}</p>
                                    </div>
                                    
                                    @if(isset($method['account_number']) && $method['account_number'])
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="font-mono">{{ $method['account_number'] }}</span>
                                        @if(isset($method['account_holder']) && $method['account_holder'])
                                            <span class="text-gray-400">a.n.</span> {{ $method['account_holder'] }}
                                        @endif
                                    </p>
                                    @endif
                                    
                                    <p class="text-lg font-bold text-teal-600 mt-1">
                                        Rp {{ number_format($method['amount'] ?? $selectedPackage->price, 0, ',', '.') }}
                                    </p>
                                    
                                    @if(isset($method['instructions']) && $method['instructions'])
                                    <p class="text-xs text-gray-500 mt-1 border-t pt-2">
                                        {{ $method['instructions'] }}
                                    </p>
                                    @endif
                                </div>
                            </label>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Final Amount Display --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-600">Total Pembayaran:</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($finalAmount, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endif {{-- end !is_free --}}
                @endif {{-- end selectedPackage --}}

                {{-- Free Package: Confirm without proof --}}
                @if($selectedPackage && $selectedPackage->is_free)
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 text-center">
                    <svg class="w-10 h-10 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-emerald-800 font-semibold">Paket ini <strong>GRATIS</strong> — tidak diperlukan bukti pembayaran.</p>
                    <p class="text-emerald-700 text-sm mt-1">Klik tombol di bawah untuk langsung mengaktifkan akun Anda.</p>
                </div>
                <button type="button" wire:click="registerFree" wire:loading.attr="disabled"
                    class="w-full px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition text-sm disabled:opacity-50">
                    <span wire:loading.remove wire:target="registerFree">Konfirmasi Pendaftaran Gratis</span>
                    <span wire:loading wire:target="registerFree">Memproses...</span>
                </button>
                @else

                {{-- Paid Package: File Upload --}}
                @if(!$selectedPackage || !$selectedPackage->is_free)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">File Bukti Pembayaran <span class="text-red-500">*</span></label>
                    <input type="file" wire:model="newProof" accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm file:mr-3 file:py-1 file:px-3 file:border-0 file:bg-teal-100 file:text-teal-700 file:font-medium file:rounded file:cursor-pointer">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, atau PDF. Maks 5MB.</p>
                    @error('newProof') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div wire:loading wire:target="newProof" class="text-sm text-teal-600">Mengupload file...</div>
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition text-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="reupload">Upload Bukti Pembayaran</span>
                    <span wire:loading wire:target="reupload">Menyimpan...</span>
                </button>
                @endif
                @endif
            </form>
        </div>
    </div>
    @endif
</div>
