@extends('hanoivip::admin.layouts.admin')

@section('title', 'User shop-order detail')

@section('content')

@if (!empty($order))
<ul>
@foreach ($items as $item)
	<li>
		<div>
			<div>
			Name: {{$item->title}} </br>
			Origin price: {{$item->origin_price}} </br>
			Price: {{$item->price}}
			<input type="hidden" name="shop" value="{{$item->shop->slug}}"/>
			<input type="hidden" name="item" value="{{$item->code}}"/>
			<input type="hidden" name="count" value="1"/>
				@foreach ($item->images as $image)
					<img src="{{$image}}" title="{{$item->title}}" style="width:128px; height: 64px;"/>
				@endforeach
			</div>
		</div>
	</li>
@endforeach
</ul>
@else
<p>Order is not exists</p>
@endif

<a href="{{ route('ecmin.shopv2.additem', ['slug' => $slug]) }}">More item</a>


@endsection
