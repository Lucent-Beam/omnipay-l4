<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/



Route::get('home', array(
			'as' => 'home',
			'uses'	=> 'PaymentController@getIndex'
		)
);
Route::post('pay_via_paypal', 'PaymentController@postPayment');
Route::get('payment_success', 'PaymentController@getSuccessPayment');
Route::get('cancel_order', function() {
	return Redirect::route('home');
});
