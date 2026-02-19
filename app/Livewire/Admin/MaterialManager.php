<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\ConferenceMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class MaterialManager extends Component
{
    use WithFileUploads;

    public $conferenceId;
    public $conferences = [];

    // Form fields
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $description = '';
    public string $type = 'materi';
    public $file;
    public bool $isActive = true;
    public int $sortOrder = 0;

    public function mount()
    {
        $this->conferences = Conference::orderByDesc('start_date')->get();
        $this->conferenceId = Conference::active()->value('id') ?? ($this->conferences->first()?->id);
    }

    public function updatingConferenceId()
    {
        $this->resetForm();
    }

    public function openForm(?int $id = null)
    {
        $this->resetForm();
        if ($id) {
            $m = ConferenceMaterial::findOrFail($id);
            $this->editingId    = $m->id;
            $this->title        = $m->title;
            $this->description  = $m->description ?? '';
            $this->type         = $m->type;
            $this->isActive     = $m->is_active;
            $this->sortOrder    = $m->sort_order;
        }
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->editingId   = null;
        $this->title       = '';
        $this->description = '';
        $this->type        = 'materi';
        $this->file        = null;
        $this->isActive    = true;
        $this->sortOrder   = 0;
        $this->showForm    = false;
    }

    public function save()
    {
        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type'        => 'required|in:materi,ppt,sertifikat,lainnya',
            'isActive'    => 'boolean',
            'sortOrder'   => 'integer|min:0',
        ];

        if (!$this->editingId) {
            $rules['file'] = 'required|file|mimes:pdf,ppt,pptx,doc,docx,xls,xlsx,zip,jpg,jpeg,png|max:20480';
        } else {
            $rules['file'] = 'nullable|file|mimes:pdf,ppt,pptx,doc,docx,xls,xlsx,zip,jpg,jpeg,png|max:20480';
        }

        $this->validate($rules);

        $data = [
            'conference_id' => $this->conferenceId,
            'title'         => $this->title,
            'description'   => $this->description ?: null,
            'type'          => $this->type,
            'is_active'     => $this->isActive,
            'sort_order'    => $this->sortOrder,
            'uploaded_by'   => Auth::id(),
        ];

        if ($this->file) {
            // Remove old file if editing
            if ($this->editingId) {
                $old = ConferenceMaterial::find($this->editingId);
                if ($old && $old->file_path) {
                    Storage::disk('public')->delete($old->file_path);
                }
            }

            $path = $this->file->store('conference-materials', 'public');
            $data['file_path'] = $path;
            $data['file_name'] = $this->file->getClientOriginalName();
            $data['file_size'] = $this->formatSize($this->file->getSize());
        }

        if ($this->editingId) {
            ConferenceMaterial::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Materi berhasil diperbarui.');
        } else {
            ConferenceMaterial::create($data);
            session()->flash('success', 'Materi berhasil ditambahkan.');
        }

        $this->resetForm();
    }

    public function toggleActive(int $id)
    {
        $m = ConferenceMaterial::findOrFail($id);
        $m->update(['is_active' => !$m->is_active]);
    }

    public function delete(int $id)
    {
        $m = ConferenceMaterial::findOrFail($id);
        if ($m->file_path) {
            Storage::disk('public')->delete($m->file_path);
        }
        $m->delete();
        session()->flash('success', 'Materi berhasil dihapus.');
    }

    private function formatSize(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

    public function render()
    {
        $materials = ConferenceMaterial::where('conference_id', $this->conferenceId)
            ->with('uploader')
            ->orderBy('sort_order')
            ->orderBy('type')
            ->get();

        return view('livewire.admin.material-manager', compact('materials'))
            ->layout('layouts.app');
    }
}
