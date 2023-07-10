@extends('hanoivip::layouts.app-test')

@section('title', 'Web shop')

@section('content')

@foreach ($shops as $shop)
	<a href="{{ route('shopv2', ['shop' => $shop->id]) }}">Open {{ $shop->name }}</a><br/>
@endforeach 


@endsection
