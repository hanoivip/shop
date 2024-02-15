@extends('hanoivip::admin.layouts.admin')

@section('title', 'Shop order manager')

@section('content')

<form method="post" action="{{ route('ecmin.shopv2.order.find') }}">
{{ csrf_field() }}
Find by order: <input type="text" name="order" id="order" value="" />
<button type="submit">Filter</button>
</form>

@if (!empty($orders))
    <table>
    	<tr>
    		<th>Time</th>
    		<th>Serial</th>
    		<th>Price</th>
    		<th>Payment Status</th>
    		<th>Delivery Status</th>
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
    			<a href="{{ route('ecmin.shopv2.order.view', ['order' => $order->serial ]) }}">Detail</a>
    			<br/>
    		</td>
    	</tr>
    @endforeach 
    </table>
    
    {{ $orders->links() }}
@else
<p>Have no any order</p>
@endif

@endsection
