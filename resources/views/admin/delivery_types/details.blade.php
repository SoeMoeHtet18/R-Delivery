@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Delivery Type Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Delivery Type Detail</strong>
        </h2>

        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('delivery-types.edit' , $deliveryType->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('delivery-types.destroy', $deliveryType->id)}}" method="post" onclick="return confirm(`Are you sure you want to delete this delivery type?`);">
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
                    {{ $deliveryType->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Notified On <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $deliveryType->notified_on }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection