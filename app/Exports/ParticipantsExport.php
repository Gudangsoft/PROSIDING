<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    private int $rowNumber = 0;

    public function __construct(private ?int $conferenceId = null) {}

    public function query()
    {
        return User::query()
            ->whereIn('role', ['author', 'participant'])
            ->when($this->conferenceId, fn($q) => $q->whereHas('papers', fn($p) => $p->where('conference_id', $this->conferenceId)))
            ->orderBy('name');
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'Email', 'Institusi', 'Role', 'Negara', 'Telepon', 'Terdaftar'];
    }

    public function map($user): array
    {
        return [
            ++$this->rowNumber,
            $user->name,
            $user->email,
            $user->institution,
            ucfirst($user->role),
            $user->country,
            $user->phone,
            $user->created_at?->format('d/m/Y'),
        ];
    }

    public function title(): string { return 'Peserta'; }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1D4ED8']], 'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]]];
    }
}
