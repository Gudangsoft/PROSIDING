<?php

namespace App\Livewire\Author;

use App\Models\AbstractSubmission;
use App\Models\Conference;
use App\Models\Notification;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SubmitAbstract extends Component
{
    public ?int $abstractId = null;

    // conference
    public int $conference_id = 0;

    // form fields
    public string $title      = '';
    public string $abstract   = '';
    public string $keywords   = '';
    public string $topic      = '';

    // authors
    public array $authors = [
        ['name' => '', 'email' => '', 'institution' => '', 'is_correspondent' => true],
    ];

    public $conferences = [];
    public $topics      = [];
    public bool $isEdit = false;

    public function mount(?int $id = null): void
    {
        $this->conferences = Conference::where('status', 'active')
            ->orWhere('is_active', true)
            ->latest()
            ->get();

        if ($id) {
            $submission = AbstractSubmission::where('user_id', Auth::id())->findOrFail($id);
            $this->abstractId   = $submission->id;
            $this->conference_id = $submission->conference_id;
            $this->title        = $submission->title;
            $this->abstract     = $submission->abstract;
            $this->keywords     = $submission->keywords ?? '';
            $this->topic        = $submission->topic ?? '';
            $this->authors      = $submission->authors_meta ?? $this->authors;
            $this->isEdit       = true;
            $this->loadTopics();
        }
    }

    public function updatedConferenceId(): void
    {
        $this->loadTopics();
    }

    public function loadTopics(): void
    {
        if ($this->conference_id) {
            $this->topics = \App\Models\Topic::where('conference_id', $this->conference_id)
                ->orderBy('sort_order')
                ->pluck('name')
                ->toArray();
        }
    }

    public function addAuthor(): void
    {
        $this->authors[] = ['name' => '', 'email' => '', 'institution' => '', 'is_correspondent' => false];
    }

    public function removeAuthor(int $index): void
    {
        if (count($this->authors) > 1) {
            array_splice($this->authors, $index, 1);
        }
    }

    public function save(): void
    {
        $this->validate([
            'conference_id'  => 'required|exists:conferences,id',
            'title'          => 'required|min:10|max:500',
            'abstract'       => 'required|min:100|max:3000',
            'keywords'       => 'nullable|max:500',
            'topic'          => 'nullable|max:200',
            'authors'        => 'required|array|min:1',
            'authors.*.name' => 'required|string|max:200',
        ]);

        $data = [
            'user_id'       => Auth::id(),
            'conference_id' => $this->conference_id,
            'title'         => $this->title,
            'abstract'      => $this->abstract,
            'keywords'      => $this->keywords,
            'topic'         => $this->topic,
            'authors_meta'  => $this->authors,
        ];

        if ($this->isEdit) {
            $submission = AbstractSubmission::where('user_id', Auth::id())->findOrFail($this->abstractId);
            $submission->update(array_merge($data, ['status' => 'pending']));
            session()->flash('success', 'Abstrak berhasil diperbarui.');
        } else {
            $data['status'] = 'pending';
            AbstractSubmission::create($data);

            // Notifikasi admin
            $admins = User::whereIn('role', ['admin', 'editor'])->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type'    => 'abstract_submission',
                    'title'   => 'Abstrak Baru Diterima',
                    'message' => Auth::user()->name . ' mengirimkan abstrak: ' . $this->title,
                    'data'    => json_encode(['conference_id' => $this->conference_id]),
                ]);
            }
            session()->flash('success', 'Abstrak berhasil dikirim. Silakan tunggu hasil review.');
        }

        $this->redirect(route('author.abstracts'));
    }

    public function render()
    {
        return view('livewire.author.submit-abstract')
            ->layout('layouts.app', ['title' => $this->isEdit ? 'Edit Abstrak' : 'Submit Abstrak']);
    }
}
