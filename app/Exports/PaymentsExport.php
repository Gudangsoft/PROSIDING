<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PaymentsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    private int $rowNumber = 0;

    public function __construct(
        private string $typeFilter = '',
        private string $statusFilter = ''
    ) {}

    public function query()
    {
        return Payment::with(['user', 'paper', 'registrationPackage'])
            ->when($this->typeFilter, fn($q) => $q->where('type', $this->typeFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest();
    }

    public function headings(): array
    {
        return [
            'No',
            'Invoice',
            'Tipe',
            'Nama',
            'Email',
            'Institusi',
            'Judul Paper / Keterangan',
            'Jumlah (Rp)',
            'Metode Bayar',
            'Status',
            'Tanggal Upload',
            'Tanggal Verifikasi',
            'Catatan Admin',
        ];
    }

    /** @param Payment $payment */
    public function map($payment): array
    {
        $this->rowNumber++;

        $statusLabel = match ($payment->status) {
            'verified'  => 'Lunas',
            'uploaded'  => 'Sudah Upload',
            'rejected'  => 'Ditolak',
            default     => 'Pending',
        };

        $typeLabel = $payment->type === 'participant' ? 'Partisipan' : 'Paper';

        $keterangan = $payment->type === 'participant'
            ? 'Registrasi Partisipan'
            : ($payment->paper->title ?? '-');

        return [
            $this->rowNumber,
            $payment->invoice_number,
            $typeLabel,
            $payment->user->name ?? '-',
            $payment->user->email ?? '-',
            $payment->user->institution ?? '-',
            $keterangan,
            (float) $payment->amount,
            $payment->payment_method ?? '-',
            $statusLabel,
            $payment->paid_at?->format('d/m/Y H:i') ?? '-',
            $payment->verified_at?->format('d/m/Y H:i') ?? '-',
            $payment->admin_notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Header row style
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2563EB'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Pembayaran';
    }
}
