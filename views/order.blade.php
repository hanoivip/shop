@extends('hanoivip::layouts.app-test')

@section('title', 'Order detail')

@section('content')

<p>Order payment status: $order->payment_status</p>
<p>Order delivery status: $order->delivery_status</p>

@if (!empty($order->items))
	@foreach ($order->items as $item)
		<p>Item name: $item->name </p>
		@foreach ($item->images as $image)
			<img href="{{$image}}"/>
		@endforeach
		<p>Origin price: $item->origin_price</p>
		<p>Current price: $item->price</p>
	@endforeach
	<a href="{{ route('shopv2.pay', ['order' => $order->serial ]) }}">Pay</a>
@endif

@endsection