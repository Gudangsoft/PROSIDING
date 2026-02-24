<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\Paper;
use App\Models\PaperSimilarity;
use Livewire\Component;

class PaperSimilarityCheck extends Component
{
    public int $conferenceId = 0;
    public int $threshold = 30; // % threshold to flag
    public bool $isRunning = false;

    public function runSimilarityCheck(): void
    {
        if (!$this->conferenceId) {
            session()->flash('error', 'Pilih konferensi terlebih dahulu.');
            return;
        }

        $papers = Paper::where('conference_id', $this->conferenceId)
            ->whereNotNull('abstract')
            ->get(['id', 'title', 'abstract']);

        if ($papers->count() < 2) {
            session()->flash('error', 'Minimal 2 paper diperlukan untuk perbandingan.');
            return;
        }

        // Clear old results for this conference
        PaperSimilarity::whereIn('paper_id', $papers->pluck('id'))->delete();

        $count = 0;
        $papersArr = $papers->values()->toArray();
        $n = count($papersArr);

        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $textA = strtolower(strip_tags($papersArr[$i]['title'] . ' ' . $papersArr[$i]['abstract']));
                $textB = strtolower(strip_tags($papersArr[$j]['title'] . ' ' . $papersArr[$j]['abstract']));

                similar_text($textA, $textB, $pct);
                $pct = round($pct, 2);

                if ($pct > 0) {
                    PaperSimilarity::updateOrCreate(
                        [
                            'paper_id'        => $papersArr[$i]['id'],
                            'similar_paper_id' => $papersArr[$j]['id'],
                        ],
                        [
                            'similarity_percent' => $pct,
                            'compared_field'     => 'title_abstract',
                            'checked_at'         => now(),
                        ]
                    );
                    $count++;
                }
            }
        }

        // Update cross_score on each paper (max similarity with other papers)
        foreach ($papers as $paper) {
            $maxSim = PaperSimilarity::where('paper_id', $paper->id)
                ->orWhere('similar_paper_id', $paper->id)
                ->max('similarity_percent');
            $paper->update(['similarity_cross_score' => $maxSim ?? 0]);
        }

        session()->flash('success', "Pengecekan selesai. {$count} pasang paper dibandingkan.");
    }

    public function deleteSimilarity(int $id): void
    {
        PaperSimilarity::findOrFail($id)->delete();
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        $results = collect();
        $paperCount = 0;
        if ($this->conferenceId) {
            $paperCount = Paper::where('conference_id', $this->conferenceId)->count();
            $paperIds = Paper::where('conference_id', $this->conferenceId)->pluck('id');
            $results = PaperSimilarity::with(['paper:id,title,status', 'similarPaper:id,title,status'])
                ->whereIn('paper_id', $paperIds)
                ->where('similarity_percent', '>=', $this->threshold)
                ->orderByDesc('similarity_percent')
                ->get();
        }

        return view('livewire.admin.paper-similarity-check', compact(
            'conferences', 'results', 'paperCount'
        ))->layout('layouts.app', ['title' => 'Cek Kemiripan Paper']);
    }
}
