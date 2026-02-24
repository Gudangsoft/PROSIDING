<?php

namespace App\Livewire\Reviewer;

use App\Models\Review;
use App\Models\ReviewRubric;
use App\Models\ReviewCriterionScore;
use App\Models\PaperStatusLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class ReviewForm extends Component
{
    use WithFileUploads;

    public Review $review;
    public string $comments = '';
    public string $commentsForEditor = '';
    public string $recommendation = '';
    public $reviewFile;

    // Rubric scoring
    public array $criterionScores = [];   // [criterionId => score]
    public array $criterionComments = []; // [criterionId => comment]
    public ?ReviewRubric $rubric = null;

    public function mount(Review $review)
    {
        if ($review->reviewer_id !== Auth::id()) abort(403);

        $this->review = $review;
        $this->comments = $review->comments ?? '';
        $this->commentsForEditor = $review->comments_for_editor ?? '';
        $this->recommendation = $review->recommendation ?? '';

        // Load rubric for this conference
        $this->rubric = $review->paper->conference?->activeRubric();
        if ($this->rubric) {
            foreach ($this->rubric->criteria as $c) {
                $existing = \App\Models\ReviewCriterionScore::where('review_id', $review->id)
                    ->where('rubric_criterion_id', $c->id)->first();
                $this->criterionScores[$c->id]   = $existing?->score ?? 0;
                $this->criterionComments[$c->id] = $existing?->comment ?? '';
            }
        }
    }

    public function saveReview()
    {
        $this->validate([
            'comments' => 'required|min:20',
            'recommendation' => 'required|in:accept,minor_revision,major_revision,reject',
            'reviewFile' => 'nullable|file|mimes:doc,docx|max:20480',
        ], [
            'comments.required' => 'Komentar review wajib diisi.',
            'comments.min' => 'Komentar minimal 20 karakter.',
            'recommendation.required' => 'Rekomendasi wajib dipilih.',
        ]);

        $data = [
            'comments' => $this->comments,
            'comments_for_editor' => $this->commentsForEditor,
            'recommendation' => $this->recommendation,
            'status' => 'completed',
            'reviewed_at' => now(),
        ];

        if ($this->reviewFile) {
            $path = $this->reviewFile->store('reviews/' . $this->review->paper_id, 'public');
            $data['review_file_path'] = $path;
            $data['review_file_name'] = $this->reviewFile->getClientOriginalName();
        }

        $this->review->update($data);

        // Save rubric scores
        if ($this->rubric) {
            foreach ($this->criterionScores as $criterionId => $score) {
                ReviewCriterionScore::updateOrCreate(
                    ['review_id' => $this->review->id, 'rubric_criterion_id' => $criterionId],
                    [
                        'score'   => (int) $score,
                        'comment' => $this->criterionComments[$criterionId] ?? null,
                    ]
                );
            }
            // Update review.score with calculated weighted score
            $calcScore = $this->review->fresh()->calculated_score;
            if ($calcScore !== null) {
                $this->review->update(['score' => $calcScore]);
            }
        }

        // Log to paper history
        PaperStatusLog::log(
            $this->review->paper_id,
            $this->review->paper->status,
            $this->review->paper->status,
            'review_submitted',
            'Reviewer: ' . Auth::user()->name . ' — ' . $this->recommendation,
            ['recommendation' => $this->recommendation, 'reviewer_id' => Auth::id()]
        );

        // Send notification to admins/editors
        $adminIds = \App\Models\User::whereIn('role', ['admin', 'editor'])->pluck('id');
        \App\Models\Notification::createForUsers(
            $adminIds,
            'info',
            'Review Selesai',
            'Review untuk paper "' . \Illuminate\Support\Str::limit($this->review->paper->title, 50) . '" telah selesai.',
            route('admin.paper.detail', $this->review->paper),
            'Lihat Paper'
        );

        session()->flash('success', 'Review berhasil disimpan!');
        return redirect()->route('reviewer.reviews');
    }

    public function saveDraft()
    {
        $data = [
            'comments' => $this->comments,
            'comments_for_editor' => $this->commentsForEditor,
            'recommendation' => $this->recommendation ?: null,
            'status' => 'in_progress',
        ];

        if ($this->reviewFile) {
            $path = $this->reviewFile->store('reviews/' . $this->review->paper_id, 'public');
            $data['review_file_path'] = $path;
            $data['review_file_name'] = $this->reviewFile->getClientOriginalName();
        }

        $this->review->update($data);

        session()->flash('success', 'Draft review berhasil disimpan!');
    }

    public function removeReviewFile()
    {
        $this->reviewFile = null;
    }

    public function render()
    {
        $this->review->load(['paper.user', 'paper.files', 'paper.conference']);
        $blindReview = $this->review->paper->conference?->blind_review ?? false;
        return view('livewire.reviewer.review-form', compact('blindReview'))->layout('layouts.app');
    }
}
