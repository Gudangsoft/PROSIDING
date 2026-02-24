<div>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">OJS / DOI Integration</h2>
            <p class="text-sm text-gray-500">Submit paper ke Open Journal Systems dan generate DOI otomatis</p>
        </div>

        {{-- Tabs --}}
        <div class="flex gap-1 border-b border-gray-200 mb-6">
            <button wire:click="$set('activeTab', 'settings')" class="px-5 py-2 text-sm font-semibold rounded-t-lg {{ $activeTab === 'settings' ? 'bg-white border border-b-white border-gray-200 text-purple-700 -mb-px' : 'text-gray-500 hover:text-gray-700' }}">⚙️ Pengaturan</button>
            <button wire:click="$set('activeTab', 'papers')" class="px-5 py-2 text-sm font-semibold rounded-t-lg {{ $activeTab === 'papers' ? 'bg-white border border-b-white border-gray-200 text-purple-700 -mb-px' : 'text-gray-500 hover:text-gray-700' }}">📄 Paper</button>
        </div>

        @if(session('success')) <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-xl text-sm">✅ {{ session('success') }}</div> @endif
        @if(session('error') || $error) <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-xl text-sm">⚠️ {{ session('error') ?: $error }}</div> @endif
        @if($connectionStatus) <div class="mb-4 bg-blue-50 border border-blue-300 text-blue-700 px-4 py-3 rounded-xl text-sm">🔗 {{ $connectionStatus }}</div> @endif

        @if($activeTab === 'settings')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Konferensi</label>
                <select wire:model.live="conferenceId" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                    <option value="0">— Pilih —</option>
                    @foreach($conferences as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
            @if($conferenceId)
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-4">
                    <h3 class="text-base font-bold text-gray-800 mb-2">Koneksi OJS</h3>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">URL OJS <span class="text-gray-400 font-normal">(https://journal.example.com)</span></label>
                        <input wire:model="ojsUrl" type="url" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm" placeholder="https://ojs.example.com">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">API Key</label>
                        <input wire:model="ojsApiKey" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm" placeholder="Bearer token dari OJS">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Journal ID</label>
                        <input wire:model="ojsJournalId" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm" placeholder="1">
                    </div>
                    <hr>
                    <h3 class="text-base font-bold text-gray-800">DOI</h3>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">DOI Prefix</label>
                        <input wire:model="doiPrefix" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm" placeholder="10.12345/conference">
                        <p class="text-xs text-gray-400 mt-1">Format hasil: <code>10.12345/conference/2024.000001</code></p>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button wire:click="saveSettings" class="px-5 py-2 bg-purple-600 text-white rounded-xl text-sm font-semibold hover:bg-purple-700">💾 Simpan</button>
                        <button wire:click="testConnection" wire:loading.attr="disabled" class="px-5 py-2 bg-blue-100 text-blue-700 rounded-xl text-sm font-semibold hover:bg-blue-200 disabled:opacity-50">
                            <span wire:loading.remove wire:target="testConnection">🔗 Test Koneksi</span>
                            <span wire:loading wire:target="testConnection">⏳ Menguji...</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        @else {{-- papers tab --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Konferensi</label>
                <select wire:model.live="conferenceId" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm">
                    <option value="0">— Semua —</option>
                    @foreach($conferences as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                </select>
            </div>
        </div>

        @if($papers->isEmpty())
        <div class="text-center py-16 text-gray-400">Tidak ada paper yang ditemukan.</div>
        @else
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="grid grid-cols-[1fr,160px,150px,120px,120px] gap-3 px-5 py-3 bg-gray-50 text-xs font-semibold text-gray-500 uppercase border-b">
                <div>Judul</div><div>DOI</div><div>OJS ID</div><div>OJS Status</div><div>Aksi</div>
            </div>
            @foreach($papers as $paper)
            <div class="grid grid-cols-[1fr,160px,150px,120px,120px] gap-3 px-5 py-3 text-sm border-b last:border-0 items-center hover:bg-gray-50">
                <div>
                    <p class="font-medium text-gray-800 truncate">{{ $paper->title }}</p>
                    <p class="text-xs text-gray-400">{{ $paper->conference->name ?? '—' }}</p>
                </div>
                <div>
                    @if($paper->doi)
                    <span class="text-xs font-mono text-green-700 bg-green-50 px-2 py-0.5 rounded">{{ $paper->doi }}</span>
                    @else
                    <span class="text-xs text-gray-300">-</span>
                    @endif
                </div>
                <div>
                    @if($paper->ojs_submission_id)
                    <span class="font-mono text-xs text-blue-700">{{ $paper->ojs_submission_id }}</span>
                    @else
                    <span class="text-xs text-gray-300">-</span>
                    @endif
                </div>
                <div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold
                        {{ $paper->ojs_status === 'submitted' ? 'bg-blue-100 text-blue-700' : ($paper->ojs_status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ $paper->ojs_status ?? 'Belum' }}
                    </span>
                </div>
                <div class="flex gap-1">
                    @if(!$paper->doi)
                    <button wire:click="generateDoi({{ $paper->id }})" wire:loading.attr="disabled" class="px-2 py-1 bg-green-50 text-green-700 rounded-lg text-xs hover:bg-green-100">DOI</button>
                    @endif
                    @if(!$paper->ojs_submission_id)
                    <button wire:click="submitToOjs({{ $paper->id }})" wire:loading.attr="disabled" class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100">
                        <span wire:loading.remove wire:target="submitToOjs({{ $paper->id }})">OJS</span>
                        <span wire:loading wire:target="submitToOjs({{ $paper->id }})">...</span>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $papers->links() }}</div>
        @endif
        @endif
    </div>
</div>
