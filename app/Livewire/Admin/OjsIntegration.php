<?php

namespace App\Livewire\Admin;

use App\Models\Conference;
use App\Models\Paper;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class OjsIntegration extends Component
{
    public int $conferenceId = 0;
    public string $activeTab = 'settings';
    public string $error = '';
    public string $connectionStatus = '';

    // OJS Settings
    public string $ojsUrl = '';
    public string $ojsApiKey = '';
    public string $ojsJournalId = '';
    public string $doiPrefix = '';

    // Paper actions
    public string $search = '';
    public string $ojsStatus = ''; // filter: submitted / pending

    public function mount(): void
    {
        $this->loadConf();
    }

    public function updatedConferenceId(): void
    {
        $this->loadConf();
    }

    protected function loadConf(): void
    {
        if (!$this->conferenceId) return;
        $conf = Conference::find($this->conferenceId);
        if (!$conf) return;

        $this->ojsUrl       = $conf->ojs_url ?? '';
        $this->ojsApiKey    = $conf->ojs_api_key ?? '';
        $this->ojsJournalId = $conf->ojs_journal_id ?? '';
        $this->doiPrefix    = $conf->doi_prefix ?? '';
    }

    public function saveSettings(): void
    {
        $this->validate([
            'conferenceId' => 'required|integer|min:1',
            'ojsUrl'       => 'nullable|url|max:500',
            'doiPrefix'    => 'nullable|string|max:50',
        ], [
            'conferenceId.min' => 'Pilih konferensi.',
            'ojsUrl.url'       => 'URL OJS tidak valid.',
        ]);

        Conference::where('id', $this->conferenceId)->update([
            'ojs_url'        => $this->ojsUrl ?: null,
            'ojs_api_key'    => $this->ojsApiKey ?: null,
            'ojs_journal_id' => $this->ojsJournalId ?: null,
            'doi_prefix'     => $this->doiPrefix ?: null,
        ]);

        session()->flash('success', 'Pengaturan OJS/DOI berhasil disimpan!');
    }

    public function submitToOjs(int $paperId): void
    {
        $conf = Conference::find($this->conferenceId);
        if (!$conf?->ojs_url || !$conf?->ojs_api_key) {
            session()->flash('error', 'Harap isi URL dan API Key OJS terlebih dahulu.');
            return;
        }

        $paper = Paper::with('user')->findOrFail($paperId);

        try {
            $payload = [
                'title'    => [['locale' => 'en_US', 'value' => $paper->title]],
                'abstract' => [['locale' => 'en_US', 'value' => $paper->abstract]],
                'keywords' => [['locale' => 'en_US', 'value' => explode(',', $paper->keywords ?? '')]],
                'sectionId' => 1,
            ];

            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->timeout(15)->post(
                rtrim($conf->ojs_url, '/') . '/api/v1/submissions?apiToken=' . urlencode($conf->ojs_api_key),
                $payload
            );

            if ($response->successful()) {
                $ojsId = $response->json('id');
                $paper->update([
                    'ojs_submission_id' => $ojsId,
                    'ojs_status'        => 'submitted',
                    'ojs_submitted_at'  => now(),
                ]);
                \App\Models\PaperStatusLog::log($paper->id, $paper->status, $paper->status, 'ojs_submitted', 'Dikirim ke OJS: ID ' . $ojsId);
                session()->flash('success', "Paper berhasil dikirim ke OJS (ID: {$ojsId})");
            } else {
                session()->flash('error', 'OJS error: ' . $response->status() . ' – ' . $response->body());
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Koneksi ke OJS gagal: ' . $e->getMessage());
        }
    }

    public function generateDoi(int $paperId): void
    {
        $conf = Conference::find($this->conferenceId);
        if (!$conf?->doi_prefix) {
            session()->flash('error', 'Harap isi DOI Prefix terlebih dahulu.');
            return;
        }

        $paper = Paper::findOrFail($paperId);
        if ($paper->doi) {
            session()->flash('error', 'Paper sudah memiliki DOI: ' . $paper->doi);
            return;
        }

        $suffix = now()->format('Y') . '.' . str_pad($paper->id, 6, '0', STR_PAD_LEFT);
        $doi    = $conf->doi_prefix . '/' . $suffix;
        $paper->update(['doi' => $doi]);
        session()->flash('success', 'DOI berhasil digenerate: ' . $doi);
    }

    public function testConnection(): void
    {
        $this->error = '';
        $this->connectionStatus = '';

        if (!$this->ojsUrl) {
            $this->error = 'URL OJS belum diisi.';
            return;
        }

        try {
            $base = rtrim($this->ojsUrl, '/');
            $url  = $base . '/api/v1/submissions?apiToken=' . urlencode($this->ojsApiKey);

            $response = Http::timeout(10)->get($url);

            if ($response->successful()) {
                $this->connectionStatus = '✅ Koneksi OJS berhasil! API Key valid.';
            } elseif ($response->status() === 403 || $response->status() === 401) {
                $this->error = '❌ API Key tidak valid atau tidak punya akses. Periksa kembali API Key di profil OJS Anda.';
            } else {
                $this->error = '❌ OJS HTTP ' . $response->status() . ': ' . $response->body();
            }
        } catch (\Exception $e) {
            $this->error = '❌ Tidak dapat terhubung ke OJS: ' . $e->getMessage();
        }
    }

    public function render()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name', 'ojs_url', 'doi_prefix']);

        $papers = collect();
        if ($this->conferenceId) {
            $papers = Paper::where('conference_id', $this->conferenceId)
                ->whereIn('status', ['accepted', 'payment_verified', 'deliverables_pending', 'completed'])
                ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
                ->when($this->ojsStatus, fn($q) => $q->where('ojs_status', $this->ojsStatus))
                ->with('user:id,name')
                ->orderBy('title')
                ->paginate(20);
        }

        return view('livewire.admin.ojs-integration', compact('conferences', 'papers'))
            ->layout('layouts.app', ['title' => 'OJS / DOI Integration']);
    }
}
