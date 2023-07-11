@extends('hanoivip::layouts.app-test')

@section('title', 'Cart detail')

@section('content')

@if (!empty($cart->items))
	@foreach ($cart->items as $item)
		<p>Item name: {{ $item->title }}</p>
		@foreach ($item->images as $image)
			<img href="{{$image}}"/>
		@endforeach
		<p>Origin price: {{ $item->origin_price }}</p>
		<p>Current price: {{ $item->price }}</p>
		<form method="post" action="{{route('shopv2.cart.remove')}}">
			{{ csrf_field() }}
			<input type="hidden" id="item" name="item" value="{{$item->code}}" />
			<button type="submit">Remove</button>
		</form>
	@endforeach
	<form method="post" action="{{route('shopv2.order')}}">
		{{ csrf_field() }}
		<input type="hidden" id="cart" name="cart" value="{{$cart->id}}" />
		<button type="submit">Order</button>
	</form>
	<form method="post" action="{{route('shopv2.cart.drop')}}">
		{{ csrf_field() }}
		<input type="hidden" id="cart" name="cart" value="{{$cart->id}}" />
		<button type="submit">Drop</button>
	</form>
@else
	<p>Cart is empty!</p>
@endif

@endsection