<?php

namespace App\Livewire\Author;

use App\Models\Paper;
use Livewire\Component;
use Livewire\WithFileUploads;

class CameraReadyUpload extends Component
{
    use WithFileUploads;

    public Paper  $paper;
    public $file;

    public function mount(Paper $paper): void
    {
        abort_if($paper->user_id !== auth()->id(), 403);
        $this->paper = $paper;
    }

    public function upload(): void
    {
        $this->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:20480',
        ], [], ['file' => 'File camera-ready']);

        $path = $this->file->store('camera-ready', 'public');

        $this->paper->update([
            'camera_ready_path'          => $path,
            'camera_ready_submitted_at'  => now(),
            'camera_ready_status'        => 'pending',
            'camera_ready_notes'         => null,
        ]);

        // Notify admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'type'    => 'camera_ready_uploaded',
                'title'   => 'Camera-Ready Dikirim',
                'message' => 'Author "' . auth()->user()->name . '" mengirim camera-ready untuk paper "' . $this->paper->title . '".',
                'data'    => ['paper_id' => $this->paper->id],
            ]);
        }

        session()->flash('success', 'File camera-ready berhasil diupload! Menunggu persetujuan admin.');
        $this->file = null;
    }

    public function render()
    {
        return view('livewire.author.camera-ready-upload')
            ->layout('layouts.app', ['title' => 'Upload Camera-Ready']);
    }
}
