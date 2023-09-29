@extends('admin.layouts.master_new')
@section('style')
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <link href="{{asset('css/app.css')}}" rel="stylesheet">
<style>
    .page-content,
    .page-content::before {
        background: white !important;
    }
</style>
@endsection
@section('content')

    <div id="app">
        <setting
            :collection_method="'{{$collection_method}}'"
            :schedule_date="'{{$schedule_date}}'"
        ></setting>
    </div>
@endsection
@section('javascript')
<script src="{{ mix('js/app.js') }}"></script>
@endsection