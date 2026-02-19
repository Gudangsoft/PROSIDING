<?php

namespace App\Livewire\Author;

use App\Models\Paper;
use App\Models\PaperFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class PaperDetail extends Component
{
    use WithFileUploads;

    public Paper $paper;
    public $revisionFile;
    public string $revisionNotes = '';

    public function mount(Paper $paper)
    {
        if ($paper->user_id !== Auth::id()) {
            abort(403);
        }
        $this->paper = $paper;
    }

    public function submitRevision()
    {
        $this->validate([
            'revisionFile' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $path = $this->revisionFile->store('papers/' . $this->paper->id . '/revisions', 'public');
        PaperFile::create([
            'paper_id' => $this->paper->id,
            'type' => 'revision',
            'file_path' => $path,
            'original_name' => $this->revisionFile->getClientOriginalName(),
            'mime_type' => $this->revisionFile->getMimeType(),
            'file_size' => $this->revisionFile->getSize(),
            'notes' => $this->revisionNotes,
        ]);

        $this->paper->update(['status' => 'revised']);
        
        // Send notification to admins/editors
        $adminIds = \App\Models\User::whereIn('role', ['admin', 'editor'])->pluck('id');
        \App\Models\Notification::createForUsers(
            $adminIds,
            'info',
            'Revisi Paper Diterima',
            'Author telah mengupload revisi untuk paper "' . \Illuminate\Support\Str::limit($this->paper->title, 50) . '"',
            route('admin.paper.detail', $this->paper),
            'Lihat Paper'
        );
        
        $this->revisionFile = null;
        $this->revisionNotes = '';
        $this->paper->refresh();

        session()->flash('success', 'Revisi berhasil diunggah!');
    }

    public function render()
    {
        $this->paper->load(['files', 'reviews.reviewer', 'payment', 'deliverables']);
        return view('livewire.author.paper-detail')->layout('layouts.app');
    }
}
