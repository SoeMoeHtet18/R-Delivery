@extends('admin.layouts.master')
@section('title','Collection')
@section('sub-title','Customer Collection Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Customer Collection Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('customer-collections.edit' , $customer_collection->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('customer-collections.destroy', $customer_collection->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this customer payment?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Customer Collection Code<b>:</b></h4>
                </div>
                <div class="col-10"> 
                    {{ $customer_collection->customer_collection_code }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Collection Group<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->order->collection_group != null) {{ $customer_collection->order->collection_group }}
                    @else N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Order Code<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->order->order_code != null) {{ $customer_collection->order->order_code }}
                    @else N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Customer Name<b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $customer_collection->order->customer_name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Shop<b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $customer_collection->order->shop->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Items<b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $customer_collection->items }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Paid Amount To Customer<b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $customer_collection->paid_amount }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Is Way Fees Payable<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->is_way_fees_payable == 0) False @else True @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Status<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->status == 'pending')
                    Pending
                    @elseif($customer_collection->status == 'in-warehouse')
                    In Warehouse
                    @else
                    Completed
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Note<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->note != null) {{ $customer_collection->note }}
                    @else N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Item Image<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->item_image != null)
                    <img src="{{asset('/storage/customer payment/' . $order->proof_of_payment)}}" alt="" style="width: 200px;">
                    @else N/A
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection