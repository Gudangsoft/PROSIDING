<?php

namespace App\Services;

use App\Models\WhatsappLog;
use App\Models\WhatsappSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    private ?WhatsappSetting $setting;

    public function __construct(?WhatsappSetting $setting = null)
    {
        $this->setting = $setting ?? WhatsappSetting::active();
    }

    /** Send a WhatsApp message. Returns true on success. */
    public function send(
        string $to,
        string $message,
        string $type = 'manual',
        ?int $userId = null,
        ?int $paperId = null,
        ?string $recipientName = null
    ): bool {
        if (!$this->setting || !$this->setting->is_active || !$this->setting->api_key) {
            return false;
        }

        $to = $this->normalizeNumber($to);
        if (!$to) {
            return false;
        }

        $log = WhatsappLog::create([
            'setting_id'     => $this->setting->id,
            'to'             => $to,
            'recipient_name' => $recipientName,
            'type'           => $type,
            'message'        => $message,
            'status'         => 'pending',
            'user_id'        => $userId,
            'paper_id'       => $paperId,
        ]);

        try {
            $response = match ($this->setting->provider) {
                'fonnte' => $this->sendFonnte($to, $message),
                'wablas' => $this->sendWablas($to, $message),
                default  => $this->sendCustom($to, $message),
            };

            $success = $response['success'] ?? false;

            $log->update([
                'status'       => $success ? 'sent' : 'failed',
                'api_response' => json_encode($response),
                'sent_at'      => now(),
            ]);

            return $success;
        } catch (\Throwable $e) {
            Log::error('WhatsApp send error: ' . $e->getMessage());

            $log->update([
                'status'       => 'failed',
                'api_response' => json_encode(['error' => $e->getMessage()]),
                'sent_at'      => now(),
            ]);

            return false;
        }
    }

    // ──── Provider-specific Senders ──────────────────────────────────

    private function sendFonnte(string $to, string $message): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->setting->api_key,
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target'       => $to,
            'message'      => $message,
            'countryCode'  => '62',
        ]);

        $body = $response->json() ?? [];

        return [
            'success'  => $body['status'] ?? false,
            'response' => $body,
            'http'     => $response->status(),
        ];
    }

    private function sendWablas(string $to, string $message): array
    {
        $endpoint = $this->setting->api_url ?: 'https://solo.wablas.com/api/send-message';

        $response = Http::withHeaders([
            'Authorization' => $this->setting->api_key,
        ])->post($endpoint, [
            'phone'   => $to,
            'message' => $message,
        ]);

        $body = $response->json() ?? [];

        return [
            'success'  => ($body['status'] ?? '') === 'true' || ($body['status'] === true),
            'response' => $body,
            'http'     => $response->status(),
        ];
    }

    private function sendCustom(string $to, string $message): array
    {
        if (!$this->setting->api_url) {
            return ['success' => false, 'error' => 'No API URL configured'];
        }

        $response = Http::withToken($this->setting->api_key)
            ->post($this->setting->api_url, [
                'to'      => $to,
                'message' => $message,
            ]);

        return [
            'success'  => $response->successful(),
            'response' => $response->json() ?? [],
            'http'     => $response->status(),
        ];
    }

    // ──── Template Resolvers ─────────────────────────────────────────

    /**
     * Build and send from a template key, replacing variables.
     */
    public function sendTemplate(
        string $templateKey,
        string $to,
        array $vars,
        string $type,
        ?int $userId = null,
        ?int $paperId = null,
        ?string $recipientName = null
    ): bool {
        if (!$this->setting) {
            return false;
        }

        // Check feature toggle
        $toggle = match ($type) {
            'payment_reminder' => 'notify_payment_reminder',
            'payment_verified' => 'notify_payment_verified',
            'paper_status'     => 'notify_paper_status',
            'review_assigned'  => 'notify_review_assigned',
            'abstract_status'  => 'notify_abstract_status',
            default            => null,
        };

        if ($toggle && !$this->setting->$toggle) {
            return false;
        }

        $template = $this->setting->$templateKey
            ?? WhatsappSetting::DEFAULT_TEMPLATES[$templateKey]
            ?? null;

        if (!$template) {
            return false;
        }

        $message = $this->interpolate($template, $vars);

        return $this->send($to, $message, $type, $userId, $paperId, $recipientName);
    }

    /** Replace {{var}} placeholders */
    private function interpolate(string $template, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $template = str_replace('{' . $key . '}', (string) $value, $template);
        }
        return $template;
    }

    /** Normalize phone number to international format (remove leading 0 → 62) */
    public static function normalizeNumber(string $number): string
    {
        $number = preg_replace('/\D/', '', $number);
        if (!$number) {
            return '';
        }
        if (str_starts_with($number, '62')) {
            return $number;
        }
        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }
        return '62' . $number;
    }
}
