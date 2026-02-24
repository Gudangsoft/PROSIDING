<?php

namespace App\Livewire\Admin;

use App\Models\WhatsappSetting;
use App\Models\WhatsappLog;
use App\Models\User;
use App\Models\Paper;
use App\Models\Payment;
use App\Services\WhatsappService;
use Livewire\Component;
use Livewire\WithPagination;

class WhatsappManager extends Component
{
    use WithPagination;

    // ─── Settings ────────────────────────────────────────────────────
    public ?int $editId = null;
    public bool $showSettingModal = false;

    public string $settingName      = 'default';
    public string $provider         = 'fonnte';
    public string $apiKey           = '';
    public string $apiUrl           = '';
    public string $deviceId         = '';
    public string $senderNumber     = '';
    public string $testNumber       = '';
    public bool   $isActive         = false;

    // Feature toggles
    public bool $notifyPaymentReminder = true;
    public bool $notifyPaymentVerified = true;
    public bool $notifyPaperStatus     = true;
    public bool $notifyReviewAssigned  = true;
    public bool $notifyAbstractStatus  = true;

    // ─── Templates ───────────────────────────────────────────────────
    public bool $showTemplateModal  = false;
    public string $editingTemplateKey = '';
    public string $editingTemplateValue = '';

    // ─── Broadcast ───────────────────────────────────────────────────
    public bool $showBroadcastModal = false;
    public string $broadcastTargetRole = 'author';
    public string $broadcastMessage    = '';
    public ?int $broadcastConferenceId = null;

    // ─── Send to single user ─────────────────────────────────────────
    public bool $showSendModal = false;
    public string $sendToNumber  = '';
    public string $sendMessage   = '';
    public string $sendToName    = '';

    // ─── Tab ─────────────────────────────────────────────────────────
    public string $activeTab = 'settings';
    public string $logSearch = '';
    public string $logType   = '';
    public string $logStatus = '';

    // ─── Payment reminder target ─────────────────────────────────────
    public bool $showReminderModal = false;
    public int $reminderConferenceId = 0;
    public string $reminderStatus = 'payment_pending';

    protected $queryString = ['activeTab'];

    // ═════════════════════════════════════════════════════════════════
    //  SETTING CRUD
    // ═════════════════════════════════════════════════════════════════

    public function openCreate(): void
    {
        $this->resetSettingForm();
        $this->editId = null;
        $this->showSettingModal = true;
    }

    public function openEdit(int $id): void
    {
        $s = WhatsappSetting::findOrFail($id);
        $this->editId         = $s->id;
        $this->settingName    = $s->name;
        $this->provider       = $s->provider;
        $this->apiKey         = $s->api_key ?? '';
        $this->apiUrl         = $s->api_url ?? '';
        $this->deviceId       = $s->device_id ?? '';
        $this->senderNumber   = $s->sender_number ?? '';
        $this->testNumber     = $s->test_number ?? '';
        $this->isActive       = (bool) $s->is_active;

        $this->notifyPaymentReminder = (bool) $s->notify_payment_reminder;
        $this->notifyPaymentVerified = (bool) $s->notify_payment_verified;
        $this->notifyPaperStatus     = (bool) $s->notify_paper_status;
        $this->notifyReviewAssigned  = (bool) $s->notify_review_assigned;
        $this->notifyAbstractStatus  = (bool) $s->notify_abstract_status;

        $this->showSettingModal = true;
    }

    public function saveSetting(): void
    {
        $this->validate([
            'settingName' => 'required|string|max:100',
            'provider'    => 'required|in:fonnte,wablas,custom',
            'apiKey'      => 'required|string',
            'senderNumber'=> 'nullable|string|max:30',
            'testNumber'  => 'nullable|string|max:20',
        ]);

        // If setting this as active, deactivate others first
        if ($this->isActive) {
            WhatsappSetting::where('id', '!=', $this->editId ?? 0)->update(['is_active' => false]);
        }

        $data = [
            'name'          => $this->settingName,
            'provider'      => $this->provider,
            'api_key'       => $this->apiKey,
            'api_url'       => $this->apiUrl ?: null,
            'device_id'     => $this->deviceId ?: null,
            'sender_number' => $this->senderNumber ?: null,
            'test_number'   => $this->testNumber ?: null,
            'is_active'     => $this->isActive,
            'notify_payment_reminder' => $this->notifyPaymentReminder,
            'notify_payment_verified' => $this->notifyPaymentVerified,
            'notify_paper_status'     => $this->notifyPaperStatus,
            'notify_review_assigned'  => $this->notifyReviewAssigned,
            'notify_abstract_status'  => $this->notifyAbstractStatus,
        ];

        if ($this->editId) {
            WhatsappSetting::findOrFail($this->editId)->update($data);
            session()->flash('success', 'Konfigurasi WhatsApp diperbarui.');
        } else {
            WhatsappSetting::create($data);
            session()->flash('success', 'Konfigurasi WhatsApp ditambahkan.');
        }

        $this->showSettingModal = false;
        $this->resetSettingForm();
    }

