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
  		$this->data['price'] = '200.00';
  		$this->data['currency'] = 'USD';
  		$this->data['description'] = 'Flagship Over-the-ear Bluetooth® Headset with NFC';
  		return View::make('hello', $this->data);
  		//Plantilla de datos necesaria para una respuesta y 
  		//redireccion existosa a la pasarela de paypal
  	}

  	public function postPayment()
  	{
          	$params = array(
                		'cancelUrl' 	=> 'http://localhost:8000/cancel_order',
                		'returnUrl' 	=> 'http://localhost:8000/payment_success',
                  	'name'		=> Input::get('name'),
                  	'description' 	=> Input::get('description'),
                  	'amount' 	=> Input::get('price'),
                  	'currency' 	=> Input::get('currency')
                  	
                  	//Se puede agregar al array imagenes para que la pasarela sea personalizada
          	);
          	
          	//Guarda los valores de la orden de detalle en una session más las url de cancelacion y 
          	//retorno. Cuando se haya pagado en la pasarela, y es una transaccion existosa. Paypal 
          	//redirigira al comprador a la url de retorno. En ese lugar nosotros necesitaremos 
          	//comprobar que la transaccion se haya realizado enviando estos parametros nuevamente 
          	//mediante un metodo.
		// Tambien si necesitamos obtener el detalle de la orden
		// Tambien para obtener los valores : transactionId y transactionReference
          	Session::put('params', $params);
          	Session::save();
	
		//Instanciamos un objeto de tipo Paypal checkout
  	   	$gateway = Omnipay::create('PayPal_Express');
  	   	
  	   	//Credenciales
  	   	$gateway->setUsername('iluis.06_api1.outlook.com');
     		$gateway->setPassword('E727P533LGTL57R6');
     		$gateway->setSignature('AFcWxV21C7fd0v3bYYYRCpSSRl31AXjJgfspI5c76JPDRaLi7Wc0EQuo');
		
		//Modo prueba
  	   	$gateway->setTestMode(true);
		
		//Enviamos la peticion mediante el método purchase
  	  	$response = $gateway->purchase($params)->send();
		
		//Verificamos la respuesta
      		if ($response->isSuccessful()) {
	
			//No logro estender esta situacion
	
  		} elseif ($response->isRedirect()) {

			//Si la peticion es permitida, redigirimos al comprador a la pasarela de paypal	
  	      		$response->redirect();
	
  	  	} else {
		
		      //En el caso que paypal no obtenga los datos necesarios
  		      echo $response->getMessage();
  		      
  		      
  		      //Comentario original
  		      // (payment failed: display message to customer) ??? 
  
  	  	}
  	}
	
	//Url de retorno
  	public function getSuccessPayment()
    	{
     		$gateway = Omnipay::create('PayPal_Express');
     		$gateway->setUsername('iluis.06_api1.outlook.com');
     		$gateway->setPassword('E727P533LGTL57R6');
     		$gateway->setSignature('AFcWxV21C7fd0v3bYYYRCpSSRl31AXjJgfspI5c76JPDRaLi7Wc0EQuo');
     		$gateway->setTestMode(true);

		//Obtenemos los parametros mediante la sesion guardada
  		$params = Session::get('params');
		
		//Enviamos los parametros mediante el metodo completePurchase para obtener datos de la transaccion. 
    		$response = $gateway->completePurchase($params)->send();
    		
    		// this is the raw response object
    		$paypalResponse = $response->getData(); 
	
		//PAYMENT_INFO_0_ACK  esta dentro del objeto respuesta
		//PAYMENT_INFO_0_ACK es la variable que comprueba que si un monto de la transacción ha sido existosa.
		//Pueden existir mas variables similares como PAYMENT_INFO_1_ACK, PAYMENT_INFO_2_ACK etc,
		//los numeros significan cada monto enviado. En este caso solo es un monto. Si se ha completado la
		//transaccion de dicho monto, esta variable sera igual a success.
		if(isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success') {

        		//Transaccion existosa...actualizar la bd

    		} else {

        		//Failed transaction

    		}
    		
    		//Dentro del objeto respuesta existe la posicion ACK, parece que indica que es el estado de la transaccion
    		//en general.
    	}

}
