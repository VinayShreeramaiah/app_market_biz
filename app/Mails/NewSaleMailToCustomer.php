<?php

namespace App\Mails;

use App\Report;
use App\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class NewSaleMailToCustomer extends Mailable {
	use Queueable, SerializesModels;

	public $sale;
	public $report;
	public $payment_type;

	/**
	 * Create a new message instance.
	 *
	 * @param Sale $sale
	 * @param Report $report
	 * @param payment_type
	 */
	public function __construct( Sale $sale, Report $report, $payment_type = null ) {
		$this->sale   = $sale;
		$this->report = $report;
		$this->payment_type = $payment_type;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this
			->subject( 'Market.biz Team: ' . $this->report->meta->full_title )
			->view( 'emails.newsale_to_customer' );
	}
}