<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IssueClosed extends Mailable
{
    use Queueable, SerializesModels;

    public $data=array();
    public $subject = "Issue Closed";

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
    public function build(): IssueClosed
    {
        return $this->markdown('emails.action.issueclosed');
    }
}
