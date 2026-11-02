<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangeStatusOfCalibration extends Mailable
{
    use Queueable, SerializesModels;

    public $data = array();

    public $subject = 'Calibration Status Update';

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
    public function build()
    {
        return $this->markdown('emails.calibration.changestatustocalibrated');
    }
}
