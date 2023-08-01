@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Rider Payment Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Rider Payment Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('rider-payments.edit' , $rider_payment->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('rider-payments.destroy', $rider_payment->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this shop user?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Rider Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $rider_payment->rider->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $rider_payment->total_amount }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Total Routine <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $rider_payment->total_routine}}
                </div>
            </div>
            
            
        </div>
    </div>
</div>
@endsection