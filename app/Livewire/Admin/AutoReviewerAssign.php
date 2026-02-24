<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\Paper;
use App\Models\Review;
use App\Models\User;
use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class AutoReviewerAssign extends Component
{
    use WithPagination;

    public int $conferenceId  = 0;
    public bool $showResult   = false;
    public array $assignments = [];
    public int $assignedCount = 0;

    public function mount(): void
    {
        $this->conferenceId = Conference::where('is_active', true)->value('id') ?? 0;
    }

    public function preview(): void
    {
        $this->assignments = $this->buildAssignments();
        $this->showResult  = true;
    }

    public function assign(): void
    {
        $assignments = $this->buildAssignments();
        $count = 0;

        foreach ($assignments as $item) {
            if (empty($item['reviewerId'])) continue;

            // Cek sudah ada review sebelumnya
            $exists = Review::where('paper_id', $item['paperId'])
                ->where('reviewer_id', $item['reviewerId'])
                ->exists();

            if (!$exists) {
                Review::create([
                    'paper_id'    => $item['paperId'],
                    'reviewer_id' => $item['reviewerId'],
                    'assigned_by' => auth()->id(),
                    'status'      => 'assigned',
                ]);

                // Update paper status
                Paper::where('id', $item['paperId'])->update(['status' => 'in_review']);

                // Notifikasi reviewer
                Notification::create([
                    'user_id' => $item['reviewerId'],
                    'type'    => 'review_assigned',
                    'title'   => 'Paper Baru untuk Direview',
                    'message' => 'Anda ditugaskan mereview: ' . $item['paperTitle'],
                    'data'    => json_encode(['paper_id' => $item['paperId']]),
                ]);
                $count++;
            }
        }

        $this->assignedCount = $count;
        $this->showResult    = false;
        session()->flash('success', "{$count} paper berhasil di-assign ke reviewer.");
    }

    private function buildAssignments(): array
    {
        // Paper yang belum punya reviewer dan statusnya perlu review
        $papers = Paper::where('conference_id', $this->conferenceId)
            ->whereIn('status', ['submitted', 'screening'])
            ->whereDoesntHave('reviews')
            ->with('reviews')
            ->get();

        // Reviewer yang tersedia (berdasarkan bidang keahlian)
        $reviewers = User::where('role', 'reviewer')
            ->whereNotNull('reviewer_topics')
            ->get();

        $assignments = [];
        $reviewerLoad = []; // track beban per reviewer

        foreach ($papers as $paper) {
            $bestReviewer   = null;
            $bestScore      = -1;

            foreach ($reviewers as $reviewer) {
                // Cek beban
                $currentLoad = $reviewerLoad[$reviewer->id] ?? Review::where('reviewer_id', $reviewer->id)
                    ->whereHas('paper', fn($q) => $q->where('conference_id', $this->conferenceId))
                    ->whereNotIn('status', ['completed', 'rejected'])
                    ->count();

                if ($currentLoad >= ($reviewer->max_review_load ?? 5)) continue;

                // Hitung kecocokan topik
                $topics = is_array($reviewer->reviewer_topics) ? $reviewer->reviewer_topics : [];
                $score  = 0;
                foreach ($topics as $rt) {
                    if ($paper->topic && stripos($paper->topic, $rt) !== false) $score += 2;
                    if ($paper->keywords && stripos($paper->keywords, $rt) !== false) $score += 1;
                }

                if ($score > $bestScore || ($bestScore === 0 && $bestReviewer === null)) {
                    $bestScore        = $score;
                    $bestReviewer     = $reviewer;
                }
            }

            if ($bestReviewer) {
                $reviewerLoad[$bestReviewer->id] = ($reviewerLoad[$bestReviewer->id] ?? 0) + 1;
            }

            $assignments[] = [
                'paperId'       => $paper->id,
                'paperTitle'    => $paper->title,
                'paperTopic'    => $paper->topic,
                'reviewerId'    => $bestReviewer?->id,
                'reviewerName'  => $bestReviewer?->name ?? '— tidak ada reviewer cocok —',
                'matchScore'    => $bestScore,
            ];
        }

        return $assignments;
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        // Paper yang sudah punya reviewer
        $assigned = Review::with(['paper', 'reviewer'])
            ->whereHas('paper', fn($q) => $q->where('conference_id', $this->conferenceId))
            ->latest()
            ->paginate(15);

        // Stats reviewer
        $reviewerStats = User::where('role', 'reviewer')
            ->withCount(['reviews as active_reviews' => fn($q) => $q->whereHas(
                'paper', fn($p) => $p->where('conference_id', $this->conferenceId)
            )->whereNotIn('status', ['completed'])])
            ->get(['id', 'name', 'max_review_load', 'reviewer_topics']);

        return view('livewire.admin.auto-reviewer-assign', compact('conferences', 'assigned', 'reviewerStats'))
            ->layout('layouts.app', ['title' => 'Auto-assign Reviewer']);
    }
}
