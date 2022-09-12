<?php
/**
 * Created by PhpStorm.
 * User: Geethu
 * Date: 6/14/2017
 * Time: 10:50
 */

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentMailToSales extends Mailable {
	use Queueable, SerializesModels;

	public $sale;
	public $report;

	/**
	 * Create a new message instance.
	 * @internal param Lead $lead
	 *
	 * @param Sale $sale
	 * @param Report $report
	 */
	public function __construct( \App\Sale $sale, \App\Report $report ) {
		$this->sale   = $sale;
		$this->report = $report;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this
			->subject( 'QYResearch.us Team: ' . $this->report->meta->full_title )
			->view( 'emails.payment_to_sales' );
	}
}