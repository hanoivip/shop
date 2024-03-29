@extends('hanoivip::layouts.app-test')

@section('title', 'Order detail')

@section('content')

<p>Order payment status: {{ $order->payment_status }}</p>
<p>Order delivery status: {{ $order->delivery_status }}</p>

@if (!empty($order->cart->items))
	@foreach ($order->cart->items as $item)
		<p>Item name: {{ $item->title }}</p>
		@foreach ($item->images as $image)
			<img href="{{$image}}"/>
		@endforeach
		<p>Item origin price: {{ $item->origin_price }}</p>
		<p>Item current price: {{ $item->price }}</p>
	@endforeach
	@if (!empty($order->cart->delivery_info))
		<p>---------------</p>
		{{ print_r($order->cart->delivery_info, true) }}
	@endif
	<p>---------------</p>
	<p>Order origin price: {{ $order->origin_price }} {{ $order->currency }}</p>
	<p>Order price: {{ $order->price }} {{ $order->currency }}</p> 
	<a href="{{ route('shopv2.pay', ['order' => $order->serial ]) }}">Pay</a>
@endif

@endsection