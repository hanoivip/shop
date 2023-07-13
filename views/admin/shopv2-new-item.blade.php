@extends('hanoivip::admin.layouts.admin')

@section('title', 'Define new shop item')

@section('content')

<form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{ route('ecmin.shopv2.additem') }}">
	{{ csrf_field() }}
	<input type="hidden" id="slug" name="slug" value="{{$slug}}"/>
	Item title: <input id="title" name="title" value="" required class="form-control" />
	Item code: <input id="code" name="code" value="" required class="form-control" />
	Origin price: <input id="origin_price" name="origin_price" value="" required class="form-control" />
	Price: <input id="price" name="price" value="" required class="form-control" />
	Currency: <select id="currency" name="currency">
						<option value="VND">VND</option>
						<option value="USD">USD</option>
					</select>
	Images: <input type="file" class="form-control" name="images[]" multiple required>
	Description: <textarea rows = "10" cols = "60" name="description" required></textarea>
	Delivery type: <select id="delivery_type" name="delivery_type" required>
						<option value="1">Game role currency bag</option>
						<option value="2">Game role item bag</option>
						<option value="3">Web account</option>
						<option value="4">Game account</option>
					</select>
	<button type="submit">Add</button>
</form>

@endsection