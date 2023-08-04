@extends('hanoivip::layouts.app-test')

@section('title', 'Web shop item detail')

@section('content')

<div>
	<form method="post" action="{{route('shopv2.cart.add')}}">
	{{ csrf_field() }}
	Name: {{$item->title}} </br>
	Origin price: {{$item->origin_price}} </br>
	Price: {{$item->price}}
	<input type="hidden" name="shop" value="{{$item->shop->slug}}"/>
	<input type="hidden" name="item" value="{{$item->code}}"/>
	<input type="hidden" name="count" value="1"/>
		@foreach ($item->images as $image)
			<img src="{{$image}}" title="{{$item->title}}"/>
		@endforeach 
	<button type="submit">Add to cart</button>
	</form>
</div>


@endsection
