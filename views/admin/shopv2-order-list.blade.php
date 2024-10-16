@extends('hanoivip::admin.layouts.admin')

@section('title', 'User order list')

@section('content')

@if (empty($orders))
	<p>Have no order yet!</p>
@else
	<form method="post" action="{{route('ecmin.shopv2.order')}}">
		{{ csrf_field() }}
		Order <input id="order" name="order" value="" required/>
		<input id="tid" name="tid" value="{{$tid}}" type="hidden"/>
		<button type="submit" class="btn btn-primary">Filter</button>
	</form>
    <style>
    table, th, td {
      border: 1px solid black;
    }
    </style>
    <table>
    	<tr>
    		<th>Time</th>
    		<th>Serial</th>
    		<th>Price</th>
    		<th>Payment</th>
    		<th>Delivery</th>
    		<th>Reason</th>
    		<th>Actions</th>
    	</tr>
    @foreach ($orders as $order)
    	<tr>
    		<td>{{ $order->created_at }}</td>
    		<td>{{ $order->serial }}</td>
    		<td>{{ $order->price }} {{ $order->currency }}</td>
    		<td>{{ __('hanoivip.shop::order.payment_status.' . $order->payment_status) }}</td>
    		<td>{{ __('hanoivip.shop::order.delivery_status.' . $order->delivery_status) }}</td>
    		<td>{{ $order->delivery_reason }}</td>
    		<td>
    			<a href="{{ route('ecmin.shopv2.order.view', ['order' => $order->serial ]) }}" class="btn btn-secondary">Detail</a>
    			<a href="{{ route('ecmin.shopv2.order.email', ['order' => $order->serial ]) }}" class="btn btn-secondary">Email</a>
    			<!--
    			<br/> 
    			<a href="{{ route('ecmin.shopv2.order.finish', ['order' => $order->serial ]) }}" class="btn btn-primary">Finish</a>
    			 -->
    			<br/>
    			<form method="post" action="{{route('ecmin.shopv2.order.check')}}">
    				{{ csrf_field() }}
    				<input id="order" name="order" type="hidden" value="{{$order->serial}}"/>
    				Receipt <input id="receipt" name="receipt" required/>
    				<button type="submit" class="btn btn-primary">Check</button>
    			</form>
    		</td>
    	</tr>
    @endforeach 
    </table>
    {{ $orders->appends(['tid' => $tid])->links() }}
@endif

@endsection
