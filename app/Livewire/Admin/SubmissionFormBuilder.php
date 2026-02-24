<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use Livewire\Component;

class SubmissionFormBuilder extends Component
{
    public int $conferenceId = 0;
    public array $fields = [];

    // Form for new/edit field
    public bool $showFieldModal = false;
    public ?int $editIndex = null;
    public string $fieldLabel = '';
    public string $fieldKey = '';
    public string $fieldType = 'text'; // text, textarea, select, checkbox, number, file
    public string $fieldHelp = '';
    public bool $fieldRequired = false;
    public string $fieldOptions = ''; // comma-separated for select

    const FIELD_TYPES = [
        'text'     => 'Teks Singkat',
        'textarea' => 'Teks Panjang',
        'number'   => 'Angka',
        'select'   => 'Pilihan (Dropdown)',
        'checkbox' => 'Centang (Ya/Tidak)',
        'file'     => 'Upload File',
    ];

    public function mount(): void
    {
        $this->loadFields();
    }

    public function updatedConferenceId(): void
    {
        $this->loadFields();
    }

    protected function loadFields(): void
    {
        if (!$this->conferenceId) {
            $this->fields = [];
            return;
        }
        $conf = Conference::find($this->conferenceId);
        $this->fields = $conf?->submission_extra_fields ?? [];
    }

    public function openAddField(): void
    {
        $this->resetFieldForm();
        $this->showFieldModal = true;
    }

    public function openEditField(int $index): void
    {
        $f = $this->fields[$index];
        $this->editIndex      = $index;
        $this->fieldLabel     = $f['label'];
        $this->fieldKey       = $f['key'];
        $this->fieldType      = $f['type'];
        $this->fieldHelp      = $f['help'] ?? '';
        $this->fieldRequired  = $f['required'] ?? false;
        $this->fieldOptions   = isset($f['options']) ? implode(',', $f['options']) : '';
        $this->showFieldModal = true;
    }

    public function saveField(): void
    {
        $this->validate([
            'fieldLabel' => 'required|string|max:150',
            'fieldType'  => 'required|in:text,textarea,number,select,checkbox,file',
        ]);

        // Auto-generate key from label
        if (!$this->fieldKey) {
            $this->fieldKey = \Illuminate\Support\Str::snake(\Illuminate\Support\Str::slug($this->fieldLabel, '_'));
        }

        $field = [
            'label'    => $this->fieldLabel,
            'key'      => $this->fieldKey,
            'type'     => $this->fieldType,
            'help'     => $this->fieldHelp ?: null,
            'required' => $this->fieldRequired,
            'options'  => $this->fieldType === 'select'
                ? array_map('trim', explode(',', $this->fieldOptions))
                : null,
        ];

        if ($this->editIndex !== null) {
            $this->fields[$this->editIndex] = $field;
        } else {
            $this->fields[] = $field;
        }

        $this->saveToConference();
        $this->showFieldModal = false;
        $this->resetFieldForm();
    }

    public function deleteField(int $index): void
    {
        array_splice($this->fields, $index, 1);
        $this->saveToConference();
    }

    public function moveUp(int $index): void
    {
        if ($index === 0) return;
        [$this->fields[$index - 1], $this->fields[$index]] = [$this->fields[$index], $this->fields[$index - 1]];
        $this->saveToConference();
    }

    public function moveDown(int $index): void
    {
        if ($index >= count($this->fields) - 1) return;
        [$this->fields[$index], $this->fields[$index + 1]] = [$this->fields[$index + 1], $this->fields[$index]];
        $this->saveToConference();
    }

    protected function saveToConference(): void
    {
        if (!$this->conferenceId) return;
        Conference::where('id', $this->conferenceId)->update([
            'submission_extra_fields' => array_values($this->fields),
        ]);
        session()->flash('success', 'Form submission berhasil disimpan!');
    }

    protected function resetFieldForm(): void
    {
        $this->editIndex     = null;
        $this->fieldLabel    = '';
        $this->fieldKey      = '';
        $this->fieldType     = 'text';
        $this->fieldHelp     = '';
        $this->fieldRequired = false;
        $this->fieldOptions  = '';
        $this->resetValidation();
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        return view('livewire.admin.submission-form-builder', compact('conferences'))
            ->layout('layouts.app', ['title' => 'Form Submission Builder']);
    }
}
