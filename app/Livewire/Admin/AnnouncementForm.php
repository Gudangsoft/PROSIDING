<?php

namespace App\Livewire\Admin;

use App\Models\Announcement;
use App\Models\Conference;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class AnnouncementForm extends Component
{
    use WithFileUploads;

    public ?Announcement $announcement = null;
    public bool $isEdit = false;

    public string $title = '';
    public string $content = '';
    public string $type = 'info';
    public string $priority = 'normal';
    public array $audience = ['all'];
    public string $status = 'draft';
    public bool $is_pinned = false;
    public string $conference_id = '';
    public string $expires_at = '';
    public $attachment;

    public function mount(?Announcement $announcement = null)
    {
        if ($announcement && $announcement->exists) {
            $this->isEdit = true;
            $this->announcement = $announcement;
            $this->fill([
                'title' => $announcement->title,
                'content' => $announcement->content,
                'type' => $announcement->type,
                'priority' => $announcement->priority,
                'audience' => $announcement->audience ?? ['all'],
                'status' => $announcement->status,
                'is_pinned' => $announcement->is_pinned,
                'conference_id' => (string) ($announcement->conference_id ?? ''),
                'expires_at' => $announcement->expires_at?->format('Y-m-d') ?? '',
            ]);
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required',
            'priority' => 'required',
            'audience' => 'required|array|min:1',
            'audience.*' => 'in:all,author,reviewer,editor,admin,participant,web',
            'status' => 'required|in:draft,published,archived',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $data = [
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type,
            'priority' => $this->priority,
            'audience' => $this->audience,
            'status' => $this->status,
            'is_pinned' => $this->is_pinned,
            'conference_id' => $this->conference_id ?: null,
            'expires_at' => $this->expires_at ?: null,
        ];

        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('announcements', 'public');
        }

        if ($this->status === 'published') {
            $data['published_at'] = now();
        }

        if ($this->isEdit) {
            $this->announcement->update($data);
        } else {
            $data['created_by'] = Auth::id();
            Announcement::create($data);
        }

        session()->flash('success', $this->isEdit ? 'Pengumuman berhasil diperbarui.' : 'Pengumuman berhasil dibuat.');
        return redirect()->route('admin.announcements');
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get();
        return view('livewire.admin.announcement-form', compact('conferences'))
            ->layout('layouts.app');
    }
}
