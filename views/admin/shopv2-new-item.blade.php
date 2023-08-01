@extends('hanoivip::admin.layouts.admin')

@section('title', 'Define new shop item')

@section('content')
<script>
var attrs = {};
function addAttr()
{
	var name = document.getElementById('attr_name').value;
	var value =  document.getElementById('attr_value').value;
	attrs[name] = value;
	// render for fun
	document.getElementById('attributes').innerHTML = JSON.stringify(attrs); 
}
function preSubmit()
{
	document.getElementById('meta').value = JSON.stringify(attrs);
	document.getElementById('shopv2_new_item_frm').submit();
}
</script>

<form class="form-horizontal" enctype="multipart/form-data" method="post" action="{{ route('ecmin.shopv2.additem') }}" id="shopv2_new_item_frm">
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
	Description: <textarea rows = "10" cols = "60" name="description" required></textarea><br/>
	Delivery type: <select id="delivery_type" name="delivery_type" required>
						<option value="1">Game role currency bag</option>
						<option value="2">Game role item bag</option>
						<option value="3">Web account</option>
						{{-- <option value="4">Game account</option> --}}
						<option value="5">Exchange account</option>
					</select>
	<input type="hidden" id="meta" name="meta" value=""/>
	<p>------------------</p>
	Attributes: <p id="attributes"></p>
	<p>------------------</p>
	Add Attributes: 
	Attr name <input type="text" id="attr_name" name="attr_name" value="" />
	Attr value <input type="text" id="attr_value" name="attr_value" value="" />
	<a onclick="addAttr()">Add attr</a>
	<p>------------------</p>
	<button type="submit" onclick="preSubmit()">Add Item</button>
</form>

@endsection