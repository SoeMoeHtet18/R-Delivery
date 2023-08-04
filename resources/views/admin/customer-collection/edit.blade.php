@extends('admin.layouts.master')
@section('title','Collections')
@section('sub-title','Customer Exchange Editing')
@section('style')
<style>
    .tabs {
        width: 150px;
        height: 40px;
        border: 1px solid #64C5B1;
        cursor: pointer;
    }

    .text-green {
        color: #64C5B1;
    }

    .bg-cyan {
        background-color: #64C5B1;
    }
</style>
@endsection
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Exchange Collection</strong>
        </h2>
        <form action="{{route('customer-collections.update', $customer_collection->id)}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row m-0 mb-3">
                <label for="customer_collection_code" class="col-2">
                    <h4>Customer Exchange Code<b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="customer_collection_code" name="customer_collection_code" value="{{$customer_collection->customer_collection_code}}" class="form-control" readonly />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="collection_group_id" class="col-2">
                    <h4>Collection Group <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="collection_group_id" id="collection_group_id" class="form-control">
                        <option value="" selected disabled>Select Collection Group for This Collection</option>
                        @foreach ( $collection_groups as $collection_group)
                        <option value="{{$collection_group->id}}" @if($customer_collection->collection_group_id == $collection_group->id) {{'selected'}} @endif>{{$collection_group->collection_group_code}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="order_id" class="col-2">
                    <h4>Order <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="order_id" id="order_id" class="form-control">
                        <option value="" selected disabled>Select Order for This Collection</option>
                        @foreach ( $orders as $order)
                        <option value="{{$order->id}}" @if($customer_collection->order_id == $order->id) {{'selected'}} @endif>{{$order->order_code}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="items" class="col-2">
                    <h4>Item<b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="items" name="items" value="{{$customer_collection->items}}" class="form-control" />
                    @if ($errors->has('items'))
                    <span class="text-danger"><strong>{{ $errors->first('items') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="paid_amount" class="col-2">
                    <h4>Paid Amount<b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="paid_amount" name="paid_amount" value="{{$customer_collection->paid_amount}}" class="form-control" />
                    @if ($errors->has('paid_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('paid_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="status" class="col-2">
                    <h4>Status <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="status" id="status_id" class="form-control">
                        <option value="" selected disabled>Select Status for This Customer Collection</option>
                        <option value="pending" @if($customer_collection->status == "pending") {{'selected'}} @endif>Pending</option>
                        <option value="in-warehouse" @if($customer_collection->status == "in-warehouse") {{'selected'}} @endif>In Warehouse</option>
                        <option value="complete" @if($customer_collection->status == "complete") {{'selected'}} @endif>Completed</option>
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="note" class="col-2">
                    <h4>Note <b>:</b></h4>
                </label>
                <div class="col-10">
                    <textarea id="note" name="note" class="form-control" style="height: 100px">{{$customer_collection->note}}</textarea>
                </div>
            </div>

            <div class="row m-0 mb-3">
                <label>
                    <h4>Is Way Fees Payable</h4>
                </label>
                <div id="tabs-container" class="d-flex mt-2">
                    <div id="tab-one" class="tabs tab-one d-flex justify-content-center align-items-center text-green">Yes</div>
                    <div id="tab-two" class="tabs tab-two d-flex justify-content-center align-items-center text-green">No</div>
                </div>
            </div>
            <input type="hidden" name="is_way_fees_payable" id="is_way_fees_payable" value="{{$customer_collection->is_way_fees_payable}}">
            <div class="footer-button float-end">
                <a href="{{url()->previous() }}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script type="text/javascript">
    $('#status_id').select2();
    $('#collection_group_id').select2();
    $('#order_id').select2();

    var isWayFeesPayable = $('#is_way_fees_payable').val();
    if (isWayFeesPayable) {
        $('#tab-one').addClass('bg-cyan text-white clicked');
    } else {
        $('#tab-two').addClass('bg-cyan text-white clicked');
    }
    // Attach the click event to the parent container
    $('#tabs-container').on('click', '.tabs', function() {
        var $this = $(this);
        var $tabs = $('.tabs'); // Cache all tabs

        $tabs.not($this).removeClass('bg-cyan text-white clicked'); // Remove classes from other tabs
        $this.addClass('bg-cyan text-white clicked'); // Add classes to the clicked tab

        if ($this.attr('id') == 'tab-one') {
            $('#is_way_fees_payable').val(1);
        } else {
            $('#is_way_fees_payable').val(0);
        }
    });
</script>
@endsection