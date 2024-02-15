@extends('hanoivip::admin.layouts.admin')

@section('title', 'User shop-order detail')

@section('content')

@if (!empty($order))
<h3>Buyer Info</h3>
<p>Web ID: {{ $order->user_id }}</p>
<br/>

<h3>Delivery info</h3>
{{ print_r($order->delivery_type, true) }}
@if ($order->cart->delivery_type == 1)
<p>Server {{$order->cart->delivery_info->svname}}</p>
<p>Role {{$order->cart->delivery_info->roleid}}</p>
@endif
<br/>

<h3>Cart info</h3>
<ul>
    @php($items = $order->cart->items)
    @foreach ($items as $item)
    	<li>
    		<div>
    			<div>
    			Name: {{$item->title}} </br>
    			Price: {{$item->price}} {{$item->currency}} <br/>
    			<input type="hidden" name="item" value="{{$item->code}}"/>
    			<input type="hidden" name="count" value="1"/>
    				@foreach ($item->images as $image)
    					<img src="{{$image}}" title="{{$item->title}}" style="width:64px; height: 64px;"/>
    				@endforeach
    			</div>
    		</div>
    	</li>
    @endforeach
</ul>
@else
<p>Order is not exists</p>
@endif


@endsection
