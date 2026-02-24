<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\Paper;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgramBookGenerator extends Component
{
    public int $conferenceId = 0;
    public string $bookTitle = 'Proceeding Book';
    public string $bookSubtitle = '';
    public bool $includeCover = true;
    public bool $includeToc = true;
    public bool $includeAbstracts = true;
    public bool $includeAuthors = true;
    public bool $includeProgramSchedule = true;
    public string $selectedStatus = 'accepted';
    public string $groupBy = 'topic'; // topic | none

    public function generate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        if (!$this->conferenceId) {
            session()->flash('error', 'Pilih konferensi terlebih dahulu.');
            return back();
        }

        $conference = Conference::with([
            'keynoteSpeakers',
            'importantDates',
            'committees',
        ])->findOrFail($this->conferenceId);

        $papers = Paper::where('conference_id', $this->conferenceId)
            ->where('status', $this->selectedStatus)
            ->with('user')
            ->orderBy($this->groupBy === 'topic' ? 'topic' : 'title')
            ->get();

        $grouped = $this->groupBy === 'topic'
            ? $papers->groupBy('topic')
            : collect(['Semua Paper' => $papers]);

        $data = [
            'conference'           => $conference,
            'papers'               => $papers,
            'grouped'              => $grouped,
            'bookTitle'            => $this->bookTitle ?: $conference->name,
            'bookSubtitle'         => $this->bookSubtitle ?: ($conference->theme ?? ''),
            'includeCover'         => $this->includeCover,
            'includeToc'           => $this->includeToc,
            'includeAbstracts'     => $this->includeAbstracts,
            'includeAuthors'       => $this->includeAuthors,
            'includeProgramSchedule' => $this->includeProgramSchedule,
            'generatedAt'          => now()->translatedFormat('d F Y H:i'),
        ];

        $pdf = Pdf::loadView('pdfs.program-book', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('enable-local-file-access', true);

        $filename = 'proceeding-' . \Illuminate\Support\Str::slug($conference->acronym ?? $conference->name) . '-' . now()->format('Ymd') . '.pdf';

        return response()->streamDownload(
            fn() => print($pdf->output()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name', 'acronym']);

        $paperCount = 0;
        if ($this->conferenceId) {
            $paperCount = Paper::where('conference_id', $this->conferenceId)
                ->where('status', $this->selectedStatus)
                ->count();
        }

        return view('livewire.admin.program-book-generator', compact('conferences', 'paperCount'))
            ->layout('layouts.app', ['title' => 'Program Book Generator']);
    }
}
