@extends('hanoivip::layouts.app')

@section('title', 'Web shop history')

@section('content')

<style>
table, th, td {
  border: 1px solid black;
}
</style>

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
		</td>
		<td>{{ __('hanoivip.shop::order.delivery_status.' . $record->delivery_status) }}</td>
		<td>
			@if ($record->payment_status == 0)
    			<a href="{{ route('shopv2.pay', ['order' => $record->serial ]) }}">Pay</a>
    		@endif
		</td>
	</tr>
@endforeach 
</table>

{{ $records->links() }}

@endsection
