<?php

namespace App\Livewire\Author;

use App\Models\Conference;
use App\Models\Paper;
use App\Models\Notification;
use Livewire\Component;

class AuthorDashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $papers = Paper::where('user_id', $user->id)
            ->with(['conference:id,name', 'payment', 'revisionRequests' => fn($q) => $q->whereNull('resolved_at')])
            ->latest('submitted_at')
            ->get();

        $stats = [
            'total'             => $papers->count(),
            'under_review'      => $papers->whereIn('status', ['submitted','screening','in_review'])->count(),
            'accepted'          => $papers->whereIn('status', ['accepted','payment_pending','payment_uploaded','payment_verified','deliverables_pending','completed'])->count(),
            'revision_required' => $papers->whereIn('status', ['revision_required'])->count(),
            'completed'         => $papers->where('status', 'completed')->count(),
            'camera_ready_pending' => $papers->where('camera_ready_status', 'pending')->count(),
        ];

        $pendingActions = [];

        foreach ($papers as $p) {
            if ($p->status === 'revision_required') {
                $pendingActions[] = ['type' => 'revision', 'paper' => $p, 'label' => 'Upload Revisi'];
            }
            if ($p->status === 'accepted' && $p->camera_ready_status === 'none') {
                $pendingActions[] = ['type' => 'camera_ready', 'paper' => $p, 'label' => 'Upload Camera-Ready'];
            }
            if ($p->camera_ready_status === 'rejected') {
                $pendingActions[] = ['type' => 'camera_ready_reupload', 'paper' => $p, 'label' => 'Upload Ulang Camera-Ready'];
            }
            if ($p->status === 'payment_pending') {
                $pendingActions[] = ['type' => 'payment', 'paper' => $p, 'label' => 'Upload Bukti Pembayaran'];
            }
        }

        $recentNotifs = Notification::where('user_id', $user->id)
            ->latest()->take(5)->get();

        $activeConference = Conference::active()->published()->first(['id', 'name', 'start_date', 'end_date']);

        return view('livewire.author.author-dashboard', compact(
            'papers', 'stats', 'pendingActions', 'recentNotifs', 'activeConference'
        ))->layout('layouts.app', ['title' => 'Dashboard Author']);
    }
}
