<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewGuestNearMissAwaitingInitialReview extends Mailable
{
    use Queueable, SerializesModels;

    public $data = [];
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
        return $this->markdown('emails.nearmiss.new_guest_awaiting_initial_review')
                    ->subject('Initial Review Required [Near Miss]: '.$this->data['title']);
    }
}
