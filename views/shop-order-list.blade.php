@extends('hanoivip::layouts.app-test')

@section('title', 'Order fail')

@section('content')

@foreach ($orders as $order)
{{print_r($order)}}
@endforeach

@endsection
