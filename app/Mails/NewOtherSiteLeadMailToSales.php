<?php
/**
 * Created by PhpStorm.
 * User: Geethu
 * Date: 5/24/2017
 * Time: 16:04
 */

namespace App\Mails;


use App\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOtherSiteLeadMailToSales extends  Mailable {
	use Queueable, SerializesModels;

	public $lead;

	/**
	 * Create a new message instance.
	 *
	 * @param  $lead
	 */
	public function __construct(Lead $lead ) {
		$this->lead   = $lead;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this
			->subject( 'LEAD: ' . $this->lead->meta->formtype)
			->view( 'emails.new_othersite_lead_to_sales' );
	}
}