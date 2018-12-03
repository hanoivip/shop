@extends('hanoivip::layouts.app-test')

@section('title', 'Mua vật phẩm')

@section('content')

@if (!empty($error))
<p>Lỗi: {{ $error }}</p>
@endif

@if (!empty($message))
<p> {{ $message }}</p>
@endif

<a href="{{ route('shop.platform.detail', ['platform' => $platform]) }}">Quay lại</a>

@endsection
