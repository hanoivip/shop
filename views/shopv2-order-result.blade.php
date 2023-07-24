@extends('hanoivip::layouts.app-test')

@section('title', 'Shop process result')

@section('content')

@if (!empty($serial))
<a href="{{ route('shopv2.pay', ['order' => $serial ]) }}">Pay now</a>
@endif

@endsection
