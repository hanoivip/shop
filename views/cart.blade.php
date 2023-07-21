@extends('hanoivip::layouts.app-test')

@section('title', 'Cart detail')

@push('scripts')
    <script src="/js/recharge3.js"></script>
@endpush

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
		{{-- delivery infomation --}}
		<input type="hidden" id="cart" name="cart" value="{{$cart->id}}" />
		@if ($cart->delivery_type == 1 || $cart->delivery_type == 2)
			<select id="recharge-svname" name="svname" style="width: 100%;" data-action="{{ route('game.roles') }}" 
				data-update-id="recharge-roles-div">
        		{{ show_user_servers() }}
        	</select>
        	<div id="recharge-roles-div">
        	</div>
        	<a data-action="{{ route('game.roles') }}" id="recharge-refresh-roles" data-update-id="recharge-roles-div">Làm mới ds nhân vật</a>
		@endif
		<button type="submit">Order</button>
	</form>
	<a href="{{route('shopv2.cart.drop')}}">Drop</a>
@else
	<p>Cart is empty!</p>
@endif

@endsection