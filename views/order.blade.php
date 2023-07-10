@extends('hanoivip::layouts.app-test')

@section('title', 'Order detail')

@section('content')

<p>Order payment status: $order->payment_status</p>
<p>Order delivery status: $order->delivery_status</p>

@if (!empty($order->cart->items))
	@foreach ($order->cart->items as $item)
		<p>Item name: $item->title </p>
		@foreach ($item->images as $image)
			<img href="{{$image}}"/>
		@endforeach
		<p>Item origin price: $item->origin_price</p>
		<p>Item current price: $item->price</p>
	@endforeach
	<p>Order origin price: $order->origin_price</p>
	<p>Order price: $order->price</p> 
	<a href="{{ route('shopv2.pay', ['order' => $order->serial ]) }}">Pay</a>
@endif

@endsection