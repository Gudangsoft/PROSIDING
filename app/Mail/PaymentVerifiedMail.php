<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentVerifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $invoiceNumber;
    public int|float $amount;
    public string $paymentType; // 'paper' or 'participant'
    public ?string $paperTitle;
    public string $dashboardUrl;
    public string $loginUrl;
    public ?int $conferenceId;
    public ?string $waGroupLink;

    public function __construct(
        string $userName,
        string $invoiceNumber,
        int|float $amount,
        string $paymentType,
        ?string $paperTitle,
        string $dashboardUrl,
        ?int $conferenceId = null,
        ?string $waGroupLink = null
    ) {
        $this->userName     = $userName;
        $this->invoiceNumber = $invoiceNumber;
        $this->amount       = $amount;
        $this->paymentType  = $paymentType;
        $this->paperTitle   = $paperTitle;
        $this->dashboardUrl = $dashboardUrl;
        $this->loginUrl     = route('login');
        $this->conferenceId = $conferenceId;
        $this->waGroupLink  = $waGroupLink;
    }

    public function build(): static
    {
        $tpl = EmailTemplate::forConference($this->conferenceId, 'payment_verified');
        if ($tpl) {
            $vars = [
                'name'          => $this->userName,
                'invoice'       => $this->invoiceNumber,
                'amount'        => number_format($this->amount, 0, ',', '.'),
                'dashboard_url' => $this->dashboardUrl,
                'wa_group_link' => $this->waGroupLink ?? '',
            ];
            return $this
                ->subject($tpl->renderSubject($vars))
                ->html($tpl->render($vars));
        }

        return $this->subject('✅ Pembayaran Terverifikasi — ' . config('app.name'))
                    ->view('emails.payment-verified');
    }
}
