<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    private int $rowNumber = 0;

    public function __construct(
        private ?int $conferenceId = null,
        private ?string $startDate = null,
        private ?string $endDate   = null
    ) {}

    public function query()
    {
        return Payment::with(['user', 'conference', 'paper'])
            ->where('status', 'verified')
            ->when($this->conferenceId, fn($q) => $q->where('conference_id', $this->conferenceId))
            ->when($this->startDate,    fn($q) => $q->whereDate('updated_at', '>=', $this->startDate))
            ->when($this->endDate,      fn($q) => $q->whereDate('updated_at', '<=', $this->endDate))
            ->orderBy('updated_at');
    }

    public function headings(): array
    {
        return ['No', 'Invoice', 'Nama', 'Email', 'Tipe', 'Jumlah', 'Metode', 'Konferensi', 'Tanggal Verifikasi'];
    }

    public function map($payment): array
    {
        return [
            ++$this->rowNumber,
            $payment->invoice_number ?? '-',
            $payment->user?->name,
            $payment->user?->email,
            ucfirst($payment->type ?? 'paper'),
            number_format($payment->amount, 0, ',', '.'),
            $payment->payment_method ?? '-',
            $payment->conference?->name,
            $payment->updated_at->format('d/m/Y'),
        ];
    }

    public function title(): string { return 'Pendapatan'; }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '7C3AED']], 'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]]];
    }
}
