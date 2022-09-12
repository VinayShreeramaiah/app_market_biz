<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


Route::post( '/app/submit-request', 'RequestFormController@submitRequest' );
Route::post( '/app/lead-request-othersite', 'RequestFormController@submitRequestFromOtherSite' );
Route::get( '/app/get-report', 'ReportController@getReport' );
Route::post( "/app/buy-now", 'ReportController@buyNow' );
Route::get( "/app/get-buy-now", 'ReportController@buyNowData' );
Route::post( "/app/pay", 'ReportController@pay' );
Route::post( "/app/paypal-ipn", 'ReportController@afterPaidByPayPal' );
Route::post( "/app/send-contact-mail", 'RequestFormController@sendContactMail' );
Route::post( "/app/send-contact-mail", 'RequestFormController@sendContactMail' );
Route::get( '/app/fetchReport/{id}', 'ReportController@fetchReport' );
Route::post( '/app/upload', 'ReportController@uploadReport' );
Route::get( '/app/home', 'ReportController@home' );
Route::post( '/app/login', [ 'uses' => 'AuthenticationController@login' ] );
Route::post( '/app/logout', [ 'uses' => 'AuthenticationController@logout' ] );
//Route::get( '/app/checkAndAddRatingToReport', 'ReportController@checkAndAddRatingToReport' );
Route::get('/app/get-sale', 'ReportController@getSale');

//$app->group(['middleware' => 'middleware.auth'], function ($app) {
//	$app->get('/user/dashboard', ['uses' => 'Controller@method']);
Route::get( '/app/me', function ( \Illuminate\Http\Request $request ) {
	return $request->user();
} );

//Route::get( "/app/import-reports", 'ImportController@importReports' );

Route::get( '{t}', function ( \Illuminate\Http\Request $request ) {
	echo "<pre>";
	var_dump( $request );
	var_dump( $_REQUEST );
	die();
} );