    public function deleteSetting(int $id): void
    {
        WhatsappSetting::findOrFail($id)->delete();
        session()->flash('success', 'Konfigurasi dihapus.');
    }

    public function toggleActive(int $id): void
    {
        $s = WhatsappSetting::findOrFail($id);
        if (!$s->is_active) {
            WhatsappSetting::query()->update(['is_active' => false]);
        }
        $s->update(['is_active' => !$s->is_active]);
    }

    // ═════════════════════════════════════════════════════════════════
    //  TEST SEND
    // ═════════════════════════════════════════════════════════════════

    public function testSend(int $settingId): void
    {
        $s = WhatsappSetting::findOrFail($settingId);
        if (!$s->test_number) {
            session()->flash('error', 'Isi nomor test terlebih dahulu.');
            return;
        }

        $svc = new WhatsappService($s);
        $ok  = $svc->send(
            $s->test_number,
            "✅ Ini adalah pesan test dari sistem prosiding.\nKonfigurasi WhatsApp API aktif dengan provider: *{$s->provider_label}*",
            'test'
        );

        session()->flash($ok ? 'success' : 'error', $ok
            ? "Pesan test berhasil dikirim ke {$s->test_number}."
            : "Gagal mengirim pesan test. Periksa API key dan provider."
        );
    }

    // ═════════════════════════════════════════════════════════════════
    //  TEMPLATE EDITOR
    // ═════════════════════════════════════════════════════════════════

    public function openTemplate(string $key): void
    {
        $setting = WhatsappSetting::active();
        $this->editingTemplateKey   = $key;
        $this->editingTemplateValue = $setting?->$key
            ?? WhatsappSetting::DEFAULT_TEMPLATES[$key]
            ?? '';
        $this->showTemplateModal = true;
    }

    public function saveTemplate(): void
    {
        $setting = WhatsappSetting::active();
        if (!$setting) {
            session()->flash('error', 'Aktifkan salah satu konfigurasi terlebih dahulu.');
            $this->showTemplateModal = false;
            return;
        }

        $setting->update([$this->editingTemplateKey => $this->editingTemplateValue]);
        $this->showTemplateModal = false;
        session()->flash('success', 'Template pesan disimpan.');
    }

    public function resetTemplate(string $key): void
    {
        $setting = WhatsappSetting::active();
        if ($setting && isset(WhatsappSetting::DEFAULT_TEMPLATES[$key])) {
            $setting->update([$key => WhatsappSetting::DEFAULT_TEMPLATES[$key]]);
            session()->flash('success', 'Template dikembalikan ke default.');
        }
    }

    // ═════════════════════════════════════════════════════════════════
    //  SEND TO SINGLE NUMBER
    // ═════════════════════════════════════════════════════════════════

    public function sendManual(): void
    {
        $this->validate([
            'sendToNumber' => 'required|string',
            'sendMessage'  => 'required|string',
        ]);

        $svc = new WhatsappService();
        $ok  = $svc->send($this->sendToNumber, $this->sendMessage, 'manual', null, null, $this->sendToName ?: null);

        $this->showSendModal = false;
        $this->sendToNumber  = '';
        $this->sendMessage   = '';
        $this->sendToName    = '';

        session()->flash($ok ? 'success' : 'error', $ok
            ? 'Pesan berhasil dikirim.'
            : 'Gagal mengirim pesan. Periksa konfigurasi API.'
        );
    }

    // ═════════════════════════════════════════════════════════════════
    //  PAYMENT REMINDERS (BATCH)
    // ═════════════════════════════════════════════════════════════════

