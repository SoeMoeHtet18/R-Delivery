@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Collection Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Collections Detail</strong>
        </h2>

        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('collections.edit' , $collection->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('collections.destroy', $collection->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this collection?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Total Quantity <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collection->total_quantity }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collection->total_amount }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Paid Amount <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collection->paid_amount }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Collection Group Id <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collection->collection_group_id }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Rider <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($collection->rider_id != null)
                        {{ $collection->rider->name }}
                    @else
                     'N/A'
                    @endif 
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Shop <b>:</b></h4>
                </div>
                <div class="col-10">
                @if($collection->shop_id != null)
                        {{ $collection->shop->name }}
                @else
                    'N/A'
                @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Assigned At <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collection->assigned_at }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Collected At <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collection->collected_at }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Note <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collection->note }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Status <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collection->status }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Is Payable <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collection->is_payable }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection