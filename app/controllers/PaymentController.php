<?php

use Omnipay\Omnipay;

class PaymentController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

  private $data;

  	public function getIndex()
  	{
  		$this->data['product'] = 'Aurvana Platinum';
  		$this->data['productImage'] = 'http://img.creative.com/images/products/large/pdt_21734.png.ashx?width=200';
  		$this->data['price'] = '299.00';
  		$this->data['currency'] = 'USD';
  		$this->data['description'] = 'Flagship Over-the-ear Bluetooth® Headset with NFC';
  		return View::make('hello', $this->data);
  	}

  	public function postPayment()
  	{
          	$params = array(
                		'cancelUrl' 	=> 'http://localhost/cancel_order',
                		'returnUrl' 	=> 'http://localhost/payment_success',
                  	'name'		=> Input::get('name'),
                  	'description' 	=> Input::get('description'),
                  	'amount' 	=> Input::get('price'),
                  	'currency' 	=> Input::get('currency')
          	);

          	Session::put('params', $params);
          	Session::save();

  	   	$gateway = Omnipay::create('PayPal_Express');
  	   	$gateway->setUsername('iluis.06_api1.outlook.com');
     		$gateway->setPassword('E727P533LGTL57R6');
     		$gateway->setSignature('AFcWxV21C7fd0v3bYYYRCpSSRl31AXjJgfspI5c76JPDRaLi7Wc0EQuo');

  	   	$gateway->setTestMode(true);

  	  	$response = $gateway->purchase($params)->send();

      		if ($response->isSuccessful()) {

  	      		// payment was successful: update database
  	      		print_r($response);

  		} elseif ($response->isRedirect()) {

  	      		// redirect to offsite payment gateway
  	      		$response->redirect();

  	  	} else {

  		      // payment failed: display message to customer
  		      echo $response->getMessage();

  	  	}
  	}

  	public function getSuccessPayment()
    	{
     		$gateway = Omnipay::create('PayPal_Express');
     		$gateway->setUsername('iluis.06_api1.outlook.com');
     		$gateway->setPassword('E727P533LGTL57R6');
     		$gateway->setSignature('AFcWxV21C7fd0v3bYYYRCpSSRl31AXjJgfspI5c76JPDRaLi7Wc0EQuo');
     		$gateway->setTestMode(true);

  		$params = Session::get('params');

    		$response = $gateway->completePurchase($params)->send();
    		$paypalResponse = $response->getData(); // this is the raw response object

    		if(isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success') {

        			// Response
        			// print_r($paypalResponse);

    		} else {

        			//Failed transaction

    		}

      		return View::make('result');
    	}



}
