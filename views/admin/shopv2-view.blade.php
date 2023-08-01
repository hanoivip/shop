@extends('hanoivip::admin.layouts.admin')

@section('title', 'Web shop items')

@section('content')

<p>Shop slug: {{ $slug }}</p>

@if (!empty($items))
<ul>
@foreach ($items as $item)
	<li>
		<div>
			<div>
    			Name: {{$item->title}} </br>
    			Origin price: {{$item->origin_price}} </br>
    			Price: {{$item->price}} </br>
    			<input type="hidden" name="shop" value="{{$item->shop->slug}}"/>
    			<input type="hidden" name="item" value="{{$item->code}}"/>
    			<input type="hidden" name="count" value="1"/>
				@foreach ($item->images as $image)
					<img src="{{$image}}" title="{{$item->title}}" style="width:128px; height: 64px;"/>
				@endforeach
			</div>
			<br/>
			<a href="{{ route('ecmin.shopv2.remitem', ['code' => $item->code, 'slug' => $item->shop->slug ]) }}">Del</a>
		</div>
	</li>
@endforeach
</ul>
@else
<p>Shop is empty</p>
@endif

<a href="{{ route('ecmin.shopv2.additem', ['slug' => $slug]) }}">More item</a>


@endsection
