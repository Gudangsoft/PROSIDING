<?php

namespace App\Livewire\Author;

use App\Models\Paper;
use App\Models\Deliverable;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class DeliverableUpload extends Component
{
    use WithFileUploads;

    public Paper $paper;
    public $posterFile;
    public $pptFile;
    public $finalPaperFile;

    public function mount(Paper $paper)
    {
        if ($paper->user_id !== Auth::id()) abort(403);
        $this->paper = $paper;
    }

    public function uploadDeliverable(string $type)
    {
        $fileProperty = match($type) {
            'poster' => 'posterFile',
            'ppt' => 'pptFile',
            'final_paper' => 'finalPaperFile',
        };

        $this->validate([
            $fileProperty => 'required|file|mimes:pdf,ppt,pptx,jpg,jpeg,png|max:20480',
        ]);

        $file = $this->$fileProperty;
        $path = $file->store('deliverables/' . $this->paper->id . '/' . $type, 'public');

        // Remove old deliverable of same type
        Deliverable::where('paper_id', $this->paper->id)
            ->where('type', $type)
            ->where('direction', 'author_upload')
            ->delete();

        Deliverable::create([
            'paper_id' => $this->paper->id,
            'user_id' => Auth::id(),
            'type' => $type,
            'direction' => 'author_upload',
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
        ]);

        $this->$fileProperty = null;
        $this->paper->refresh();

        // Check if all 3 are uploaded
        $uploadedTypes = Deliverable::where('paper_id', $this->paper->id)
            ->where('direction', 'author_upload')
            ->pluck('type')
            ->toArray();

        if (count(array_intersect(['poster', 'ppt', 'final_paper'], $uploadedTypes)) === 3) {
            $this->paper->update(['status' => 'completed']);
        } else {
            $this->paper->update(['status' => 'deliverables_pending']);
        }

        session()->flash('success', Deliverable::TYPE_LABELS[$type] . ' berhasil diunggah!');
    }

    public function render()
    {
        $authorDeliverables = Deliverable::where('paper_id', $this->paper->id)
            ->where('direction', 'author_upload')->get()->keyBy('type');
        $adminDeliverables = Deliverable::where('paper_id', $this->paper->id)
            ->where('direction', 'admin_send')->get();

        return view('livewire.author.deliverable-upload', compact('authorDeliverables', 'adminDeliverables'))->layout('layouts.app');
    }
}
