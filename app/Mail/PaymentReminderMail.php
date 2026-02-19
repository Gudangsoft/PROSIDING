<?php

namespace App\Mail;

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

    public function __construct(
        string $userName,
        string $invoiceNumber,
        int|float $amount,
        string $paymentUrl
    ) {
        $this->userName     = $userName;
        $this->invoiceNumber = $invoiceNumber;
        $this->amount       = $amount;
        $this->paymentUrl   = $paymentUrl;
        $this->loginUrl     = route('login');
    }

    public function build(): static
    {
        return $this->subject('⏰ Pengingat: Segera Upload Bukti Pembayaran — ' . config('app.name'))
                    ->view('emails.payment-reminder');
    }
}
