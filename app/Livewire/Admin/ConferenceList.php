<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConferenceList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public ?int $viewingId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function toggleActive(Conference $conference)
    {
        if (!$conference->is_active) {
            Conference::where('is_active', true)->update(['is_active' => false]);
        }
        $conference->update(['is_active' => !$conference->is_active]);
        session()->flash('success', $conference->is_active ? "'{$conference->name}' diaktifkan." : "'{$conference->name}' dinonaktifkan.");
    }

    public function viewDetail($id)
    {
        $this->viewingId = $this->viewingId === $id ? null : $id;
    }

    public function duplicate(Conference $conference)
    {
        $newConf = $conference->replicate();
        $newConf->name = $conference->name . ' (Salinan)';
        $newConf->is_active = false;
        $newConf->status = 'draft';
        $newConf->created_by = Auth::id();
        $newConf->save();

        // Duplicate related data
        foreach ($conference->importantDates as $date) {
            $newDate = $date->replicate();
            $newDate->conference_id = $newConf->id;
            $newDate->save();
        }
        foreach ($conference->committees as $comm) {
            $newComm = $comm->replicate();
            $newComm->conference_id = $newConf->id;
            $newComm->save();
        }
        foreach ($conference->topics as $topic) {
            $newTopic = $topic->replicate();
            $newTopic->conference_id = $newConf->id;
            $newTopic->save();
        }
        foreach ($conference->keynoteSpeakers as $speaker) {
            $newSpeaker = $speaker->replicate();
            $newSpeaker->conference_id = $newConf->id;
            $newSpeaker->save();
        }
        if ($guideline = $conference->guideline) {
            $newGuide = $guideline->replicate();
            $newGuide->conference_id = $newConf->id;
            $newGuide->save();
        }

        session()->flash('success', "Kegiatan berhasil diduplikasi sebagai '{$newConf->name}'.");
    }

    public function delete(Conference $conference)
    {
        // Clean up uploaded files
        if ($conference->cover_image) {
            Storage::disk('public')->delete($conference->cover_image);
        }
        if ($conference->logo) {
            Storage::disk('public')->delete($conference->logo);
        }
        if ($guideline = $conference->guideline) {
            if ($guideline->template_file) {
                Storage::disk('public')->delete($guideline->template_file);
            }
        }

        $conference->delete();
        session()->flash('success', 'Kegiatan berhasil dihapus.');
    }

    public function render()
    {
        $conferences = Conference::query()
            ->withCount(['importantDates', 'committees', 'topics', 'keynoteSpeakers'])
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('acronym', 'like', "%{$this->search}%")
                ->orWhere('theme', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Conference::count(),
            'published' => Conference::where('status', 'published')->count(),
            'draft' => Conference::where('status', 'draft')->count(),
            'active' => Conference::where('is_active', true)->count(),
        ];

        return view('livewire.admin.conference-list', compact('conferences', 'stats'))
            ->layout('layouts.app');
    }
}
