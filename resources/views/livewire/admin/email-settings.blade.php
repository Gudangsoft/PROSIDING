<div class="p-6 max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Pengaturan Email</h1>
        <p class="text-base text-gray-500 mt-2">Notifikasi email dan test email - Konfigurasi SMTP diatur di file .env</p>
    </div>

    {{-- Info SMTP from .env --}}
    <div class="mb-6 p-5 bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 rounded-r-xl">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <h3 class="font-bold text-gray-800 text-base">Konfigurasi SMTP</h3>
                <p class="text-sm text-gray-700 mt-1">Konfigurasi SMTP dikelola melalui file <strong>.env</strong> di root project. Untuk mengubah pengaturan email:</p>
                <ol class="text-sm text-gray-700 mt-2 ml-4 space-y-1 list-decimal">
                    <li>Edit file <strong>.env</strong></li>
                    <li>Ubah nilai <strong>MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, dll</strong></li>
                    <li>Untuk Gmail, gunakan <strong>App Password</strong> (bukan password biasa)</li>
                    <li>Restart aplikasi setelah mengubah .env</li>
                </ol>
                <div class="mt-3 p-3 bg-white/70 rounded-lg border border-blue-200">
                    <p class="text-xs font-mono text-gray-700">MAIL_HOST={{ config('mail.mailers.smtp.host') ?: '-' }}</p>
                    <p class="text-xs font-mono text-gray-700">MAIL_PORT={{ config('mail.mailers.smtp.port') ?: '-' }}</p>
                    <p class="text-xs font-mono text-gray-700">MAIL_USERNAME={{ config('mail.mailers.smtp.username') ?: '-' }}</p>
                    <p class="text-xs font-mono text-gray-700">MAIL_ENCRYPTION={{ config('mail.mailers.smtp.encryption') ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg flex items-start gap-3">
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg flex items-start gap-3">
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <form wire:submit.prevent="save">
        {{-- Notification Settings --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800">Notifikasi Email Otomatis</h2>
                <p class="text-sm text-gray-500 mt-1">Aktifkan notifikasi yang akan dikirim via email secara otomatis</p>
            </div>

            <div class="space-y-3">
                <div class="flex items-start justify-between p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl hover:border-orange-300 transition group">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-orange-700 transition">Notifikasi Submission Paper Baru</p>
                            <p class="text-xs text-gray-500 mt-0.5">Kirim email ke admin saat paper baru disubmit</p>
                        </div>
                    </div>
                    <label class="relative ml-3 inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.defer="settings.mail_notify_submission" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:ring-4 peer-focus:ring-orange-200 rounded-full peer peer-checked:bg-gradient-to-r peer-checked:from-orange-600 peer-checked:to-orange-700 transition"></div>
                        <div class="absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full shadow-sm transition-transform peer-checked:translate-x-5 pointer-events-none"></div>
                    </label>
                </div>

                <div class="flex items-start justify-between p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl hover:border-orange-300 transition group">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-orange-700 transition">Notifikasi Review Selesai</p>
                            <p class="text-xs text-gray-500 mt-0.5">Kirim email ke author saat review paper selesai</p>
                        </div>
                    </div>
                    <label class="relative ml-3 inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.defer="settings.mail_notify_review" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:ring-4 peer-focus:ring-orange-200 rounded-full peer peer-checked:bg-gradient-to-r peer-checked:from-orange-600 peer-checked:to-orange-700 transition"></div>
                        <div class="absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full shadow-sm transition-transform peer-checked:translate-x-5 pointer-events-none"></div>
                    </label>
                </div>

                <div class="flex items-start justify-between p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl hover:border-orange-300 transition group">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-orange-700 transition">Notifikasi Pembayaran Diverifikasi</p>
                            <p class="text-xs text-gray-500 mt-0.5">Kirim email ke author saat pembayaran terverifikasi</p>
                        </div>
                    </div>
                    <label class="relative ml-3 inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.defer="settings.mail_notify_payment" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:ring-4 peer-focus:ring-orange-200 rounded-full peer peer-checked:bg-gradient-to-r peer-checked:from-orange-600 peer-checked:to-orange-700 transition"></div>
                        <div class="absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full shadow-sm transition-transform peer-checked:translate-x-5 pointer-events-none"></div>
                    </label>
                </div>

                <div class="flex items-start justify-between p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl hover:border-orange-300 transition group">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-orange-700 transition">Notifikasi Perubahan Status Paper</p>
                            <p class="text-xs text-gray-500 mt-0.5">Kirim email ke author saat status paper berubah</p>
                        </div>
                    </div>
                    <label class="relative ml-3 inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.defer="settings.mail_notify_status" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:ring-4 peer-focus:ring-orange-200 rounded-full peer peer-checked:bg-gradient-to-r peer-checked:from-orange-600 peer-checked:to-orange-700 transition"></div>
                        <div class="absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full shadow-sm transition-transform peer-checked:translate-x-5 pointer-events-none"></div>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="button" onclick="window.location.reload()" class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-all duration-200 flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Batal
            </button>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white rounded-xl text-sm font-semibold hover:from-orange-700 hover:to-orange-800 transition-all duration-200 flex items-center gap-2 shadow-lg shadow-orange-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Notifikasi
            </button>
        </div>
    </form>

    {{-- Test Email --}}
    <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl shadow-sm border-2 border-orange-200 p-8 mt-8">
        <div class="flex items-start gap-4 mb-6">
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 rounded-full p-3 shadow-lg shadow-orange-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Kirim Email Percobaan</h2>
                <p class="text-sm text-gray-600 mt-1">Pastikan konfigurasi SMTP sudah benar dengan mengirim email percobaan</p>
            </div>
        </div>

        @if(session('test_success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="text-sm font-medium">{{ session('test_success') }}</span>
        </div>
        @endif
        @if(session('test_error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <span class="text-sm font-medium">{{ session('test_error') }}</span>
        </div>
        @endif

        <div class="flex gap-3">
            <input type="email" wire:model="testEmail" class="flex-1 px-4 py-3 border-2 border-orange-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition bg-white shadow-sm" placeholder="Masukkan alamat email tujuan percobaan...">
            <button wire:click="sendTestEmail" class="px-6 py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white rounded-xl text-sm font-semibold hover:from-orange-700 hover:to-orange-800 transition-all duration-200 flex items-center gap-2 shadow-lg shadow-orange-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Kirim Test Email
            </button>
        </div>
        @error('testEmail') <p class="text-red-600 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
    </div>
</div>
