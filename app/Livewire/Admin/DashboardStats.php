<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\Paper;
use App\Models\Payment;
use App\Models\User;
use App\Models\AbstractSubmission;
use App\Models\Review;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DashboardStats extends Component
{
    public int $conferenceId = 0;
    public string $period    = '30'; // days

    public function mount(): void
    {
        $this->conferenceId = Conference::where('is_active', true)->value('id') ?? 0;
    }

    public function render()
    {
        $days      = (int) $this->period;
        $confId    = $this->conferenceId ?: null;
        $since     = now()->subDays($days)->startOfDay();

        // ─── Summary cards ───────────────────────────────────────────
        $totalPapers = Paper::when($confId, fn($q) => $q->where('conference_id', $confId))->count();
        $acceptedPapers = Paper::when($confId, fn($q) => $q->where('conference_id', $confId))
            ->whereIn('status', ['accepted', 'payment_verified', 'deliverables_pending', 'completed'])
            ->count();

        $totalUsers = User::whereIn('role', ['author', 'participant'])->count();
        $totalRevenue = Payment::when($confId, fn($q) => $q->whereHas('paper', fn($p) => $p->where('conference_id', $confId)))
            ->where('status', 'verified')
            ->sum('amount');

        $pendingPayments = Payment::when($confId, fn($q) => $q->whereHas('paper', fn($p) => $p->where('conference_id', $confId)))
            ->whereIn('status', ['pending', 'uploaded'])
            ->count();

        $pendingAbstracts = AbstractSubmission::when($confId, fn($q) => $q->where('conference_id', $confId))
            ->where('status', 'pending')
            ->count();

        // ─── Daily submissions chart (last N days) ───────────────────
        $submissionsChart = Paper::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->when($confId, fn($q) => $q->where('conference_id', $confId))
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Fill missing days with 0
        $chartLabels = [];
        $chartData   = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d  = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d/m');
            $chartData[]   = $submissionsChart[$d] ?? 0;
        }

        // ─── Revenue per day ────────────────────────────────────────
        $revenueChart = Payment::selectRaw('DATE(updated_at) as date, SUM(amount) as total')
            ->when($confId, fn($q) => $q->whereHas('paper', fn($p) => $p->where('conference_id', $confId)))
            ->where('status', 'verified')
            ->where('updated_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $revenueData = [];
        foreach ($chartLabels as $i => $label) {
            $d = now()->subDays($days - 1 - $i)->format('Y-m-d');
            $revenueData[] = (float) ($revenueChart[$d] ?? 0);
        }

        // ─── Paper status distribution (pie) ────────────────────────
        $statusDist = Paper::selectRaw('status, COUNT(*) as count')
            ->when($confId, fn($q) => $q->where('conference_id', $confId))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // ─── Registration conversion ─────────────────────────────────
        $registeredAuthors     = User::where('role', 'author')->count();
        $authorsWithPaper      = Paper::when($confId, fn($q) => $q->where('conference_id', $confId))
            ->distinct('user_id')->count('user_id');
        $conversionRate = $registeredAuthors > 0
            ? round(($authorsWithPaper / $registeredAuthors) * 100, 1)
            : 0;

        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.dashboard-stats', compact(
            'totalPapers', 'acceptedPapers', 'totalUsers', 'totalRevenue',
            'pendingPayments', 'pendingAbstracts',
            'chartLabels', 'chartData', 'revenueData',
            'statusDist', 'conversionRate', 'registeredAuthors', 'authorsWithPaper',
            'conferences'
        ))->layout('layouts.app', ['title' => 'Statistik & Analitik']);
    }
}
