<div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Database Manager</h1>
            <p class="text-sm text-gray-500 mt-1">Export & import database — backup atau restore data aplikasi</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm font-medium border border-blue-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582 4-8-4s8 1.79 8 4"/></svg>
            {{ $dbName }}
        </span>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-5 flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-4 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 18h18M3 6h18"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Jumlah Tabel</p>
                <p class="text-xl font-bold text-gray-800">{{ $tables->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Ukuran DB</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalSize }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border p-4 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Database</p>
                <p class="text-base font-bold text-gray-800 truncate max-w-[120px]">{{ $dbName }}</p>
            </div>
        </div>
    </div>

    {{-- Export & Import Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

        {{-- Export Card --}}
        <div class="bg-white rounded-xl border shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-800">Export Database</h2>
                    <p class="text-xs text-gray-500">Unduh backup penuh sebagai file SQL</p>
                </div>
            </div>
            <div class="bg-green-50 border border-green-100 rounded-lg p-3 text-xs text-green-700 mb-4">
                Export akan menghasilkan file <strong>.sql</strong> berisi struktur semua tabel beserta seluruh datanya. Simpan file ini sebagai backup rutin.
            </div>
            <a href="{{ route('admin.database.export') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium shadow-sm transition w-full justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download Backup SQL
            </a>
        </div>

        {{-- Import Card --}}
        <div class="bg-white rounded-xl border shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-orange-100 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-800">Import Database</h2>
                    <p class="text-xs text-gray-500">Restore dari file SQL (.sql / .txt)</p>
                </div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-700 mb-4">
                <strong>⚠ Perhatian:</strong> Import akan menjalankan semua perintah di file SQL. Pastikan file SQL valid dan Anda sudah backup data sebelum import.
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih File SQL</label>
                    <input wire:model="importFile" type="file" accept=".sql,.txt"
                           class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:text-sm file:font-medium hover:file:bg-blue-700 cursor-pointer border border-gray-300 rounded-lg">
                    @error('importFile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Maksimum 50 MB</p>
                </div>
                <button type="button" wire:click="openImportConfirm"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 text-sm font-medium shadow-sm transition w-full justify-center disabled:opacity-60">
                    <span wire:loading.remove wire:target="importFile,openImportConfirm">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12"/></svg>
                        Import Database
                    </span>
                    <span wire:loading wire:target="importFile,openImportConfirm">Memuat...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Tables List --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-800">Daftar Tabel ({{ $tables->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500 w-8">#</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Nama Tabel</th>
                        <th class="text-right px-4 py-3 font-medium text-gray-500">Rows</th>
                        <th class="text-right px-4 py-3 font-medium text-gray-500">Ukuran</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Engine</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Update Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($tables as $i => $table)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2.5 text-gray-400 text-xs">{{ $i + 1 }}</td>
                        <td class="px-4 py-2.5">
                            <code class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-mono">{{ $table['name'] }}</code>
                        </td>
                        <td class="px-4 py-2.5 text-right text-gray-600 font-mono text-xs">{{ $table['rows'] }}</td>
                        <td class="px-4 py-2.5 text-right text-gray-500 text-xs">{{ $table['size'] }}</td>
                        <td class="px-4 py-2.5 text-center">
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $table['engine'] }}</span>
                        </td>
                        <td class="px-4 py-2.5 text-center text-xs text-gray-400">
                            {{ $table['updated'] ? \Carbon\Carbon::parse($table['updated'])->format('d/m/Y H:i') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Import Confirm Modal --}}
    @if($showImportConfirm)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-200">
                <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Konfirmasi Import</h3>
            </div>
            <div class="p-5 space-y-3">
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <p class="text-sm text-red-700 font-semibold mb-1">⚠ Aksi ini tidak dapat dibatalkan!</p>
                    <p class="text-sm text-red-600">Semua perintah SQL di file yang diupload akan dieksekusi langsung ke database. Data yang bertentangan bisa tertimpa atau menyebabkan error.</p>
                </div>
                <p class="text-sm text-gray-600">Pastikan Anda sudah:</p>
                <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                    <li>Memiliki backup terbaru</li>
                    <li>Memastikan file SQL valid</li>
                    <li>Benar-benar ingin melanjutkan</li>
                </ul>
            </div>
            <div class="flex justify-end gap-2 px-5 py-4 border-t border-gray-200 bg-gray-50">
                <button type="button" wire:click="cancelImport"
                        class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                    Batal
                </button>
                <button type="button" wire:click="runImport"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 text-sm font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition cursor-pointer disabled:opacity-60 inline-flex items-center gap-2">
                    <span wire:loading.remove wire:target="runImport">Ya, Jalankan Import</span>
                    <span wire:loading wire:target="runImport">
                        <svg class="animate-spin w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Mengimport...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
