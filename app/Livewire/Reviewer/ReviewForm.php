<?php

namespace App\Livewire\Reviewer;

use App\Models\Review;
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

    public function mount(Review $review)
    {
        if ($review->reviewer_id !== Auth::id()) abort(403);

        $this->review = $review;
        $this->comments = $review->comments ?? '';
        $this->commentsForEditor = $review->comments_for_editor ?? '';
        $this->recommendation = $review->recommendation ?? '';
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
            'reviewFile.mimes' => 'File harus berformat Word (.doc, .docx).',
            'reviewFile.max' => 'Ukuran file maksimal 20MB.',
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
        $this->review->load(['paper.user', 'paper.files']);
        return view('livewire.reviewer.review-form')->layout('layouts.app');
    }
}
