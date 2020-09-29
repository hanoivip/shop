@extends('hanoivip::layouts.app-test')

@section('title', 'Confirm?')

@section('content')

<form method="post" action="{{route('shop.order')}}">
Confirm to buy: {{$itemDetail->title}}
Count: {{$count}}
Price: {{$price}}
<button type="submit">Buy</button>
</form>

@endsection
