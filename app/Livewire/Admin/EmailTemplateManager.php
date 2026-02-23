<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\EmailTemplate;
use Livewire\Component;

class EmailTemplateManager extends Component
{
    public ?int $selectedConferenceId = null;
    public array $emailTemplates = [];

    public function mount()
    {
        // Auto-select latest conference if only one exists
        $conferences = Conference::orderByDesc('id')->get();
        if ($conferences->count() === 1) {
            $this->selectedConferenceId = $conferences->first()->id;
            $this->loadTemplates();
        }
    }

    public function updatedSelectedConferenceId()
    {
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        if (!$this->selectedConferenceId) {
            $this->emailTemplates = [];
            return;
        }

        $saved = EmailTemplate::where('conference_id', $this->selectedConferenceId)
            ->get()
            ->keyBy('key');

        $this->emailTemplates = [];
        foreach (EmailTemplate::TYPES as $key => $cfg) {
            $row = $saved->get($key);
            $this->emailTemplates[$key] = [
                'id'        => $row?->id,
                'subject'   => $row?->subject ?? '',
                'body'      => $row?->body ?? '',
                'is_active' => $row ? (bool) $row->is_active : true,
            ];
        }
    }

    public function save()
    {
        if (!$this->selectedConferenceId) {
            session()->flash('error', 'Pilih kegiatan terlebih dahulu.');
            return;
        }

        foreach ($this->emailTemplates as $key => $tpl) {
            if (empty(trim($tpl['subject'] ?? '')) && empty(trim($tpl['body'] ?? ''))) {
                // If both empty and there's an existing record, delete it (reset to default)
                if (!empty($tpl['id'])) {
                    EmailTemplate::find($tpl['id'])?->delete();
                }
                continue;
            }
            EmailTemplate::updateOrCreate(
                ['conference_id' => $this->selectedConferenceId, 'key' => $key],
                [
                    'subject'   => $tpl['subject'] ?? '',
                    'body'      => $tpl['body'] ?? '',
                    'is_active' => (bool) ($tpl['is_active'] ?? true),
                ]
            );
        }

        session()->flash('success', 'Template email berhasil disimpan.');
        $this->loadTemplates(); // refresh ids
    }

    public function resetTemplate(string $key)
    {
        if (!empty($this->emailTemplates[$key]['id'])) {
            EmailTemplate::find($this->emailTemplates[$key]['id'])?->delete();
        }
        $this->emailTemplates[$key] = [
            'id'        => null,
            'subject'   => '',
            'body'      => '',
            'is_active' => true,
        ];
        session()->flash('success', 'Template "' . (EmailTemplate::TYPES[$key]['label'] ?? $key) . '" direset ke default.');
    }

    public function render()
    {
        return view('livewire.admin.email-template-manager', [
            'conferences' => Conference::orderByDesc('id')->get(['id', 'name', 'acronym']),
            'emailTypes'  => EmailTemplate::TYPES,
        ])->layout('layouts.app');
    }
}
