@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Payment Type Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Payment Type Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('payment-types.edit' , $payment_type->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('payment-types.destroy', $payment_type->id)}}" method="post">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $payment_type->name }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection