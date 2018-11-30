@extends('hanoivip::layouts.app-test')

@section('title', 'Chi tiết các shop trong platform')

@section('content')

@if (!empty($shops))
	<p>Long: sử dụng các tabs control để phân chia các shop</p>
	@foreach ($shops as $shop)
		Tên shop: {{ $shop->name }},
		@if ($shop->reset > 0)
		Thời gian reset: {{ $shop->reset }}
		@endif
		Vật phẩm:
		@foreach ($shop->items as $item)
			<form method="post" action="{{route('shop.buy')}}">
				csrf_token()
				Tên: {{ $item->name }}, Giá: {{ $item->price }}, Số lượng: {{ $item->count }}
				@if (isset($boughts[$item->id]) && !empty($shop->reset))
					Thời gian miễn phí kế tiếp: {{ display_wait_time($boughts[$item->id]->last_buy_time, $shop->reset) }}
				@endif
				<input type="hidden" id="platform" name="platform" value="{{$platform}}"/>
				<input type="hidden" id="item" name="item" value="{{$item->id}}"/>
				@if (!isset($boughts[$item->id]) && !empty($shop->reset))
					<button type="submit">Miễn phí</button>
				@else
					<button type="submit">Mua</button>
				@endif
			</form>
			<br/>
		@endforeach
	@endforeach
@else
	<p>Chưa có shop hoặc các shop đã hết hạn/đóng cửa! Mời quay lại sau</p>
@endif

@endsection
