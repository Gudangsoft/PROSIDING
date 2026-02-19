<?php

namespace App\Livewire\Author;

use App\Models\Paper;
use App\Models\PaperFile;
use App\Models\Topic;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class SubmitPaper extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $abstract = '';
    public string $keywords = '';
    public string $selectedTopic = '';
    public $abstractFile;
    public $paperFile;
    public $turnitinFile;

    // Contributors
    public array $contributors = [];
    public string $contribName = '';
    public string $contribEmail = '';
    public string $contribInstitution = '';

    protected $rules = [
        'title' => 'required|min:10|max:500',
        'abstract' => 'required|min:50',
        'keywords' => 'required',
        'selectedTopic' => 'required',
        'abstractFile' => 'required|file|mimes:pdf,doc,docx|max:10240',
        'paperFile' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        'turnitinFile' => 'nullable|file|mimes:pdf|max:10240',
    ];

    protected $messages = [
        'title.required' => 'Judul paper wajib diisi.',
        'title.min' => 'Judul minimal 10 karakter.',
        'abstract.required' => 'Abstrak wajib diisi.',
        'abstract.min' => 'Abstrak minimal 50 karakter.',
        'keywords.required' => 'Kata kunci wajib diisi.',
        'selectedTopic.required' => 'Topik wajib dipilih.',
        'abstractFile.required' => 'File abstrak wajib diunggah.',
        'abstractFile.mimes' => 'File abstrak harus berformat PDF, DOC, atau DOCX.',
        'abstractFile.max' => 'Ukuran file abstrak maksimal 10MB.',
        'paperFile.mimes' => 'File full paper harus berformat PDF, DOC, atau DOCX.',
        'paperFile.max' => 'Ukuran file full paper maksimal 10MB.',
        'turnitinFile.mimes' => 'File turnitin harus berformat PDF.',
        'turnitinFile.max' => 'Ukuran file turnitin maksimal 10MB.',
    ];

    public function addContributor()
    {
        $validated = \Illuminate\Support\Facades\Validator::make([
            'contribName' => $this->contribName,
            'contribEmail' => $this->contribEmail,
            'contribInstitution' => $this->contribInstitution,
        ], [
            'contribName' => 'required|string|max:255',
            'contribEmail' => 'required|email|max:255',
            'contribInstitution' => 'required|string|max:255',
        ], [
            'contribName.required' => 'Nama kontributor wajib diisi.',
            'contribEmail.required' => 'Email kontributor wajib diisi.',
            'contribEmail.email' => 'Format email tidak valid.',
            'contribInstitution.required' => 'Institusi kontributor wajib diisi.',
        ]);

        if ($validated->fails()) {
            $this->setErrorBag($validated->errors());
            return;
        }

        $this->contributors[] = [
            'name' => $this->contribName,
            'email' => $this->contribEmail,
            'institution' => $this->contribInstitution,
        ];

        $this->contribName = '';
        $this->contribEmail = '';
        $this->contribInstitution = '';
        $this->resetErrorBag(['contribName', 'contribEmail', 'contribInstitution']);
    }

    public function removeContributor(int $index)
    {
        unset($this->contributors[$index]);
        $this->contributors = array_values($this->contributors);
    }

    public function submit()
    {
        $this->validate();

        $activeConference = \App\Models\Conference::where('is_active', true)->first();

        $paper = Paper::create([
            'user_id' => Auth::id(),
            'conference_id' => $activeConference?->id,
            'title' => $this->title,
            'abstract' => $this->abstract,
            'keywords' => $this->keywords,
            'topic' => $this->selectedTopic,
            'authors_meta' => !empty($this->contributors) ? $this->contributors : null,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Upload abstract file (wajib)
        $abstractPath = $this->abstractFile->store('papers/' . $paper->id, 'public');
        PaperFile::create([
            'paper_id' => $paper->id,
            'type' => 'abstract',
            'file_path' => $abstractPath,
            'original_name' => $this->abstractFile->getClientOriginalName(),
            'mime_type' => $this->abstractFile->getMimeType(),
            'file_size' => $this->abstractFile->getSize(),
        ]);

        // Upload full paper if provided (opsional)
        if ($this->paperFile) {
            $path = $this->paperFile->store('papers/' . $paper->id, 'public');
            PaperFile::create([
                'paper_id' => $paper->id,
                'type' => 'full_paper',
                'file_path' => $path,
                'original_name' => $this->paperFile->getClientOriginalName(),
                'mime_type' => $this->paperFile->getMimeType(),
                'file_size' => $this->paperFile->getSize(),
            ]);
        }

        // Upload turnitin if provided (opsional)
        if ($this->turnitinFile) {
            $turnitinPath = $this->turnitinFile->store('papers/' . $paper->id . '/turnitin', 'public');
            PaperFile::create([
                'paper_id' => $paper->id,
                'type' => 'turnitin',
                'file_path' => $turnitinPath,
                'original_name' => $this->turnitinFile->getClientOriginalName(),
                'mime_type' => $this->turnitinFile->getMimeType(),
                'file_size' => $this->turnitinFile->getSize(),
            ]);
        }

        session()->flash('success', 'Paper berhasil disubmit!');
        return redirect()->route('author.papers');
    }

    public function render()
    {
        $topics = Topic::orderBy('sort_order')->get();
        return view('livewire.author.submit-paper', compact('topics'))->layout('layouts.app');
    }
}
