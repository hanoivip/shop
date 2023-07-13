@extends('hanoivip::admin.layouts.admin')

@section('title', 'Define new shop')

@section('content')

<form method="post" action="{{ route('ecmin.shopv2.add') }}">
	{{ csrf_field() }}
	Shop title: <input id="name" name="name" value="" required/>
	<p>-------------------</p>
	Time condition (if any): <input id="starttime" name="starttime" type="date" value=""/><input id="starttime" name="starttime" type="date" value=""/>
	VIP condition (if any): <input id="viplv" name="viplv" value=""/>
	<button type="submit">Add Item</button>
</form>

@endsection