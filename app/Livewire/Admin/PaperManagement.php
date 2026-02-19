<?php

namespace App\Livewire\Admin;

use App\Models\Paper;
use App\Models\User;
use App\Models\Review;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class PaperManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $activeTab = '';
    public string $statusFilter = '';
    public bool $showFilters = false;

    // Assign editor
    public bool $showAssignEditorModal = false;
    public ?int $assigningPaperId = null;
    public string $selectedEditorId = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingActiveTab() { $this->resetPage(); $this->statusFilter = ''; }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function mount()
    {
        $this->activeTab = 'unassigned';
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->statusFilter = '';
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function openAssignEditor(int $paperId)
    {
        $this->assigningPaperId = $paperId;
        $this->selectedEditorId = '';
        $this->showAssignEditorModal = true;
    }

    public function closeAssignEditor()
    {
        $this->showAssignEditorModal = false;
        $this->assigningPaperId = null;
        $this->selectedEditorId = '';
    }

    public function assignEditor()
    {
        $this->validate(['selectedEditorId' => 'required|exists:users,id']);

        $paper = Paper::findOrFail($this->assigningPaperId);
        $editor = User::findOrFail($this->selectedEditorId);
        $paper->update(['assigned_editor_id' => $editor->id]);

        if (class_exists(\App\Models\Notification::class)) {
            \App\Models\Notification::createForUser(
                $paper->user_id,
                'info',
                'Paper "' . \Illuminate\Support\Str::limit($paper->title, 50) . '" telah ditugaskan ke editor: ' . $editor->name,
                route('author.paper.detail', $paper),
                'Editor Ditugaskan'
            );
        }

        $this->closeAssignEditor();
        session()->flash('success', "Editor {$editor->name} berhasil ditugaskan.");
    }

    public function getTabCountsProperty(): array
    {
        $userId = Auth::id();

        return [
            'my_queue' => Paper::where(function ($q) use ($userId) {
                $q->where('assigned_editor_id', $userId)
                  ->orWhereHas('reviews', fn($r) => $r->where('assigned_by', $userId));
            })->whereNotIn('status', ['rejected', 'completed'])->count(),

            'unassigned' => Paper::whereNull('assigned_editor_id')
                ->whereNotIn('status', ['rejected', 'completed'])
                ->count(),

            'all_active' => Paper::whereNotIn('status', ['rejected', 'completed'])->count(),

            'archives' => Paper::whereIn('status', ['rejected', 'completed'])->count(),
        ];
    }

    public function render()
    {
        $userId = Auth::id();

        $query = Paper::with(['user', 'assignedEditor', 'reviews.reviewer']);

        // Tab filtering
        switch ($this->activeTab) {
            case 'my_queue':
                $query->where(function ($q) use ($userId) {
                    $q->where('assigned_editor_id', $userId)
                      ->orWhereHas('reviews', fn($r) => $r->where('assigned_by', $userId));
                })->whereNotIn('status', ['rejected', 'completed']);
                break;
            case 'unassigned':
                $query->whereNull('assigned_editor_id')
                    ->whereNotIn('status', ['rejected', 'completed']);
                break;
            case 'all_active':
                $query->whereNotIn('status', ['rejected', 'completed']);
                break;
            case 'archives':
                $query->whereIn('status', ['rejected', 'completed']);
                break;
        }

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"));
            });
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $papers = $query->latest()->paginate(20);
        $editors = User::whereIn('role', ['admin', 'editor'])->get();
        $activeConference = \App\Models\Conference::where('is_active', true)->first();

        return view('livewire.admin.paper-management', compact('papers', 'editors', 'activeConference'))->layout('layouts.app');
    }
    
    public function bulkGenerateCertificates()
    {
        $conference = \App\Models\Conference::where('is_active', true)->first();
        
        if (!$conference) {
            session()->flash('error', 'Tidak ada conference aktif.');
            return;
        }
        
        if ($conference->certificate_generation_mode !== 'auto') {
            session()->flash('error', 'Mode sertifikat conference ini adalah Manual. Ubah ke Auto-Generate di pengaturan conference.');
            return;
        }
        
        try {
            $generator = new \App\Services\DocumentGenerator();
            $stats = $generator->batchGenerateCertificates($conference);
            
            session()->flash('success', "Sertifikat berhasil di-generate untuk {$stats['authors']} pemakalah." . ($stats['failed'] > 0 ? " Gagal: {$stats['failed']}." : ''));
        } catch (\Exception $e) {
            \Log::error('Batch certificate error: ' . $e->getMessage());
            session()->flash('error', 'Gagal generate sertifikat: ' . $e->getMessage());
        }
    }
}
