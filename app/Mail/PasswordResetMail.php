<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $loginUrl)
    {
        $this->userName = $userName;
        $this->loginUrl = $loginUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Password Berhasil Direset - ' . config('app.name'))
                    ->view('emails.password-reset');
    }
}
