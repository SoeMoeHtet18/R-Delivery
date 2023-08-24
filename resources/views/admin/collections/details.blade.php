@extends('admin.layouts.master')
@section('title','Collections')
@section('sub-title','Pick Up Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Pick Up Detail</strong>
        </h2>

        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('collections.edit' , $collection->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('collections.destroy', $collection->id)}}" method="post" onclick="return confirm(`Are you sure you want to delete this pick up?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Pick Up Code <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collection->collection_code }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Pick Up Group Code <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($collection->collection_group != null)
                    {{ $collection->collection_group->collection_group_code }}
                    @else
                    N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Total Quantity of Pick Up <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ number_format($collection->total_quantity, 0, '.', ',') }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Total Amount of Pick Up <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ number_format($collection->total_amount, 2, '.', ',') }} MMK
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Paid Amount By Rider <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ number_format($collection->paid_amount, 2, '.', ',') }} MMK
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Rider <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($collection->rider_id != null)
                    <a href="/riders/{{ $collection->rider_id }}"> {{$collection->rider->name}}</a>
                    @else
                    N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Shop <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($collection->shop_id != null)
                    <a href="/shops/{{ $collection->shop_id }}"> {{$collection->shop->name}}</a>
                    @else
                    N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Status <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($collection->status == 'pending')
                    Pending @elseif($collection->status == 'complete')
                    Completed
                    @else
                    In Warehouse
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Is Payable <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($collection->is_payable == 0)
                    No
                    @else
                    Yes
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Note <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($collection->note != null)
                    {{ $collection->note }}
                    @else
                    N/A
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection