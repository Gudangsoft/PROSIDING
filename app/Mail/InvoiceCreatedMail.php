<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $paperTitle;
    public $invoiceNumber;
    public $amount;
    public $paymentUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $paperTitle, $invoiceNumber, $amount, $paymentUrl)
    {
        $this->userName = $userName;
        $this->paperTitle = $paperTitle;
        $this->invoiceNumber = $invoiceNumber;
        $this->amount = $amount;
        $this->paymentUrl = $paymentUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Tagihan Pembayaran - ' . config('app.name'))
                    ->view('emails.invoice-created');
    }
}
