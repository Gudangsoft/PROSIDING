<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Verifikasi Pembayaran</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola pembayaran penulis — cek bukti bayar, lalu ubah status: Lunas, Pending, atau Ditolak</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        {{-- Type Tabs --}}
        <div class="flex border border-gray-200 rounded-lg overflow-hidden text-sm">
            <button wire:click="$set('typeFilter', '')" class="px-4 py-2 font-medium transition {{ $typeFilter === '' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} cursor-pointer">
                Semua ({{ $allCount }})
            </button>
            <button wire:click="$set('typeFilter', 'paper')" class="px-4 py-2 font-medium transition border-l border-gray-200 {{ $typeFilter === 'paper' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} cursor-pointer">
                Paper ({{ $paperCount }})
            </button>
            <button wire:click="$set('typeFilter', 'participant')" class="px-4 py-2 font-medium transition border-l border-gray-200 {{ $typeFilter === 'participant' ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} cursor-pointer">
                Partisipan ({{ $participantCount }})
            </button>
        </div>
        <select wire:model.live="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="uploaded">Sudah Upload</option>
            <option value="verified">Lunas</option>
            <option value="rejected">Ditolak</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Invoice</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Tipe</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Paper / Keterangan</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">User</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Jumlah</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Bukti</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $payment->invoice_number }}</td>
                    <td class="px-4 py-3">
                        @if($payment->type === 'participant')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">Partisipan</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Paper</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 max-w-[200px] truncate">
                        @if($payment->type === 'participant')
                            <span class="text-gray-600">Registrasi Partisipan</span>
                            @if($payment->user && $payment->user->institution)
                                <p class="text-xs text-gray-400 truncate">{{ $payment->user->institution }}</p>
                            @endif
                        @else
                            {{ $payment->paper->title ?? '-' }}
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $payment->user->name ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            @if($payment->status==='verified') bg-green-100 text-green-800
                            @elseif($payment->status==='uploaded') bg-blue-100 text-blue-800
                            @elseif($payment->status==='rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            @if($payment->status==='verified') Lunas
                            @elseif($payment->status==='uploaded') Sudah Upload
                            @elseif($payment->status==='rejected') Ditolak
                            @else Pending
                            @endif
                        </span>
                        @if($payment->admin_notes)
                            <p class="text-xs text-red-500 mt-1 max-w-[150px] truncate" title="{{ $payment->admin_notes }}">{{ $payment->admin_notes }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($payment->payment_proof)
                            <a href="{{ asset('storage/'.$payment->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 text-sm hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat
                            </a>
                        @else
                            <span class="text-gray-400 text-xs italic">Belum upload</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1 flex-wrap">
                            @if($payment->status === 'pending')
                                <button type="button" wire:click="sendReminder({{ $payment->id }})" wire:confirm="Kirim email pengingat ke {{ $payment->user->name ?? 'user' }}?"
                                    class="px-2 py-1 text-xs font-medium bg-orange-500 text-white rounded hover:bg-orange-600 cursor-pointer">
                                    📧 Reminder
                                </button>
                            @elseif($payment->status === 'uploaded')
                                <button type="button" wire:click="quickVerify({{ $payment->id }})" wire:confirm="Tandai pembayaran ini sebagai LUNAS?"
                                    class="px-2 py-1 text-xs font-medium bg-green-600 text-white rounded hover:bg-green-700 cursor-pointer">
                                    ✓ Lunas
                                </button>
                                <button type="button" wire:click="openRejectModal({{ $payment->id }})"
                                    class="px-2 py-1 text-xs font-medium bg-red-600 text-white rounded hover:bg-red-700 cursor-pointer">
                                    ✗ Tolak
                                </button>
                            @elseif($payment->status === 'verified')
                                <button type="button" wire:click="resetToPending({{ $payment->id }})" wire:confirm="Reset pembayaran ini ke PENDING?"
                                    class="px-2 py-1 text-xs font-medium bg-yellow-500 text-white rounded hover:bg-yellow-600 cursor-pointer">
                                    ↺ Pending
                                </button>
                            @elseif($payment->status === 'rejected')
                                <button type="button" wire:click="resetToPending({{ $payment->id }})" wire:confirm="Reset pembayaran ini ke PENDING?"
                                    class="px-2 py-1 text-xs font-medium bg-yellow-500 text-white rounded hover:bg-yellow-600 cursor-pointer">
                                    ↺ Pending
                                </button>
                                <button type="button" wire:click="sendReminder({{ $payment->id }})" wire:confirm="Kirim email pengingat ke {{ $payment->user->name ?? 'user' }}?"
                                    class="px-2 py-1 text-xs font-medium bg-orange-500 text-white rounded hover:bg-orange-600 cursor-pointer">
                                    📧 Reminder
                                </button>
                            @endif
                            @if($payment->type === 'paper' && $payment->paper)
                                <a href="{{ route('admin.paper.detail', $payment->paper) }}" class="px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 border border-blue-200 rounded hover:bg-blue-50">Detail</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400">Belum ada data pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $payments->links() }}</div>

    {{-- Reject Modal --}}
    @if($showRejectModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Tolak Pembayaran</h3>
                <button wire:click="closeRejectModal" type="button" class="text-gray-400 hover:text-gray-600 text-xl cursor-pointer">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <p class="text-sm text-red-700">Pembayaran akan ditolak dan pengguna akan diminta upload ulang bukti bayar.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                    <textarea wire:model="adminNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-red-500 focus:border-red-500" placeholder="Contoh: Bukti bayar tidak jelas, nominal tidak sesuai..."></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                <button wire:click="rejectPayment" type="button" class="px-4 py-2 text-sm font-medium bg-red-600 text-white rounded hover:bg-red-700 cursor-pointer">
                    <span wire:loading.remove wire:target="rejectPayment">Tolak Pembayaran</span>
                    <span wire:loading wire:target="rejectPayment">Processing...</span>
                </button>
                <button wire:click="closeRejectModal" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 cursor-pointer">Batal</button>
            </div>
        </div>
    </div>
    @endif
</div>
