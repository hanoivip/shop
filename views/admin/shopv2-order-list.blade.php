@extends('hanoivip::admin.layouts.admin')

@section('title', 'User order list')

@section('content')

@if (empty($orders))
	<p>Have no order yet!</p>
@else
    <table>
    	<tr>
    		<th>Serial</th>
    		<th>Price</th>
    		<th>Payment</th>
    		<th>Delivery</th>
    		<th>Reason</th>
    		<th>Actions</th>
    	</tr>
    @foreach ($orders as $order)
    	<tr>
    		<td>{{ $order->serial }}</td>
    		<td>{{ $order->price }} {{ $order->currency }}</td>
    		<td>{{ $order->payment_status }}</td>
    		<td>{{ $order->delivery_status }}</td>
    		<td>{{ $order->delivery_reason }}</td>
    		<td>
    			<a href="{{ route('ecmin.shopv2.order.view', ['order' => $order->serial ]) }}">Detail</a>
    			<br/>
    			<a href="{{ route('ecmin.shopv2.order.finish', ['order' => $order->serial ]) }}">Finish</a>
    		</td>
    	</tr>
    @endforeach 
    </table>
@endif

@endsection
