<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewEventMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $eventName;
    public $eventDate;
    public $eventUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $eventName, $eventDate, $eventUrl)
    {
        $this->userName = $userName;
        $this->eventName = $eventName;
        $this->eventDate = $eventDate;
        $this->eventUrl = $eventUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Kegiatan Baru: ' . $this->eventName)
                    ->view('emails.new-event');
    }
}
