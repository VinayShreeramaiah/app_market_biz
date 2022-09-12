<?php
/**
 * Created by PhpStorm.
 * User: Geethu
 * Date: 5/24/2017
 * Time: 16:22
 */

namespace App\Mails;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOtherSiteLeadMailToCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $report;

    /**
     * Create a new message instance.
     *
     * @param  $lead
     */
    public function __construct($lead)
    {
        $this->lead = $lead;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(['address' => "inquiry@market.biz", 'name' => 'Market.biz Team'])
            ->subject('Market.biz')
            ->view('emails.new_othersite_lead_to_customer');
    }
}