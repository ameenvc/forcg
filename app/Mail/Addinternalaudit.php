<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Addinternalaudit extends Mailable
{
    use Queueable, SerializesModels;

    public $data=array();
    public $subject = "Add Audit Schedule";

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
    public function build():Addinternalaudit
    {
        //return $this->from($address = 'system@quickqm.com', $name = $this->data['company'] . '/cgbod')->markdown('emails.internalaudit.addinternalaudit');
        return $this->markdown('emails.internalaudit.addinternalaudit');
    }
}
