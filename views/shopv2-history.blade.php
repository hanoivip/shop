@extends('hanoivip::layouts.app-test')

@section('title', 'Web shop history')

@section('content')

@foreach ($records as $record)
	{{ print_r($record, true) }}
@endforeach 


@endsection
