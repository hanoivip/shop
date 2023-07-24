@extends('hanoivip::layouts.app-test')

@section('title', 'Web shop history')

@section('content')

<table>
	<tr>
		<th>Time</th>
		<th>Order</th>
		<th>Price</th>
		<th>Payment Status</th>
		<th>Delivery Status</th>
		<th>Actions</th>
	</tr>
@foreach ($records as $record)
	<tr>
		<td>{{ $record->created_at }}</td>
		<td>{{ $record->serial }}</td>
		<td>{{ $record->price }} {{ $record->currency }}</td>
		<td>{{ __('hanoivip.shop::order.payment_status.' . $record->payment_status) }}
		@if ($record->payment_status == 0)
			<a href="{{ route('shopv2.pay', ['order' => $record->serial ]) }}">Pay</a>
		@endif
		</td>
		<td>{{ __('hanoivip.shop::order.delivery_status.' . $record->delivery_status) }}</td>
		<td>
			<a href="">Detail</a>
		</td>
	</tr>
@endforeach 
</table>

@endsection
