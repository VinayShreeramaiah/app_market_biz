<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {
	/**
	 * The URIs that should be excluded from CSRF verification.
	 *
	 * @var array
	 */
	protected $except = [
		'/app/login',
		'/app/logout',
		'/app/upload',
		'/app/submit-request',
		'/app/lead-request',
		'/app/get-report',
		'/app/buy-now',
		'/app/pay',
		'/app/paypal-ipn',
		'/app/send-contact',
		'/app/send-contact',
		'/app/upload',
		'/app/home',
		'/app/lead-request-othersite',
		'/app/fetchReport/',
		'/app/paypal-ipn'
	];
}
