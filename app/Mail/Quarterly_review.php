<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Quarterly_review extends Mailable
{
    use Queueable, SerializesModels;
    
    public $da=array();
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($da)
    {
        $this->da=$da;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.compliance.qua_review');
    }
}
