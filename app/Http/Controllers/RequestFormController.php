<?php
/**
 * Created by IntelliJ IDEA.
 * User: nidheeshdas
 * Date: 18/03/17
 * Time: 8:51 PM
 */

namespace App\Http\Controllers;


use App\Lead;
use App\Mails\NewContactToSales;
use App\Mails\NewLeadMailToCustomer;
use App\Mails\NewLeadMailToSales;
use App\Mails\NewOtherSiteLeadMailToCustomer;
use App\Mails\NewOtherSiteLeadMailToSales;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class RequestFormController extends Controller {

	public function __construct() {
		session_start();
	}

	public function submitRequest( Request $request ) {
		$form                   = $request->input();
		$_SESSION['submission'] = $form;
		$_SESSION['errors']     = [];

		$secret = "6LfZVR0UAAAAAL1EwEphYzUSGwimJxhrBEo5nn39";
		$url    = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $request->input( 'g-recaptcha-response' );
//print_r( $request->input('g-recaptcha-response'));die();
		try {
			$this->validate( $request, [
				'customerName' => 'required',
				'email'        => 'required|email',
				'phone'        => 'required',
				'country'      => 'required',
			] );
		} catch ( ValidationException $e ) {
			$_SESSION['errors'] = json_decode( $e->getResponse()->getContent(), true );

			return redirect( $request->headers->get( 'referer' ) . '#' . $form['formType'] );
		}

		$response = \Httpful\Request::post( $url )
		                            ->send();
		$res      = json_decode( $response->raw_body );
//        print_r( $res);die();
		if ( ! $res->success ) {
			$_SESSION['errors'] = [ "recaptcha" => array( "Incorrect CAPTCHA" ) ];

			return redirect( $request->headers->get( 'referer' ) . '#' . $form['formType'] );
		}
//		print_r( "<pre>" );
		$report = Report::find( $form['reportId'] );
//		var_dump( $report->meta->marketbiz_report_id );
//		die();
		// do whatever!
		$lead             = new Lead();
		$lead->post_title = "lead_" . $form['customerName'] . "_" . $form['reportId'];
		$lead->post_type  = 'lead';
		$lead->save();
		$lead->meta->reportid     = $form['reportId'];
		$lead->meta->reportname   = $report->meta->full_title;
		$lead->meta->formtype     = $form['formType'];
		$lead->meta->customername = $form['customerName'];
		$lead->meta->email        = $form['email'];
		$lead->meta->phone        = $form['phone'];
		$lead->meta->country      = $form['country'];
		$lead->meta->company      = $form['company'];
		$lead->meta->designation  = $form['designation'];
		$lead->meta->message      = $form['message'];
		$lead->meta->report       = $form['reportId'];
		$lead->meta->ipAddress    = $request->ip();
		$lead->save();

		try {
			$this->sendLeadToCrm( $form, $request->ip(), $report, "marketresearch.biz" );
		} catch ( \Exception $e ) {
		}
		$lead = Lead::find( $lead->ID );

		Mail::to( env( 'CONTACT_EMAIL', 'inquiry@market.biz' ) )->send( new NewLeadMailToSales( $lead, $report ) );
		Mail::to( $form['email'] )->send( new NewLeadMailToCustomer( $lead, $report ) );

		return Response::create()
		               ->setStatusCode( 303, "Thank you!" )
		               ->withHeaders( [
			               'Location' => '/thank-you?reportId=' . $form['reportId'] . '&type=' . $form['formType']
		               ] );
	}

	private function sendLeadToCrm( $form, $ipAddress, $report = null, $source = null ) {

		if ( env( 'APP_ENV' ) == 'local' ) {
			$url = "https://requestb.in/1k7e7w21";
		} else {
			$url = "https://globemetrix-crm.thavorath.com/newLead";
		}
		$crmReqest = [];
		Log::info( 'crm url : ' . $url );
		$crmReqest["customer_id"] = null;
		$crmReqest["post_title"]  = array_key_exists( "customerName", $form ) ? $form["customerName"] : '';
		$crmReqest["ipAddress"]   = $ipAddress;
		$crmReqest["tags_input"]  = null;
		$crmReqest["title"]       = array_key_exists( "designation", $form ) ? $form["designation"] : '';
		$crmReqest["name"]        = array_key_exists( "customerName", $form ) ? $form["customerName"] : '';
		$crmReqest["phone"]       = array_key_exists( "phone", $form ) ? $form["phone"] : '';
		$crmReqest["designation"] = array_key_exists( "designation", $form ) ? $form["designation"] : '';
		$crmReqest["country"]     = array_key_exists( "country", $form ) ? $form["country"] : '';
		$crmReqest["howhelp"]     = array_key_exists( "message", $form ) ? $form["message"] : '';
		$crmReqest["email"]       = array_key_exists( "email", $form ) ? $form["email"] : '';
		$crmReqest["company"]     = array_key_exists( "company", $form ) ? $form["company"] : '';
		$crmReqest["reportId"]    = array_key_exists( "reportId", $form ) ? $form["reportId"] : ( $report ? $report->ID : null );
		try {
			$crmReqest["reportTitle"] = $report != null ? $report->post_title : null;
		} catch ( \Exception $e ) {
		}
		$crmReqest["inquirytype"]  = array_key_exists( "formType", $form ) ? $form["formType"] : '';
		$crmReqest["license_type"] = null;
		$crmReqest["coupon_code"]  = null;
		$crmReqest["domain"]       = "marketresearch.biz";
		if ( array_key_exists( "reportSiteName", $form ) ) {
			if ( $form["reportSiteName"] == "market.biz" ) {
				$crmReqest["domain"] = "market.biz";
			} else if ( $form["reportSiteName"] == "qyresearch.us" ) {
				$crmReqest["domain"] = "qyresearch.us";
			} else if ( $form["reportSiteName"] == "marketresearch.biz" ) {
				$crmReqest["domain"] = "marketresearch.biz";
			}
		}
//		$crmReqest["source"] = array_key_exists( "reportSiteName", $form ) ? $form["reportSiteName"] : $source;
//		$crmReqest["domain"] = array_key_exists( "source", $form ) ? $form["source"] : $crmReqest["domain"];

		$response = \Httpful\Request::post( $url )
		                            ->sendsJson()
		                            ->addHeader( 'Content-Type', 'application/json' )
		                            ->addHeader( 'X-AUTH', 'crm-master-api' )
		                            ->body( $crmReqest )
		                            ->send();

		Log::info( "response from crm " . $response );
	}

	public function submitRequestFromOtherSite( Request $request ) {
		$form = $request->input();

		$secret = "6LfZVR0UAAAAAL1EwEphYzUSGwimJxhrBEo5nn39";
		$url    = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $request->input( 'g-recaptcha-response' );
//print_r( $request->input('g-recaptcha-response'));die();
		try {
			$this->validate( $request, [
				'customerName' => 'required',
				'email'        => 'required|email',
				'phone'        => 'required',
				'country'      => 'required',
			] );
		} catch ( ValidationException $e ) {
			return response()->json( [
				'status' => 'error',
				'data'   => json_decode( $e->getResponse()->getContent(), true )
			] );
		}

		$response = \Httpful\Request::post( $url )
		                            ->send();
		$res      = json_decode( $response->raw_body );
//        print_r( $res);die();
		if ( ! $res->success ) {
			return response()->json( [
				'status' => 'error',
				'data'   => [
					"recaptcha" => array( "Incorrect CAPTCHA" )
				]
			] );
		}

		$lead                     = new Lead();
		$lead->meta->reportUrl    = $request->input( 'reportUrl', '' );
		$lead->meta->formtype     = $form['formType'];
		$lead->meta->customername = $form['customerName'];
		$lead->meta->email        = $form['email'];
		$lead->meta->phone        = $form['phone'];
		$lead->meta->country      = $form['country'];
		$lead->meta->company      = $form['company'];
		$lead->meta->designation  = $form['designation'];
		$lead->meta->message      = $request->input( 'message', '' );
		$lead->meta->ipAddress    = $request->ip();


		try {
			$this->sendLeadToCrm( $form, $request->ip() );
		} catch ( \Exception $e ) {
		}


		Mail::to( env( 'CONTACT_EMAIL', 'inquiry@market.biz' ) )->send( new NewOtherSiteLeadMailToSales( $lead ) );
//		Mail::to( "geethu@nirandas.com" )->send( new NewOtherSiteLeadMailToSales( $lead ) );
		Mail::to( $form['email'] )->send( new NewOtherSiteLeadMailToCustomer( $lead ) );


		return response()->json( [ 'status' => 'success', 'data' => "ok" ] );
	}

	public function sendContactMail( Request $request ) {
		$form                   = $request->input();
		$_SESSION['submission'] = $form;
		$_SESSION['errors']     = [];

		$secret = "6LfZVR0UAAAAAL1EwEphYzUSGwimJxhrBEo5nn39";
		$url    = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $request->input( 'g-recaptcha-response' );
		try {
			$this->validate( $request, [
				'name'    => 'required',
				'email'   => 'required|email',
				'message' => 'required',
			] );
		} catch ( ValidationException $e ) {
			$_SESSION['errors'] = json_decode( $e->getResponse()->getContent(), true );

			return redirect( $request->headers->get( 'referer' ) );
		}

		$response = \Httpful\Request::post( $url )
		                            ->send();
		$res      = json_decode( $response->raw_body );
		if ( ! $res->success ) {
			$_SESSION['errors'] = [ "recaptcha" => array( "Incorrect CAPTCHA" ) ];

			return redirect( $request->headers->get( 'referer' ) );
		}

		//TODO::change the mail TO
		Mail::to( env( 'CONTACT_EMAIL', 'inquiry@market.biz' ) )->send( new NewContactToSales( $form['name'], $form['email'], $form['subject'], $form['message'] ) );

		return Response::create()
		               ->setStatusCode( 303, "Thank you!" )
		               ->withHeaders( [
			               'Location' => '/thank-you-for-contacting-us'
		               ] );
	}

}