<?php
/**
 * Created by PhpStorm.
 * User: Geethu
 * Date: 4/17/2017
 * Time: 09:45
 */

namespace App\Mails;


use App\Report;
use App\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSaleMailToSales extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;
    public $report;

    /**
     * NewSaleMailToSales constructor.
     * @param Sale $sale
     * @param Report $report
     */
    public function __construct(Sale $sale, Report $report)
    {
        $this->sale = $sale;
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
		    ->subject( 'SALE: ' . $this->report->meta->full_title )
		    ->view( 'emails.newsale_to_sales' );
    }
}