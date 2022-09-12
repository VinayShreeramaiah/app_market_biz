<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class NewLeadMailToSales extends Mailable {
	use Queueable, SerializesModels;

	public $lead;
	public $report;

	/**
	 * Create a new message instance.
	 *
	 * @param \App\Lead $lead
	 */
	public function __construct( \App\Lead $lead, \App\Report $report ) {
		$this->lead   = $lead;
		$this->report = $report;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this
			->subject( 'LEAD: ' . ($this->report->meta->upcoming_report ? "Pre-book " : ""). $this->lead->meta->formtype . ' - ' . $this->report->meta->full_title )
			->view( 'emails.newlead_to_sales' );
	}
}