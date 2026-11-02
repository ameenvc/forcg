<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentRemovalRequestUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $removalupdatedata = array();

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($removalupdatedata)
    {
        $this->removalupdatedata = $removalupdatedata;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.document.docremovalrequestupdated');
    }
}
