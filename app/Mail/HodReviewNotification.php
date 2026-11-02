<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HodReviewNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Verify the proposed action information';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data = [];

    /**
     * @param $data
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
        return $this->markdown('emails.internalaudit.hod_review_notification');
    }
}
