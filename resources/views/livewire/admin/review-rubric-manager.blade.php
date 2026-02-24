<div>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Rubrik Review</h2>
                <p class="text-sm text-gray-500">Template penilaian reviewer dengan bobot per kriteria</p>
            </div>
            <button wire:click="openCreateRubric" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">+ Buat Rubrik</button>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">✅ {{ session('success') }}</div>
        @endif

        {{-- Conference filter --}}
        <div class="mb-5">
            <select wire:model.live="conferenceId" class="px-3 py-2 border border-gray-300 rounded-xl text-sm max-w-xs">
                <option value="0">— Pilih Konferensi —</option>
                @foreach($conferences as $c)
                <option value="{{ $c->id }}">{{ $c->name }}{{ $c->blind_review ? ' 🕶 Blind' : '' }}</option>
                @endforeach
            </select>
        </div>

        {{-- Rubrics list --}}
        @if($rubrics->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p class="text-5xl mb-3">📋</p>
            <p>Belum ada rubrik. Pilih konferensi dan buat rubrik penilaian.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($rubrics as $r)
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-4 border-b border-gray-100 flex justify-between items-start gap-2">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $r->name }}</h3>
                        @if($r->description)<p class="text-xs text-gray-500 mt-1">{{ $r->description }}</p>@endif
                    </div>
                    <button wire:click="toggleRubricActive({{ $r->id }})">
                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $r->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $r->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </button>
                </div>
                <div class="p-4 space-y-1">
                    <p class="text-xs text-gray-500">{{ $r->criteria->count() }} kriteria &bull; Skor max: {{ $r->total_max_score }} &bull; Passing: {{ $r->passing_score }}</p>
                    @foreach($r->criteria->take(4) as $c)
                    <div class="flex items-center justify-between text-xs py-1 border-b border-gray-50">
                        <span class="text-gray-700">{{ $c->label }}</span>
                        <span class="text-gray-400">bobot x{{ $c->weight }} | max {{ $c->max_score }}</span>
                    </div>
                    @endforeach
                    @if($r->criteria->count() > 4)
                    <p class="text-xs text-gray-400">+ {{ $r->criteria->count() - 4 }} kriteria lainnya</p>
                    @endif
                </div>
                <div class="p-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                    <button wire:click="openCriteria({{ $r->id }})" class="flex-1 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-medium hover:bg-indigo-100 transition">Kelola Kriteria</button>
                    <button wire:click="openEditRubric({{ $r->id }})" class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">Edit</button>
                    <button wire:click="deleteRubric({{ $r->id }})" wire:confirm="Hapus rubrik ini?" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100 transition">Hapus</button>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- MODAL: Rubric Form --}}
    @if($showRubricModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
            <div class="flex items-center justify-between p-5 border-b">
                <h3 class="text-lg font-bold">{{ $editRubricId ? 'Edit' : 'Buat' }} Rubrik</h3>
                <button wire:click="$set('showRubricModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                @if(!$editRubricId)
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Konferensi</label>
                    <select wire:model="conferenceId" class="w-full px-3 py-2 border rounded-xl text-sm">
                        <option value="0">— Pilih —</option>
                        @foreach($conferences as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select>
                    @error('conferenceId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endif
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Rubrik</label>
                    <input wire:model="rubricName" type="text" placeholder="e.g. Rubrik Review 2025" class="w-full px-3 py-2 border rounded-xl text-sm">
                    @error('rubricName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi (opsional)</label>
                    <textarea wire:model="rubricDesc" rows="2" class="w-full px-3 py-2 border rounded-xl text-sm resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Skor Lulus Minimum (0-100)</label>
                    <input wire:model="passingScore" type="number" min="0" max="100" class="w-full px-3 py-2 border rounded-xl text-sm">
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input wire:model="rubricActive" type="checkbox" class="w-4 h-4 text-indigo-600 rounded">
                    Jadikan aktif untuk konferensi ini
                </label>
            </div>
            <div class="flex justify-end gap-3 p-5 border-t">
                <button wire:click="$set('showRubricModal', false)" class="px-4 py-2 border rounded-xl text-sm">Batal</button>
                <button wire:click="saveRubric" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">Simpan</button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: Criteria Manager --}}
    @if($showCriteriaModal && $managingRubric)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base font-bold text-gray-800">Kriteria: {{ $managingRubric->name }}</h3>
                    <p class="text-xs text-gray-500">Total skor max: {{ $managingRubric->total_max_score }}</p>
                </div>
                <button wire:click="closeCriteriaModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-5">
                @if(session('success2'))
                <div class="mb-3 bg-green-50 border border-green-200 text-green-700 px-3 py-2 rounded-lg text-sm">{{ session('success2') }}</div>
                @endif

                {{-- Existing criteria --}}
                @forelse($managingRubric->criteria as $c)
                <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl mb-2 {{ $editCriterionId === $c->id ? 'bg-indigo-50 border-indigo-300' : 'bg-gray-50' }}">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $c->label }}</p>
                        <p class="text-xs text-gray-500">Bobot: {{ $c->weight }} | Max: {{ $c->max_score }}</p>
                    </div>
                    <button wire:click="openEditCriterion({{ $c->id }})" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg">Edit</button>
                    <button wire:click="deleteCriterion({{ $c->id }})" wire:confirm="Hapus kriteria ini?" class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-lg">Hapus</button>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Belum ada kriteria.</p>
                @endforelse

                {{-- Add/Edit criterion form --}}
                <div class="mt-4 p-4 bg-indigo-50 rounded-xl border border-indigo-200">
                    <h4 class="text-sm font-semibold text-indigo-800 mb-3">{{ $editCriterionId ? 'Edit Kriteria' : 'Tambah Kriteria Baru' }}</h4>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div class="col-span-2">
                            <input wire:model="criterionLabel" type="text" placeholder="Label kriteria (e.g. Orisinalitas)" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            @error('criterionLabel') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bobot (1-10)</label>
                            <input wire:model="criterionWeight" type="number" min="1" max="10" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Skor Max</label>
                            <input wire:model="criterionMaxScore" type="number" min="1" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div class="col-span-2">
                            <textarea wire:model="criterionDesc" rows="2" placeholder="Deskripsi/panduan penilaian (opsional)" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm resize-none"></textarea>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="saveCriterion" class="px-4 py-1.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700">
                            {{ $editCriterionId ? 'Update' : 'Tambah' }}
                        </button>
                        @if($editCriterionId)
                        <button wire:click="$set('editCriterionId', null)" class="px-4 py-1.5 border border-gray-300 rounded-lg text-sm">Batal</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
