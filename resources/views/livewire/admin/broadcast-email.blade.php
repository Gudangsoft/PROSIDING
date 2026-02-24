<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Broadcast Email</h2>
            <button wire:click="openCompose" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Buat Email Baru
            </button>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        {{-- Compose Modal --}}
        @if($showCompose)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Buat Broadcast Email</h3>
                    <button wire:click="$set('showCompose', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
                <div class="p-5 overflow-y-auto space-y-4 flex-1">
                    {{-- Filter --}}
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <p class="text-xs font-semibold text-blue-700 mb-3 uppercase tracking-wider">Filter Penerima</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Role</label>
                                <select wire:model.live="filterRole" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-sm">
                                    <option value="">Semua Role</option>
                                    <option value="author">Author</option>
                                    <option value="participant">Peserta</option>
                                    <option value="reviewer">Reviewer</option>
                                    <option value="editor">Editor</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Konferensi</label>
                                <select wire:model.live="filterConf" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-sm">
                                    <option value="">Semua</option>
                                    @foreach($conferences as $conf)
                                    <option value="{{ $conf->id }}">{{ $conf->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Status Paper</label>
                                <select wire:model.live="filterPaperStatus" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-sm">
                                    <option value="">Semua</option>
                                    <option value="submitted">Submitted</option>
                                    <option value="accepted">Accepted</option>
                                    <option value="payment_pending">Pending Payment</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-sm font-semibold text-blue-700">{{ $previewCount }}</span>
                            <span class="text-xs text-blue-600">penerima ditemukan</span>
                        </div>
                    </div>
                    {{-- Subject & Body --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Subjek Email <span class="text-red-500">*</span></label>
                        <input wire:model="subject" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm" placeholder="Informasi Penting dari Panitia...">
                        @error('subject')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Isi Pesan <span class="text-red-500">*</span></label>
                        <textarea wire:model="body" rows="8" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm resize-none" placeholder="Tulis isi email di sini..."></textarea>
                        @error('body')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="p-5 border-t border-gray-200 flex justify-between items-center">
                    <p class="text-xs text-gray-500">Email akan dikirim ke <strong class="text-gray-700">{{ $previewCount }}</strong> penerima.</p>
                    <div class="flex gap-3">
                        <button wire:click="$set('showCompose', false)" class="px-4 py-2 border border-gray-300 rounded-xl text-sm">Batal</button>
                        <button wire:click="send" wire:confirm="Yakin kirim email ke {{ $previewCount }} penerima?" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                            <span wire:loading.remove>Kirim Sekarang</span>
                            <span wire:loading>Mengirim...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- History --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-700">Riwayat Broadcast</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Subjek</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Dikirim oleh</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Penerima</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden lg:table-cell">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($history as $email)
                    @php $sc=['sent'=>'green','sending'=>'yellow','failed'=>'red','draft'=>'gray'][$email->status] ?? 'gray'; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $email->subject }}</td>
                        <td class="px-4 py-3 hidden md:table-cell text-gray-600">{{ $email->sender?->name }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-gray-800">{{ $email->recipient_count }}</td>
                        <td class="px-4 py-3 text-center"><span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-{{ $sc }}-100 text-{{ $sc }}-700">{{ ucfirst($email->status) }}</span></td>
                        <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-500">{{ $email->sent_at?->diffForHumans() ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-10 text-gray-400">Belum ada riwayat broadcast.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($history->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">{{ $history->links() }}</div>
            @endif
        </div>
    </div>
</div>
