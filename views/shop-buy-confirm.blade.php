@extends('hanoivip::layouts.app-test')

@section('title', 'Xác nhận mua vật phẩm')

@section('content')

@if (!empty($roles))
<form method="post" action="{{route('shop.buy.confirm')}}">
	{{ csrf_field() }}
	Bạn đang muốn mua: {{$item}}
	Chọn nhân vật:
	<select id='role' name='role'>
	@foreach ($roles as $roleId => $roleName)
		<option value="{{$roleId}}">{{$roleName}}</option>
	@endforeach
	</select>
	<input type="hidden" id="platform" name="platform" value="{{$platform}}"/>
	Xác nhận mua?
	<button type="submit">OK</button>
</form>
@else
	<p>Cần chọn nhân vật để mua. Mời thực hiện lại</p>
@endif

<a href="{{ route('shop.platform.detail', ['platform' => $platform]) }}">Quay lại</a>

@endsection
