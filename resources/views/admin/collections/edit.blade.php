@extends('admin.layouts.master')
@section('title','Collections')
@section('sub-title','Pick Up Editing')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Pick Up</strong>
        </h2>
        <form action="{{route('collections.update', $collection->id)}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row m-0 mb-3">
                <label for="total_quantity" class="col-2">
                    <h4>Total Quantity of Pick Up <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_quantity" name="total_quantity" value="{{$collection->total_quantity}}" class="form-control"/>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount of Pick Up <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount" value="{{$collection->total_amount}}" class="form-control"/>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="paid_amount" class="col-2">
                    <h4>Paid Amount By Rider <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="paid_amount" name="paid_amount" value="{{$collection->paid_amount}}" class="form-control" />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="collection_group_id" class="col-2">
                    <h4>Collection Group <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="collection_group_id" id="collection_group_id" class="form-control">
                        <option value="" selected disabled>Select the Collection Group for This Collection</option>
                        @foreach ( $collection_groups as $collection_group)
                        <option value="{{$collection_group->id}}" @if($collection->collection_group_id == $collection_group->id) {{'selected'}} @endif>{{$collection_group->collection_group_code}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="shop_id" class="col-2">
                    <h4>Shop <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="shop_id" id="shop_id" class="form-control">
                        <option value="" selected disabled>Select the Shop for This Collection</option>
                        @foreach ( $shops as $shop)
                        <option value="{{$shop->id}}" @if($collection->shop_id == $shop->id) {{'selected'}} @endif>{{$shop->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Status <b>:</b></h4>
                </div>
                <div class="col-10">
                    <select name="status" id="status" class="form-control">
                        <option value="" selected disabled>Select the Status for This Order</option>
                        <option value="pending" @if($collection->status == "pending") {{'selected'}} @endif>Pending</option>
                        <option value="in-warehouse" @if($collection->status == "in-warehouse") {{'selected'}} @endif>In Warehouse</option>
                        <option value="complete" @if($collection->status == "complete") {{'selected'}} @endif>Complete</option>
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Note <b>:</b></h4>
                </div>
                <div class="col-10">
                    <textarea id="note" style="height:100px" name="note" class="form-control">{{$collection->note}}</textarea>
                </div>
            </div>
            
            <div class="footer-button float-end">
                <a href="{{url()->previous() }}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        $('#collection_group_id').select2();
        $('#rider_id').select2();
        $('#shop_id').select2();
        $('#status').select2();
    });
</script>
@endsection