<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class NewLeadMailToCustomer extends  Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $report;

    /**
     * Create a new message instance.
     *
     * @param \App\Lead $lead
     */
    public function __construct(\App\Lead $lead, \App\Report $report)
    {
        $this->lead = $lead;
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
	        ->subject('QYResearch.us Team: ' . $this->report->meta->full_title)
	        ->view('emails.newlead_to_customer');
    }
}