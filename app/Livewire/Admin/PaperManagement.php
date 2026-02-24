<?php

namespace App\Livewire\Admin;

use App\Models\Paper;
use App\Models\User;
use App\Models\Review;
use App\Models\Payment;
use App\Models\PaperStatusLog;
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

    // Bulk actions
    public array $selectedPapers = [];
    public bool $selectAll = false;
    public string $bulkAction = '';
    public string $bulkTargetStatus = '';
    public string $bulkReviewerId = '';
    public bool $showBulkModal = false;

    // Assign editor
    public bool $showAssignEditorModal = false;
    public ?int $assigningPaperId = null;
    public string $selectedEditorId = '';

    public function updatingSearch() { $this->resetPage(); $this->selectedPapers = []; }
    public function updatingActiveTab() { $this->resetPage(); $this->statusFilter = ''; $this->selectedPapers = []; }
    public function updatingStatusFilter() { $this->resetPage(); $this->selectedPapers = []; }

    public function updatedSelectAll(bool $value): void
    {
        // Select IDs from current page
        $ids = $this->getCurrentPageIds();
        $this->selectedPapers = $value ? $ids : [];
    }

    protected function getCurrentPageIds(): array
    {
        // Re-run query to get current page IDs
        return Paper::when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->activeTab === 'unassigned', fn($q) => $q->whereNull('assigned_editor_id')->whereNotIn('status', ['rejected', 'completed']))
            ->when($this->activeTab === 'all_active', fn($q) => $q->whereNotIn('status', ['rejected', 'completed']))
            ->when($this->activeTab === 'archives', fn($q) => $q->whereIn('status', ['rejected', 'completed']))
            ->latest()
            ->paginate(20)
            ->pluck('id')
            ->map(fn($id) => (string)$id)
            ->toArray();
    }

    public function openBulkAction(): void
    {
        if (empty($this->selectedPapers)) {
            session()->flash('error', 'Pilih minimal 1 paper terlebih dahulu.');
            return;
        }
        $this->showBulkModal = true;
    }

    public function executeBulkAction(): void
    {
        if (empty($this->selectedPapers)) return;

        $ids = array_map('intval', $this->selectedPapers);
        $papers = Paper::whereIn('id', $ids)->get();

        match ($this->bulkAction) {
            'change_status' => $this->bulkChangeStatus($papers),
            'assign_reviewer' => $this->bulkAssignReviewer($papers),
            default => null,
        };

        $this->showBulkModal = false;
        $this->selectedPapers = [];
        $this->selectAll = false;
        $this->bulkAction = '';
        $this->bulkTargetStatus = '';
        $this->bulkReviewerId = '';
    }

    protected function bulkChangeStatus($papers): void
    {
        if (!$this->bulkTargetStatus) {
            session()->flash('error', 'Pilih status tujuan.'); return;
        }
        $count = 0;
        foreach ($papers as $paper) {
            $old = $paper->status;
            $paper->update(['status' => $this->bulkTargetStatus]);
            PaperStatusLog::log($paper->id, $old, $this->bulkTargetStatus, 'status_changed', 'Bulk action oleh admin');
            $count++;
        }
        session()->flash('success', "{$count} paper berhasil diubah ke status: " . Paper::STATUS_LABELS[$this->bulkTargetStatus] ?? $this->bulkTargetStatus);
    }

    protected function bulkAssignReviewer($papers): void
    {
        if (!$this->bulkReviewerId) {
            session()->flash('error', 'Pilih reviewer.'); return;
        }
        $reviewer = User::findOrFail($this->bulkReviewerId);
        $count = 0;
        foreach ($papers as $paper) {
            // Only assign if no pending review exists for this reviewer
            $exists = Review::where('paper_id', $paper->id)
                ->where('reviewer_id', $reviewer->id)
                ->exists();
            if (!$exists) {
                Review::create([
                    'paper_id'    => $paper->id,
                    'reviewer_id' => $reviewer->id,
                    'assigned_by' => Auth::id(),
                    'status'      => 'pending',
                ]);
                $count++;
            }
        }
        session()->flash('success', "Reviewer {$reviewer->name} ditugaskan ke {$count} paper baru.");
    }

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
