@extends('hanoivip::layouts.app-test')

@section('title', 'Chi tiết các shop trong platform')

@section('content')

@if (!empty($balance))
    <p>Thông tin tài khoản:<p><br/>
    @foreach ($balance as $bal)
    <p>Loại tài khoản:</p>{{$bal->balance_type}} <br/>
    <p>Số dư:</p>{{$bal->balance}} <br/>
    @endforeach
@else
	<p>Chưa có xu nào trong tk!</p>
@endif

@if (!empty($roles))
Chọn nhân vật:
	<select id='role' name='role'>
	@foreach ($roles as $roleId => $roleName)
		<option value="{{$roleId}}">{{$roleName}}</option>
	@endforeach
	</select>
@endif

@if (!empty($shops))
	<p>Long: sử dụng các tabs control để phân chia các shop</p>
	@foreach ($shops as $shop)
		<br/>
		Tên shop: {{ $shop['name'] }},
		
		@if (!empty($shop['items']))
		<p>Long: sử dụng các table để phân chia các item</p>
		Vật phẩm:
    		@foreach ($shop['items'] as $itemId => $item)
    			<form method="post" action="{{route('shop.buy')}}">
    				{{ csrf_field() }}
    				<img src="{{config('items.' . $itemId . '.img')}}" alt="{{ $item['name'] }}"/>
    				Tên: {{ $item['name'] }}, Giá: {{ $item['price'] }}, Số lượng: {{ $item['count'] }}
    				<input type="hidden" id="platform" name="platform" value="{{$platform}}"/>
    				<input type="hidden" id="shop" name="shop" value="{{$shop['id']}}"/>
    				<input type="hidden" id="item" name="item" value="{{$item['id']}}"/>
    				<button type="submit">Mua</button>
    			</form>
    			<br/>
    		@endforeach
    	@endif
	@endforeach
@else
	<p>Chưa có shop hoặc các shop đã hết hạn/đóng cửa! Mời quay lại sau</p>
	<a href="{{route('shop.platform')}}">Quay lại</a>
@endif

@endsection
