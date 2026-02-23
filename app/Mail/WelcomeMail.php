<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userRole;
    public $dashboardUrl;
    public ?int $conferenceId;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $userRole, $dashboardUrl, ?int $conferenceId = null)
    {
        $this->userName = $userName;
        $this->userRole = $userRole;
        $this->dashboardUrl = $dashboardUrl;
        $this->conferenceId = $conferenceId;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $tpl = EmailTemplate::forConference($this->conferenceId, 'welcome');
        if ($tpl) {
            $vars = [
                'name'            => $this->userName,
                'email'           => '',
                'login_url'       => $this->dashboardUrl,
                'dashboard_url'   => $this->dashboardUrl,
            ];
            return $this
                ->subject($tpl->renderSubject($vars))
                ->html($tpl->render($vars));
        }

        return $this->subject('Selamat Datang di ' . config('app.name'))
                    ->view('emails.welcome');
    }
}
