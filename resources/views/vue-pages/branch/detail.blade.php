@extends('vue-pages.layout.master')
@section('content')
    <branch-detail :id="{{$branchId}}" />
@endsection