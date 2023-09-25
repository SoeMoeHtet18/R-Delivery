@extends('vue-pages.layout.master')
@section('content')
    <create-bulk-order
        :schedule_date="'{{$schedule_date}}'"
    ></create-bulk-order>
@endsection