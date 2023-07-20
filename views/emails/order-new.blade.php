@if (!empty($cart->items))
	<h3>You are buying following items:</h3>
	@foreach ($cart->items as $item)
		<p>Item name: {{ $item->title }}</p>
		<img href="{{asset($item->images[0])}}"/>
		<p>Item origin price: <strike>{{ $item->origin_price }}</strike> {{ $item->currency }}</p>
		<p>Item current price: {{ $item->price }} {{ $item->currency }}</p>
	@endforeach
	<a href="{{ route('shopv2.pay', ['order' => $order ]) }}" class="btn btn-primary">Pay now</a>
@endif
