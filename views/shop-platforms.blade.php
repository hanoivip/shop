@extends('hanoivip::layouts.app-test')

@section('title', 'Danh sách các shop')

@section('content')

@if (!empty($platforms))
	@foreach ($platforms as $platform)
		<a href="{{route('shop.platform.detail', ['platform' => $platform]) }}">Vào shop {{ $platform }}</a>
	@endforeach 
@else
	<p>Chưa có shop nào trên hệ thống</p>
@endif

@endsection
