<?php
/**
 * Created by PhpStorm.
 * User: Geethu
 * Date: 4/21/2017
 * Time: 15:45
 */

namespace App\Mails;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewContactToSales extends Mailable
{
    use Queueable, SerializesModels;


    public $customerName;
    public $customerEmail;
    public $subject;
    public $body;

    /**
     * Create a new message instance.
     *
     * @param $customerName
     * @param $customerEmail
     * @param $subject
     * @param $message
     * @internal param \App\Lead $lead
     */
    public function __construct($customerName, $customerEmail, $subject, $message)
    {
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
        $this->subject = $subject;
        $this->body = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject ? $this->subject : 'Contact message')->view('emails.contact_to_sales');
    }
}