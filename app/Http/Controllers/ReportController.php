<?php
/**
 * Created by IntelliJ IDEA.
 * User: nidheeshdas
 * Date: 18/03/17
 * Time: 8:55 PM
 */

namespace App\Http\Controllers;


use App\Discount;
use App\Mails\NewSaleMailToCustomer;
use App\Mails\NewSaleMailToSales;
use App\Mails\PaymentMailToSales;
use App\Report;
use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller {

	public function __construct() {
		session_start();
	}

	public function getReport( Request $request ) {
		$reportId = $request->input( 'reportId' );
		$report   = Report::find( $reportId );
		if ( $report ) {
			return [
//                'report' => $report, // Unnecessary, gives out full report info.
				'long_description'  => $report->meta->long_description,
				'short_description' => $report->meta->short_description,
				'long_toc'          => $report->meta->long_toc,
				'short_toc'         => $report->meta->short_toc,
			];
		} else {
			return Response::create( 'Not found.', 404 );
		}
	}

	public function buyNowData( Request $request ) {
		$data                  = [];
		$data['reportId']      = $request->input( 'reportId' );
		$report                = Report::find( $data['reportId'] );
		$data['licenseType']   = $request->input( 'licenseType', 'single_user' );
		$data['paymentMethod'] = $request->input( 'paymentMethod' );
		$data['report']        = $report->toArray();
		$data['report_title']  = $report->meta->full_title;
		$data['submission']    = [
			'payment_method' => 'PayPal'
		];
		// fetch additional properties needed for rendering the buy now form
		// fetch coupon, discounts or any other server-side data and put in $data
		$isUpcoming  = $report['reportMeta']['upcoming_report'];
		$licenseType = $data['licenseType'];
		if ( isset( $licenseType ) ) {
			$data['price'] = $isUpcoming ? $report['reportMeta'][ 'prebook_' . $licenseType ] : $report['reportMeta'][ 'price_' . $licenseType ];
		}
		$data['singleUserPrice']    = $isUpcoming ? $report['reportMeta']['prebook_single_user'] : $report['reportMeta']['price_single_user'];
		$data['multiUserPrice']     = $isUpcoming ? $report['reportMeta']['prebook_multi_user'] : $report['reportMeta']['price_multi_user'];
		$data['corporateUserPrice'] = $isUpcoming ? $report['reportMeta']['prebook_corporate_user'] : $report['reportMeta']['price_corporate_user'];
		$data['isUpcoming']         = $isUpcoming;
		$coupon                     = $request->input( 'coupon' );
		if ( isset( $coupon ) && is_string( $coupon ) && strlen( $coupon ) > 0 ) {
//			$coupon = pods( 'discount' )->find( [ 'where' => 'coupon.meta_value = "' . $coupon . '"' ] );
			$coupon = Discount::hasMeta( 'coupon', $coupon )->first();

			if ( isset( $coupon ) ) {
//				if ( 0 < $coupon->total() ) {
//					$coupon->fetch();
				$discountType = $coupon->meta->discount_type;
				$reportMeta   = $data['report']['reportMeta'];
				$couponMeta   = [];
				foreach ( $coupon->meta->toArray() as $m ) {
					$couponMeta[ $m['meta_key'] ] = $m['meta_value'];
				}
				$data['coupon']                    = $couponMeta;
				$data['discountedSingleUserPrice'] = $isUpcoming
					? $this->discountedPrice( $reportMeta['prebook_single_user'], $discountType, $coupon->meta->single_user_prebook )
					: $this->discountedPrice( $reportMeta['price_single_user'], $discountType, $coupon->meta->single_user );

				$data['discountedMultiUserPrice'] = $isUpcoming
					? $this->discountedPrice( $reportMeta['prebook_multi_user'], $discountType, $coupon->meta->multi_user_prebook )
					: $this->discountedPrice( $reportMeta['price_multi_user'], $discountType, $coupon->meta->multi_user );

				$data['discountedCorporateUserPrice'] = $isUpcoming
					? $this->discountedPrice( $reportMeta['prebook_corporate_user'], $discountType, $coupon->meta->corporate_user_prebook )
					: $this->discountedPrice( $reportMeta['price_corporate_user'], $discountType, $coupon->meta->corporate_user );
//				}
			}
		}

		return $data;
	}

	public function buyNow( Request $request ) {
		$data                        = $this->buyNowData( $request );
		$_SESSION['buyNowData']      = $data;
		$_SESSION['comingViaReport'] = true;
		$discount = $request->input( 'discount',null );
		$discount_param = $discount ? "&discount=".$discount : "";
		$redirect_url = '//' .$_SERVER['HTTP_HOST']."/purchase-report?report_id=" . $request->input( 'reportId' ).$discount_param;
		return redirect( $redirect_url );
	}

	public function discountedPrice( $price, $discountType, $discount ) {
		if ( $discountType == 'fixed' ) {
			$price = $price - $discount;
		} else if ( $discountType == 'percent' ) {
			$price = $price * ( 1.0 - ( $discount / 100.0 ) );
		}

		$price = number_format( $price, 2, '.', '' );

		return $price;
	}

	public function pay( Request $request ) {
//		print_r( '<pre>' );
		$form                   = $request->input();
		$_SESSION['submission'] = $form;
		$_SESSION['errors']     = [];
		$reportId               = $request->input( 'reportId' ); // $_SESSION['buyNowData']['reportId'];
		$report                 = Report::find( $reportId );
		$is_upcoming_report     = $report->meta->upcoming_report;
		$coupon                 = $request->input( 'coupon' );
		$domain                 = 'marketresearch.biz';
		$nonce = $request->input('pcaptcha_nonce' , null);
		$answer = $request->input('pcaptcha_answer', null);
		$messages = [
			'g-recaptcha-response.required' => 'The Captcha field is required.',
		];

		$validator = \Validator::make( $request->all(), [
			'first_name'           => 'required',
			'last_name'            => 'required',
			'email'                => 'required|email',
			'contact_number'       => 'required',
			'address'              => 'required',
			'city'                 => 'required',
			'state'                => 'required',
			'country'              => 'required',
			'license_type'         => 'required',
			'payment_method'       => 'required',
			'g-recaptcha-response' => !$answer ? 'required' : "",
		], $messages );


		if ( $validator->fails() ) {
			$_SESSION['errors'] = $validator->errors()->toArray();//json_decode( $e->getResponse()->getContent(), true );

			return redirect( $request->headers->get( 'referer' ) );
		}

		if($nonce) {
			$validation = $request->input('pcaptcha_validation', null);
			$url = "https://tools.market.biz/verify_captcha.php";
			$response = \Httpful\Request::post($url)->body([
				'pcaptcha_validation' => $validation,
				'pcaptcha_nonce' => $nonce,
				'pcaptcha_answer' => $answer
			], \Httpful\Mime::FORM)->followRedirects( true )->send();
			$res      = json_decode( $response->raw_body );
			if ( !$res || $res->status == 'error' ) {
				$_SESSION['errors'] = [ "recaptcha" => array( "Incorrect CAPTCHA" ) ];
				return redirect( $request->headers->get( 'referer' ) );
			}
		}else {
			//        recatcha verification
			$secret   = "6LfZVR0UAAAAAL1EwEphYzUSGwimJxhrBEo5nn39";
			$url      = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $request->input( 'g-recaptcha-response' );
			$response = \Httpful\Request::post( $url )
			                            ->send();
			$res      = json_decode( $response->raw_body );
			if ( ! $res->success ) {
				$_SESSION['errors'] = [ "recaptcha" => array( "Incorrect CAPTCHA" ) ];
				return redirect( $request->headers->get( 'referer' ) );
			}
		}

		$licence_type     = $request->input( 'license_type' );
		$sale             = new Sale;
		$sale->post_title = "sale_" . $request->input( 'first_name' ) . " "
		                    . $request->input( 'last_name' ) . ( $reportId ? "_" . $reportId . "_" : '' )
		                    . $request->input( 'payment_method' );

		$reportDetails = $this->getReportDetails( $form['report_url'] );
		$amount        = 0;

		if ( ! $is_upcoming_report ) {
			if ( $licence_type == "single_user" ) {
				$amount = $report->meta->price_single_user;
			} elseif ( $licence_type == "multi_user" ) {
				$amount = $report->meta->price_multi_user;
			} elseif ( $licence_type == "corporate_user" ) {
				$amount = $report->meta->price_corporate_user;
			}
		} else {
			if ( $licence_type == "single_user" ) {
				$amount = $report->meta->prebook_single_user;
			} elseif ( $licence_type == "multi_user" ) {
				$amount = $report->meta->prebook_multi_user;
			} elseif ( $licence_type == "corporate_user" ) {
				$amount = $report->meta->prebook_corporate_user;
			}
		}

		//Coupon
		if ( isset( $coupon ) && is_string( $coupon ) && strlen( $coupon ) > 0 ) {
			$coupon = Discount::hasMeta( 'coupon', $coupon )->first();

			if ( isset( $coupon ) ) {
				$discountType        = $coupon->meta->discount_type;
				$coupon_licence_type = $licence_type . ( $is_upcoming_report ? '_prebook' : '' );
				$discount            = $coupon->meta->$coupon_licence_type;
				$discounted_price    = $this->discountedPrice( $amount, $discountType, $discount );
				if ( $discounted_price != null && $discounted_price >= 0 ) {
					$amount                          = $discounted_price;
					$sale->meta->discount            = $coupon->ID;
					$sale->meta->coupon              = $coupon->meta->coupon;
					$sale->meta->discount_type       = $discountType;
					$sale->meta->discount_amount     = $discount;
					$sale->meta->coupon_licence_type = $coupon_licence_type;
				}
			}
		}
		$sale->post_type = 'sale';
		$sale->save();
		$sale->meta->base_url         = 'https://marketresearch.biz/';
		$sale->meta->team_name        = 'MarketResearch.biz Team';
		$sale->meta->financial_status = "pending";
		$sale->meta->domain           = $domain;
		$sale->meta->reportId         = $reportId;
		$sale->meta->first_name       = $request->input( 'first_name' );
		$sale->meta->last_name        = $request->input( 'last_name' );
		$sale->meta->email            = $request->input( 'email' );
		$sale->meta->phone            = $request->input( 'contact_number' );
		$sale->meta->designation      = $request->input( 'designation' );
		$sale->meta->company          = $request->input( 'company' );
		$sale->meta->address          = $request->input( 'address' );
		$sale->meta->city             = $request->input( 'city' );
		$sale->meta->state            = $request->input( 'state' );
		$sale->meta->country          = $request->input( 'country' );
		$sale->meta->payment_method   = $request->input( 'payment_method' );
		$sale->meta->license_type     = $request->input( 'license_type' );
		$sale->meta->financial_status = "pending";
		$sale->meta->is_prebooking    = $is_upcoming_report;
		$sale->meta->ipAddress        = $request->ip();
		$sale->meta->domain           = $reportDetails['domain'];
		$sale->meta->report_id        = $reportDetails['report_id'];
		$sale->meta->title            = $reportDetails['title'];
		$sale->meta->short_title      = $reportDetails['short_title'];
		$sale->meta->report_url       = $reportDetails['report_url'];
		$sale->meta->publisher        = $reportDetails['publisher'];
		$sale->meta->publisher_id     = $reportDetails['publisher_id'];
		$sale->meta->amount           = $amount;


//		if ( $request->input( 'license_type' ) == "single_user" ) {
//			$sale->meta->amount = $is_upcoming_report ? $report->meta->prebook_single_user : $report->meta->price_single_user;
//		} elseif ( $request->input( 'license_type' ) == "multi_user" ) {
//			$sale->meta->amount = $is_upcoming_report ? $report->meta->prebook_multi_user : $report->meta->price_multi_user;
//		} elseif ( $request->input( 'license_type' ) == "corporate_user" ) {
//			$sale->meta->amount = $is_upcoming_report ? $report->meta->prebook_corporate_user : $report->meta->price_corporate_user;
//		}

		$sale->save();

		// Transfer control to https://crm.market.biz/process-sale?saleId=$sale->ID&domain=marketresearch.biz
		return Response::create()
		               ->setStatusCode( 302, "Thank you!" )
		               ->withHeaders( [
			               'Location' => 'https://crm.market.biz/process-sale?sale_id=' . $sale->ID . '&domain=' . $domain
		               ] );


//		$sale = Sale::find( $sale->ID );
////        print_r($report);
//		//TODO : change the mail To
//
//		if ( $request->input( 'payment_method' ) == 'WireTransfer' ) {
//			Mail::to( env( 'CONTACT_EMAIL', 'inquiry@marketresearch.biz' ) )->send( new NewSaleMailToSales( $sale, $report ) );
//			Mail::to( env( 'CONTACT_EMAIL_PRUDOUR_PAYMENTS', 'payments@prudour.com' ) )->send( new NewSaleMailToSales( $sale, $report ) );
//			Mail::to( $request->input( 'email' ) )->send( new NewSaleMailToCustomer( $sale, $report, 'WireTransfer' ) );
//
//			return Response::create()
//			               ->setStatusCode( 303, "Thank you!" )
//			               ->withHeaders( [
//				               'Location' => '/thank-you-for-purchasing/?reportId=' . $reportId
//			               ] );
//		} else {
//			$data                 = [];
//			$data['sale']         = $request->input();
//			$data['amount']       = $sale->meta->amount;
//			$data['report_title'] = $report->meta->full_title;
//			$data['sales_id']     = $sale->ID;
//			if ( $request->input( 'payment_method' ) == 'PayPal' ) {
//				$_SESSION['payment'] = $data;
//				Mail::to( env( 'CONTACT_EMAIL', 'inquiry@marketresearch.biz' ) )->send( new PaymentMailToSales( $sale, $report ) );
//				Mail::to( env( 'CONTACT_EMAIL_PRUDOUR_PAYMENTS', 'payments@prudour.com' ) )->send( new PaymentMailToSales( $sale, $report ) );
//
//				return view( "payment.paypal_payment" );
//			} elseif ( $request->input( 'payment_method' ) == 'TwoCheckout' ) {
//				$_SESSION['payment'] = $data;
//
//				return view( "payment.two_checkout_payment" );
//			}
//		}
	}

	private function getReportDetails( $reportUrl ) {
		$url = $reportUrl . '?t=' . time();
		$response = \Httpful\Request::head( $url )->send();
		if(!$response->headers['x-post-id']) { // getting in second request
			$response = \Httpful\Request::head( $url )->send();
		}
		return [
			'domain'       => $response->headers['x-post-domain'],
			'report_id'    => $response->headers['x-post-id'],
			'title'        => $response->headers['x-post-title'],
			'short_title'  => $response->headers['x-post-short-title'],
			'report_url'   => $response->headers['x-post-url'],
			'publisher'    => $response->headers['x-post-publisher'],
			'publisher_id' => $response->headers['x-post-publisher-id']
		];
	}

	public function afterPaidByPayPal( Request $request ) {
//        Log::info("Got IPN request", $request);
		$sale                         = Sale::find( $request->sales_id );
		$report                       = Report::find( $sale->meta->reportId );
		$sale->meta->financial_status = "paid";
		$sale->save();
		Mail::to( env( 'CONTACT_EMAIL', 'inquiry@marketresearch.biz' ) )->send( new NewSaleMailToSales( $sale, $report ) );
		Mail::to( $sale->meta->email )->send( new NewSaleMailToCustomer( $sale, $report, 'PayPal' ) );
	}

	private function paymentByTwoCheckout( $request ) {
		$sale   = Sale::find( $request->sales_id );
		$report = Report::find( $sale->meta->report_id );
		Mail::to( env( 'CONTACT_EMAIL', 'inquiry@marketresearch.biz' ) )->send( new NewSaleMailToSales( $sale, $report ) );
		Mail::to( env( 'CONTACT_EMAIL_PRUDOUR_PAYMENTS', 'payments@prudour.com' ) )->send( new NewSaleMailToSales( $sale, $report ) );
		Mail::to( $request->input( 'email' ) )->send( new NewSaleMailToCustomer( $sale, $report ) );
	}

	public function fetchReport( Request $request, $id ) {
		if ( ! ( $request->hasHeader( 'X-AUTH' ) && $request->header( 'X-AUTH' ) == 'crm-master-api' ) ) {
			print_r( 'unAutherized' );

			return;
		}
		$report = Report::find( $id );

		return response()->json( $report );

	}

	public function checkAndAddRatingToReport() {
		$reports = Report::join( 'postmeta', 'postmeta.post_id', '=', 'ID' )
		                 ->where( function ( $q ) {
			                 $q->Where( 'postmeta.meta_key', 'ratings' )
			                   ->where( function ( $r ) {
				                   $r->WhereNull( 'postmeta.meta_value' )
				                     ->orWhere( 'postmeta.meta_value', '=', 0 );
			                   } );
		                 } )
		                 ->orWhere( function ( $q ) {
			                 $q->Where( 'postmeta.meta_key', 'reviews' )
			                   ->where( function ( $r ) {
				                   $r->WhereNull( 'postmeta.meta_value' )
				                     ->orWhere( 'postmeta.meta_value', '=', 0 );
			                   } );
		                 } )
		                 ->get();
		print_r( sizeof( $reports ) );
		foreach ( $reports as $report ) {
			if ( $report->meta ) {
				if ( ! $report->meta->ratings || $report->meta->ratings == 0 ) {
					$report->meta->ratings = random_int( 4, 5 );
				}
				if ( ! $report->meta->reviews || $report->meta->reviews == 0 ) {
					$report->meta->reviews = random_int( 5, 60 );
				}
				$report->save();
			}
		}
	}

	public function getSale( Request $request ) {
		if ( ! ( $request->hasHeader( 'X-AUTH' ) && $request->header( 'X-AUTH' ) == 'crm-master-api' ) ) {
			print_r( 'unAutherized' );

			return response()->json( [ 'status' => false, 'error' => true ] );
		}
		$sale   = Sale::find( $request->input( 'sale_id' ) );
		$output = [ 'ID' => $sale->ID ];
		foreach ( $sale->meta as $item ) {
			$output[ $item->meta_key ] = $item->meta_value;
		}

		return response()->json( $output );
	}

}
