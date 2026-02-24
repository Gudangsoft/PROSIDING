<?php

namespace App\Livewire\Admin;

use App\Models\BroadcastEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Livewire\Component;
use Livewire\WithPagination;

class BroadcastEmailManager extends Component
{
    use WithPagination;

    // compose
    public string $subject     = '';
    public string $body        = '';
    public string $filterRole  = '';
    public string $filterConf  = '';
    public string $filterPaperStatus = '';
    public bool $showCompose   = false;
    public bool $confirmSend   = false;
    public int $previewCount   = 0;

    public function updatedFilterRole(): void    { $this->countRecipients(); }
    public function updatedFilterConf(): void    { $this->countRecipients(); }
    public function updatedFilterPaperStatus(): void { $this->countRecipients(); }

    public function openCompose(): void
    {
        $this->reset(['subject', 'body', 'filterRole', 'filterConf', 'filterPaperStatus', 'confirmSend']);
        $this->showCompose = true;
        $this->countRecipients();
    }

    public function countRecipients(): void
    {
        $this->previewCount = $this->buildQuery()->count();
    }

    private function buildQuery()
    {
        $q = User::query();

        if ($this->filterRole) {
            $q->where('role', $this->filterRole);
        }

        if ($this->filterConf) {
            $q->whereHas('papers', fn($p) => $p->where('conference_id', $this->filterConf));
        }

        if ($this->filterPaperStatus) {
            $q->whereHas('papers', fn($p) => $p->where('status', $this->filterPaperStatus));
        }

        return $q;
    }

    public function send(): void
    {
        $this->validate([
            'subject' => 'required|min:5|max:300',
            'body'    => 'required|min:10',
        ]);

        $users = $this->buildQuery()->get(['id', 'name', 'email']);

        // Simpan log
        $broadcast = BroadcastEmail::create([
            'sent_by'         => auth()->id(),
            'subject'         => $this->subject,
            'body'            => $this->body,
            'filter'          => [
                'role'         => $this->filterRole,
                'conference'   => $this->filterConf,
                'paper_status' => $this->filterPaperStatus,
            ],
            'recipient_count' => $users->count(),
            'status'          => 'sending',
            'sent_at'         => now(),
        ]);

        $siteName = \App\Models\Setting::getValue('site_name', config('app.name'));
        $body     = $this->body;
        $subject  = $this->subject;

        foreach ($users as $user) {
            try {
                Mail::send([], [], function (Message $mail) use ($user, $subject, $body, $siteName) {
                    $mail->to($user->email, $user->name)
                         ->subject($subject)
                         ->html("<html><body style='font-family:sans-serif;max-width:600px;margin:auto;'>
                            <div style='background:#1d4ed8;color:white;padding:24px;border-radius:8px 8px 0 0;'>
                                <h2 style='margin:0;'>{$siteName}</h2>
                            </div>
                            <div style='padding:24px;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 8px 8px;'>
                                <p>Yth. {$user->name},</p>
                                " . nl2br(e($body)) . "
                                <hr style='margin:24px 0;border:none;border-top:1px solid #e5e7eb;'>
                                <p style='color:#6b7280;font-size:12px;'>Email ini dikirim oleh sistem {$siteName}.</p>
                            </div>
                         </body></html>");
                });
            } catch (\Exception $e) {
                // lanjut ke penerima berikutnya
            }
        }

        $broadcast->update(['status' => 'sent']);

        $this->showCompose = false;
        $this->confirmSend = false;
        session()->flash('success', "Email berhasil dikirim ke {$users->count()} penerima.");
    }

    public function render()
    {
        $history    = BroadcastEmail::with('sender')->latest()->paginate(15);
        $conferences = \App\Models\Conference::orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.broadcast-email', compact('history', 'conferences'))
            ->layout('layouts.app', ['title' => 'Broadcast Email']);
    }
}
