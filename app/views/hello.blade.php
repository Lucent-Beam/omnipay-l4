<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel - Omnipay - Paypal Example</title>
</head>
<body>
	{{ Form::open([ 'url' => 'pay_via_paypal', 'method' => 'post'  ]) }}
	<p>
		<img src="{{ $productImage }}" alt="Aurvana Platinum">
		<br />
		{{ $product }}
		<br>
		{{ $description }}
		<br>
		{{ $currency }} {{ $price }}
		<br>
		<input type="hidden" value="{{ $product }}" name="product">
		<input type="hidden" value="{{ $description }}" name="description">
		<input type="hidden" value="{{ $currency }}" name="currency">
		<input type="hidden" value="{{ $price }}" name="price">
		<button type="submit">PAY NOW!</button>
	</p>
	{{ Form::close() }}
</body>
</html>
