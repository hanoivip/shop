@extends('hanoivip::layouts.app-test')

@section('title', 'Confirm?')

@section('content')

<form method="post" action="{{route('shop.order')}}">
{{ csrf_field() }}
<input type="hidden" name="server" value="{{$server}}"/>
<input type="hidden" name="role" value="{{$role}}"/>
<input type="hidden" name="shop" value="{{$shop}}"/>
<input type="hidden" name="item" value="{{$item_detail->code}}"/>
<input type="hidden" name="count" value="{{$count}}"/>
Confirm to buy: {{$item_detail->title}}
Count: {{$count}}
Origin Price: {{$price->origin_price}}
Price: {{$price->price}}
<button type="submit">Buy</button>
</form>

@endsection
