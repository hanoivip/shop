@extends('hanoivip::admin.layouts.admin')

@section('title', 'Web shop')

@section('content')

@foreach ($shops as $shop)
	<a href="{{ route('ecmin.shopv2.open', ['slug' => $shop->slug]) }}">Open {{ $shop->name }}</a><br/>
@endforeach 


@endsection
