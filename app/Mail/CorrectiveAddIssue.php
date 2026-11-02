<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class CorrectiveAddIssue extends Mailable
{
    use Queueable, SerializesModels;

    public $data=array();
    public $path = "";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    //public function __construct($data, $path)
    public function __construct($data)
    {
        $this->data=$data;
       // $this->path = $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->markdown('emails.corrective.correctiveaddissue')/*->attach($this->path)*/;
    }
}
