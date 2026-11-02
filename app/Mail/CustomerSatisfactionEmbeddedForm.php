<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerSatisfactionEmbeddedForm extends Mailable
{
    use Queueable, SerializesModels;

    public $data=array();
    public $subject = "Customer Satisfaction";

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
    public function build(): CustomerSatisfactionEmbeddedForm
    {
        return $this->from($address = 'system@setilas.com', $name = $this->data['company']. '/cgbod')->markdown('emails.customer.survey');
    }
}