    public function sendPaymentReminders(): void
    {
        $papers = Paper::with(['user', 'payment', 'conference'])
            ->where('status', $this->reminderStatus)
            ->when($this->reminderConferenceId, fn($q) => $q->where('conference_id', $this->reminderConferenceId))
            ->whereHas('user', fn($q) => $q->whereNotNull('phone'))
            ->get();

        if ($papers->isEmpty()) {
            session()->flash('error', 'Tidak ada data yang memenuhi filter.');
            $this->showReminderModal = false;
            return;
        }

        $svc   = new WhatsappService();
        $count = 0;

        foreach ($papers as $paper) {
            $user    = $paper->user;
            $payment = $paper->payment;

            $ok = $svc->sendTemplate('tpl_payment_reminder', $user->phone, [
                'nama'        => $user->name,
                'judul_paper' => $paper->title,
                'konferensi'  => $paper->conference?->name ?? '-',
                'nominal'     => $payment ? number_format($payment->amount, 0, ',', '.') : '0',
                'deadline'    => $paper->conference?->paymentDeadlineDate ?? '-',
            ], 'payment_reminder', $user->id, $paper->id, $user->name);

            if ($ok) {
                $count++;
            }

            usleep(500000); // 0.5s delay per message to avoid rate limits
        }

        $this->showReminderModal = false;
        session()->flash('success', "Berhasil mengirim {$count} reminder pembayaran dari {$papers->count()} target.");
    }

    // ═════════════════════════════════════════════════════════════════
    //  LOG MANAGEMENT
    // ═════════════════════════════════════════════════════════════════

    public function clearLogs(): void
    {
        WhatsappLog::where('created_at', '<', now()->subDays(30))->delete();
        session()->flash('success', 'Log lebih dari 30 hari dihapus.');
    }

    public function deleteLog(int $id): void
    {
        WhatsappLog::findOrFail($id)->delete();
    }

    // ═════════════════════════════════════════════════════════════════
    //  PRIVATE HELPERS
    // ═════════════════════════════════════════════════════════════════

    private function resetSettingForm(): void
    {
        $this->editId       = null;
        $this->settingName  = 'default';
        $this->provider     = 'fonnte';
        $this->apiKey       = '';
        $this->apiUrl       = '';
        $this->deviceId     = '';
        $this->senderNumber = '';
        $this->testNumber   = '';
        $this->isActive     = false;
        $this->notifyPaymentReminder = true;
        $this->notifyPaymentVerified = true;
        $this->notifyPaperStatus     = true;
        $this->notifyReviewAssigned  = true;
        $this->notifyAbstractStatus  = true;
    }

    public function getTemplateVars(string $key): array
    {
        $varMap = [
            'tpl_payment_reminder'  => ['nama', 'judul_paper', 'konferensi', 'nominal', 'deadline', 'invoice'],
            'tpl_payment_verified'  => ['nama', 'judul_paper', 'konferensi', 'invoice'],
            'tpl_paper_accepted'    => ['nama', 'judul_paper', 'konferensi'],
            'tpl_paper_rejected'    => ['nama', 'judul_paper', 'konferensi', 'catatan'],
            'tpl_paper_revision'    => ['nama', 'judul_paper', 'konferensi', 'catatan'],
            'tpl_review_assigned'   => ['nama', 'judul', 'konferensi'],
            'tpl_abstract_approved' => ['nama', 'judul_paper', 'konferensi'],
            'tpl_abstract_rejected' => ['nama', 'judul_paper', 'konferensi', 'catatan'],
        ];
        return $varMap[$key] ?? [];
    }

    public function render()
    {
        $settings    = WhatsappSetting::latest()->get();
        $activeSetting = WhatsappSetting::active();

        $logs = WhatsappLog::with(['user', 'paper'])
            ->when($this->logSearch, fn($q) => $q->where(function ($q) {
                $q->where('to', 'like', "%{$this->logSearch}%")
                  ->orWhere('recipient_name', 'like', "%{$this->logSearch}%");
            }))
            ->when($this->logType,   fn($q) => $q->where('type', $this->logType))
            ->when($this->logStatus, fn($q) => $q->where('status', $this->logStatus))
            ->latest()
            ->paginate(25);

        $logStats = [
            'total'  => WhatsappLog::count(),
            'sent'   => WhatsappLog::where('status', 'sent')->count(),
            'failed' => WhatsappLog::where('status', 'failed')->count(),
            'today'  => WhatsappLog::whereDate('created_at', today())->count(),
        ];

        $conferences = \App\Models\Conference::orderBy('name')->get(['id', 'name']);

        return view('livewire.admin.whatsapp-manager', compact(
            'settings', 'activeSetting', 'logs', 'logStats', 'conferences'
        ))->layout('layouts.app', ['title' => 'WhatsApp Notifikasi']);
    }
}
