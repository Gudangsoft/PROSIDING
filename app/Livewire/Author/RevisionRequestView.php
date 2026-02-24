<?php

namespace App\Livewire\Author;

use App\Models\Paper;
use App\Models\RevisionRequest;
use Livewire\Component;
use Livewire\WithFileUploads;

class RevisionRequestView extends Component
{
    use WithFileUploads;

    public Paper  $paper;
    public $revisedFile;
    public string $response = '';

    public function mount(Paper $paper): void
    {
        abort_if($paper->user_id !== auth()->id(), 403);
        $this->paper = $paper;
    }

    public function submitRevision(int $requestId): void
    {
        $this->validate([
            'response'    => 'nullable|string|max:2000',
            'revisedFile' => 'required|file|mimes:pdf,doc,docx,zip|max:20480',
        ], [], ['revisedFile' => 'File revisi', 'response' => 'Komentar']);

        $path = $this->revisedFile->store('revisions', 'public');
        $revReq = RevisionRequest::findOrFail($requestId);

        $revReq->update([
            'author_response'  => $this->response,
            'revised_file_path'=> $path,
            'resolved_at'      => now(),
        ]);

        // Also upload as paper file
        \App\Models\PaperFile::create([
            'paper_id' => $this->paper->id,
            'type'     => 'revision',
            'path'     => $path,
            'note'     => 'Revisi atas permintaan: ' . \Illuminate\Support\Str::limit($revReq->note, 80),
        ]);

        $this->paper->update(['status' => 'revised']);

        // Notify admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'type'    => 'paper_revised',
                'title'   => 'Paper Direvisi',
                'message' => 'Author "' . auth()->user()->name . '" mengirim revisi untuk paper "' . $this->paper->title . '".',
                'data'    => ['paper_id' => $this->paper->id],
            ]);
        }

        $this->revisedFile = null;
        $this->response    = '';
        session()->flash('success', 'Revisi berhasil dikirim!');
    }

    public function render()
    {
        $revisionRequests = RevisionRequest::where('paper_id', $this->paper->id)
            ->with('admin:id,name')
            ->latest()
            ->get();

        return view('livewire.author.revision-request-view', compact('revisionRequests'))
            ->layout('layouts.app', ['title' => 'Permintaan Revisi']);
    }
}
