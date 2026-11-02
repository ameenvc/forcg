<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RiskChangeowner extends Mailable
{
    use Queueable, SerializesModels;
    public $riskownerdata = array();

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($riskownerdata)
    {
        $this->riskownerdata = $riskownerdata;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.risk.riskownerchanged');
    }
}
