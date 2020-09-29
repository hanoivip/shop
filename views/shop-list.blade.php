@extends('hanoivip::layouts.app-test')

@section('title', 'Web shop')

@section('content')


<ul>
@foreach ($shops as $shop)
	<li style={{$shop->id == $current ? "active" : ""}}>
		<a href={{route('shop', ['shop' => $shop->id])}}>{{$shop->name}}</a>
	</li>
@endforeach 
</ul>

@foreach ($shops as $shop)
	@if ($shop->id == $current && !empty($shop->unlock))
		@foreach ($shop->unlock as $condition)
			{{ print_r($condition) }}
		@endforeach
	@endif 
@endforeach 

<ul>
@foreach ($shop_items as $item)
	<li>
		<div>
			<form method="post" action="{{route('shop.helper')}}">
			{{ csrf_field() }}
			Name: {{$item->title}} </br>
			Origin price: {{$item->origin_price}} </br>
			Price: {{$item->price}}
			<input type="hidden" name="shop" value="{{$current}}"/>
			<input type="hidden" name="item" value="{{$item->code}}"/>
			<input type="hidden" name="count" value="1"/>
			<img src="{{$item->image}}"/>
			<button type="submit">Buy</button>
			</form>
		</div>
	</li>
@endforeach
</ul>

@endsection
