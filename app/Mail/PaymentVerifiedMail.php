<?php

namespace App\Mail;

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

    public function __construct(
        string $userName,
        string $invoiceNumber,
        int|float $amount,
        string $paymentType,
        ?string $paperTitle,
        string $dashboardUrl
    ) {
        $this->userName     = $userName;
        $this->invoiceNumber = $invoiceNumber;
        $this->amount       = $amount;
        $this->paymentType  = $paymentType;
        $this->paperTitle   = $paperTitle;
        $this->dashboardUrl = $dashboardUrl;
        $this->loginUrl     = route('login');
    }

    public function build(): static
    {
        return $this->subject('✅ Pembayaran Terverifikasi — ' . config('app.name'))
                    ->view('emails.payment-verified');
    }
}
