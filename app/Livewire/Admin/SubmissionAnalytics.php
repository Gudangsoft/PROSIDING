<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\Paper;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SubmissionAnalytics extends Component
{
    public int    $conferenceId = 0;
    public string $periodDays   = '30';

    public function updatedConferenceId(): void {}
    public function updatedPeriodDays(): void {}

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        $timeline    = collect();
        $statusBreak = collect();
        $reviewerLoad= collect();
        $totals      = [];

        if ($this->conferenceId) {
            $days = (int) $this->periodDays;

            // Daily submission timeline
            $timeline = Paper::where('conference_id', $this->conferenceId)
                ->where('submitted_at', '>=', now()->subDays($days))
                ->selectRaw('DATE(submitted_at) as day, COUNT(*) as total')
                ->groupBy('day')
                ->orderBy('day')
                ->get()
                ->keyBy('day');

            // Fill gaps
            $filled = collect();
            for ($i = $days - 1; $i >= 0; $i--) {
                $day = now()->subDays($i)->format('Y-m-d');
                $filled[$day] = $timeline->get($day)?->total ?? 0;
            }
            $timeline = $filled;

            // Status breakdown
            $statusBreak = Paper::where('conference_id', $this->conferenceId)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->orderByDesc('total')
                ->get();

            // Reviewer workload
            $reviewerLoad = User::where('role', 'reviewer')
                ->withCount([
                    'reviews as total_assigned' => fn($q) => $q
                        ->whereHas('paper', fn($p) => $p->where('conference_id', $this->conferenceId)),
                    'reviews as completed' => fn($q) => $q
                        ->whereHas('paper', fn($p) => $p->where('conference_id', $this->conferenceId))
                        ->whereNotNull('reviewed_at'),
                ])
                ->having('total_assigned', '>', 0)
                ->orderByDesc('total_assigned')
                ->get(['id', 'name', 'email']);

            // Summary totals
            $totals = [
                'total'    => Paper::where('conference_id', $this->conferenceId)->count(),
                'accepted' => Paper::where('conference_id', $this->conferenceId)->where('status', 'accepted')->count(),
                'in_review'=> Paper::where('conference_id', $this->conferenceId)->where('status', 'in_review')->count(),
                'paid'     => Paper::where('conference_id', $this->conferenceId)->whereIn('status', ['payment_verified','deliverables_pending','completed'])->count(),
                'completed'=> Paper::where('conference_id', $this->conferenceId)->where('status', 'completed')->count(),
                'period_new'=> Paper::where('conference_id', $this->conferenceId)
                    ->where('submitted_at', '>=', now()->subDays($days))->count(),
            ];
        }

        return view('livewire.admin.submission-analytics', compact(
            'conferences', 'timeline', 'statusBreak', 'reviewerLoad', 'totals'
        ))->layout('layouts.app', ['title' => 'Analytics & Monitoring']);
    }
}
