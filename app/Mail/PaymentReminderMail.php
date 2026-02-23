<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $invoiceNumber;
    public int|float $amount;
    public string $paymentUrl;
    public string $loginUrl;
    public ?int $conferenceId;

    public function __construct(
        string $userName,
        string $invoiceNumber,
        int|float $amount,
        string $paymentUrl,
        ?int $conferenceId = null
    ) {
        $this->userName     = $userName;
        $this->invoiceNumber = $invoiceNumber;
        $this->amount       = $amount;
        $this->paymentUrl   = $paymentUrl;
        $this->loginUrl     = route('login');
        $this->conferenceId = $conferenceId;
    }

    public function build(): static
    {
        $tpl = EmailTemplate::forConference($this->conferenceId, 'payment_reminder');
        if ($tpl) {
            $vars = [
                'name'        => $this->userName,
                'invoice'     => $this->invoiceNumber,
                'amount'      => number_format($this->amount, 0, ',', '.'),
                'payment_url' => $this->paymentUrl,
            ];
            return $this
                ->subject($tpl->renderSubject($vars))
                ->html($tpl->render($vars));
        }

        return $this->subject('⏰ Pengingat: Segera Upload Bukti Pembayaran — ' . config('app.name'))
                    ->view('emails.payment-reminder');
    }
}
