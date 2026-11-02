<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActionProposed extends Mailable
{
    use Queueable, SerializesModels;

    public $data=array();
    public $subject = "Action Proposed";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): ActionProposed
    {
        //return $this->from($address = 'system@setilas.com', $name = $this->data['company'] . '/cgbod')->markdown('emails.action.actionproposed');
        return $this->markdown('emails.action.actionproposed');
    }
}
