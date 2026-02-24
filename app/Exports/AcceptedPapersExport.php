<?php

namespace App\Exports;

use App\Models\Paper;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AcceptedPapersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    private int $rowNumber = 0;

    public function __construct(
        private ?int $conferenceId = null,
        private ?string $status    = null
    ) {}

    public function query()
    {
        return Paper::with(['user', 'conference'])
            ->when($this->conferenceId, fn($q) => $q->where('conference_id', $this->conferenceId))
            ->when($this->status,       fn($q) => $q->where('status', $this->status))
            ->orderBy('created_at');
    }

    public function headings(): array
    {
        return ['No', 'Judul', 'Penulis', 'Email', 'Institusi', 'Topik', 'Status', 'Similarity (%)', 'Konferensi', 'Tanggal Submit'];
    }

    public function map($paper): array
    {
        return [
            ++$this->rowNumber,
            $paper->title,
            $paper->user?->name,
            $paper->user?->email,
            $paper->user?->institution,
            $paper->topic,
            $paper->status_label,
            $paper->similarity_score,
            $paper->conference?->name,
            $paper->submitted_at?->format('d/m/Y') ?? $paper->created_at->format('d/m/Y'),
        ];
    }

    public function title(): string { return 'Paper'; }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '059669']], 'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]]];
    }
}
