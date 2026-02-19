<?php

namespace App\Livewire\Admin;

use App\Models\Supporter;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class SupporterManager extends Component
{
    use WithFileUploads, WithPagination;

    public ?int $editingId = null;
    public string $name = '';
    public string $url = '';
    public string $type = 'supporter';
    public int $sort_order = 0;
    public bool $is_active = true;
    public $logo;
    public ?string $existingLogo = null;

    public bool $showForm = false;
    public string $filterType = '';

    protected function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'url'        => 'nullable|url|max:500',
            'type'       => 'required|in:supporter,sponsor,partner,organizer',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
            'logo'       => $this->editingId ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
        ];
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->sort_order = Supporter::max('sort_order') + 1;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $supporter = Supporter::findOrFail($id);
        $this->editingId    = $supporter->id;
        $this->name         = $supporter->name;
        $this->url          = $supporter->url ?? '';
        $this->type         = $supporter->type;
        $this->sort_order   = $supporter->sort_order;
        $this->is_active    = $supporter->is_active;
        $this->existingLogo = $supporter->logo;
        $this->logo         = null;
        $this->showForm     = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'       => $this->name,
            'url'        => $this->url ?: null,
            'type'       => $this->type,
            'sort_order' => $this->sort_order,
            'is_active'  => $this->is_active,
        ];

        if ($this->logo) {
            $data['logo'] = $this->logo->store('supporters', 'public');
        }

        if ($this->editingId) {
            $supporter = Supporter::findOrFail($this->editingId);
            if ($this->logo && $supporter->logo) {
                \Storage::disk('public')->delete($supporter->logo);
            }
            $supporter->update($data);
            session()->flash('success', 'Supporter berhasil diperbarui.');
        } else {
            Supporter::create($data);
            session()->flash('success', 'Supporter berhasil ditambahkan.');
        }

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $supporter = Supporter::findOrFail($id);
        if ($supporter->logo) {
            \Storage::disk('public')->delete($supporter->logo);
        }
        $supporter->delete();
        session()->flash('success', 'Supporter berhasil dihapus.');
    }

    public function toggleActive(int $id): void
    {
        $supporter = Supporter::findOrFail($id);
        $supporter->update(['is_active' => !$supporter->is_active]);
    }

    public function removeLogo(): void
    {
        if ($this->editingId) {
            $supporter = Supporter::findOrFail($this->editingId);
            if ($supporter->logo) {
                \Storage::disk('public')->delete($supporter->logo);
                $supporter->update(['logo' => null]);
            }
        }
        $this->existingLogo = null;
        $this->logo = null;
    }

    public function resetForm(): void
    {
        $this->editingId    = null;
        $this->name         = '';
        $this->url          = '';
        $this->type         = 'supporter';
        $this->sort_order   = 0;
        $this->is_active    = true;
        $this->logo         = null;
        $this->existingLogo = null;
        $this->showForm     = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Supporter::orderBy('type')->orderBy('sort_order');
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        return view('livewire.admin.supporter-manager', [
            'supporters' => $query->get(),
        ])->title('Kelola Supporter');
    }
}
