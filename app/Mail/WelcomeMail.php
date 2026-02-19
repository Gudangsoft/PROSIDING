<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userRole;
    public $dashboardUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $userRole, $dashboardUrl)
    {
        $this->userName = $userName;
        $this->userRole = $userRole;
        $this->dashboardUrl = $dashboardUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Selamat Datang di ' . config('app.name'))
                    ->view('emails.welcome');
    }
}
