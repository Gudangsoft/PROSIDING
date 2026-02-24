<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\Paper;
use App\Models\Payment;
use App\Models\User;
use Livewire\Component;

class ReportsManager extends Component
{
    public string $reportType   = 'papers';
    public int $conferenceId    = 0;
    public string $paperStatus  = '';
    public string $startDate    = '';
    public string $endDate      = '';

    public function mount(): void
    {
        $this->conferenceId = Conference::where('is_active', true)->value('id') ?? 0;
        $this->startDate    = now()->startOfMonth()->format('Y-m-d');
        $this->endDate      = now()->format('Y-m-d');
    }

    public function exportUrl(): string
    {
        $params = ['conference_id' => $this->conferenceId ?: null];

        return match ($this->reportType) {
            'participants' => route('admin.reports.participants', array_filter($params)),
            'revenue'      => route('admin.reports.revenue', array_filter(array_merge($params, ['start_date' => $this->startDate, 'end_date' => $this->endDate]))),
            default        => route('admin.reports.papers', array_filter(array_merge($params, ['status' => $this->paperStatus]))),
        };
    }

    public function getPreviewStats(): array
    {
        $confId = $this->conferenceId ?: null;

        return match ($this->reportType) {
            'participants' => [
                'total' => User::whereIn('role', ['author', 'participant'])
                    ->when($confId, fn($q) => $q->whereHas('papers', fn($p) => $p->where('conference_id', $confId)))
                    ->count(),
                'label' => 'Peserta',
            ],
            'revenue' => [
                'total' => 'Rp ' . number_format(
                    Payment::where('status', 'verified')
                        ->when($confId, fn($q) => $q->where('conference_id', $confId))
                        ->when($this->startDate, fn($q) => $q->whereDate('updated_at', '>=', $this->startDate))
                        ->when($this->endDate, fn($q) => $q->whereDate('updated_at', '<=', $this->endDate))
                        ->sum('amount'), 0, ',', '.'),
                'label' => 'Total Pendapatan',
            ],
            default => [
                'total' => Paper::when($confId, fn($q) => $q->where('conference_id', $confId))
                    ->when($this->paperStatus, fn($q) => $q->where('status', $this->paperStatus))
                    ->count(),
                'label' => 'Paper',
            ],
        };
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        $stats       = $this->getPreviewStats();

        return view('livewire.admin.reports-manager', compact('conferences', 'stats'))
            ->layout('layouts.app', ['title' => 'Export Laporan']);
    }
}
