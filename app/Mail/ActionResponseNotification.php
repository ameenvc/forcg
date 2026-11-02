<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActionResponseNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $data = array();
    public $subject = "Add Audit Observation";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): ActionResponseNotification
    {
        //return $this->from($address = 'system@quickqm.com', $name = $this->data['company'] . '/cgbod')->markdown('emails.action.responsenotification');
        return $this->markdown('emails.action.responsenotification');
    }
}
