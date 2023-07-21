@extends('hanoivip::layouts.app')

@section('title', 'Web shop items')


@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/flickity@2.3.0/dist/flickity.css">
<script type="text/javascript" src="https://unpkg.com/flickity@2.3.0/dist/flickity.pkgd.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.main-gallery').flickity({ "wrapAround": true })
})
</script>
@endpush

@section('content')
<script>
function onSort(e, meta)
{
	var form = document.getElementById('shopv2_sort_frm')
	document.getElementById('sort_type').value = e.name
	document.getElementById('sort').value = e.value
	document.getElementById('is_meta').value = meta
	form.submit()
}
</script>

<select id="price" name="price" onchange="onSort(this, 0);" >
	<option value="">Xếp theo giá</option>
	<option value="desc">Giảm dần</option>
	<option value="asc">Tăng dần</option>
</select>

<select id="meta" name="LC" onchange="onSort(this, 1);">
	<option value="">Xếp theo LC</option>
	<option value="desc">Giảm dần</option>
	<option value="asc">Tăng dần</option>
</select>

<select id="meta" name="Star" onchange="onSort(this, 1);">
	<option value="">Xếp theo star</option>
	<option value="desc">Giảm dần</option>
	<option value="asc">Tăng dần</option>
</select>

<form method="post" action="{{route('shopv2')}}" id="shopv2_sort_frm" name="shopv2_sort_frm">
	{{ csrf_field() }}
	<input type="hidden" id="shop" name="shop" value="{{$shop}}" />
	<input type="hidden" id="sort_type" name="sort_type" value="" />
	<input type="hidden" id="sort" name="sort" value="" />
	<input type="hidden" id="is_meta" name="is_meta" value="" />
</form>

@if (empty($items))
	<p>Shop is empty</p>
@else
@foreach ($items as $item)
	<div>
		<form method="post" action="{{route('shopv2.cart.add')}}">
    		{{ csrf_field() }}
    		<h3>{{$item->title}}</h3>
    		Old price: <strike>{{$item->origin_price}}</strike> {{$item->currency}}<br/>
    		Price: {{$item->price}} {{$item->currency}}<br/>
    		<input type="hidden" name="shop" value="{{$item->shop->slug}}"/>
    		<input type="hidden" name="item" value="{{$item->code}}"/>
    		<input type="hidden" name="count" value="1"/>
    		<div class="main-gallery">
    			@foreach ($item->images as $image)
    				<div class="gallery-cell">
    					<img src="{{$image}}" title="{{$item->title}}" />
    				</div>
    			@endforeach
			</div>
			<br/>
			<p>Description: {{ $item->description }}</p>
			<p>Attributes/meta here..</p>
			<br/>
    		<button type="submit">Add to cart</button>
		</form>
	</div>
@endforeach
@endif

@endsection
