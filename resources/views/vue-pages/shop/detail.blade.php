@extends('vue-pages.layout.master')
@section('content')
    <shop-detail :id="{{$shop_id}}"></shop-detail>
@endsection