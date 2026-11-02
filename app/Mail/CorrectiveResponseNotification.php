<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CorrectiveResponseNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $data = array();

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        //$this->attachs = $attachs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->markdown('emails.corrective.correctiveresponsenotification');
        // $attachments is an array with file paths of attachments
        // foreach ($this->attachs as $filePath) {
        //     $email->attach($filePath);
        // }
        return $email;
    }
}
