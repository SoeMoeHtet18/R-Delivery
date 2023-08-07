@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Order Detail')
@section('content')
<style>
    .yes-btn {
        background-color: green;
        color: white;
    }

    .no-btn {
        background-color: red;
        color: white;
    }
</style>
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Order Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div>
                <a href="{{url('/customer-collections/create?order_id='.$order->id)}}" class="btn portlet green me-3">Create Customer Collection</a>
            </div>
            @if($order->status == 'delivering' && $order->payment_channel == 'shop_online_payment' && $order->is_payment_channel_confirm == false)
            <div>
                <a href="{{url('/payment-channel-confirm/'.$order->id)}}" class="btn btn-secondary me-3">Confirm Shop Payment</a>
            </div>
            @endif
            @if($order->status == 'delivering' && $order->payment_channel == 'company_online_payment' && $order->is_payment_channel_confirm == false)
            <div>
                <a href="{{url('/payment-channel-confirm/'.$order->id)}}" class="btn btn-secondary me-3">Confirm Company Payment</a>
            </div>
            @endif
            @if($order->status == 'pending' || $order->status == 'warehouse')
            <div>
                <a href="{{url('/orders/'.$order->id.'/assign-rider')}}" class="btn btn-secondary me-3">Assign Rider</a>
            </div>
            @endif
            <div class="create-button">
                <a href="{{route('orders.edit' , $order->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('orders.destroy', $order->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this order?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="d-flex justify-content-end">
            @if($order->status == 'delay' || $order->status == 'cancel_request')
            <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post">
                @csrf
                @method('POST')
                <input type="hidden" value="success" name="status">
                <input type="submit" value="Delivered" class="btn btn-dark me-2">
            </form>
            <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post" onclick="return confirm(`Are you sure you want to cancel this order?`);">
                @csrf
                @method('POST')
                <input type="hidden" value="cancel" name="status">
                <input type="submit" value="{{ $order->status == 'cancel_request' ? 'Approve Cancel' : 'Cancel' }}" class="btn btn-success">
            </form>
                @if($order->status == 'cancel_request')
                <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post">
                    @csrf
                    @method('POST')
                    <input type="hidden" value="warehouse" name="status">
                    <input type="submit" value="Reject Cancel" class="btn btn-danger ms-2">
                </form>
                @endif
            @endif
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Order Code <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->order_code }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Shop Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->shop->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Customer PhoneNumber <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->customer_phone_number }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Customer Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->customer_name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>City Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->city->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Township Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->township->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Rider Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->rider != null) {{$order->rider->name}} @else {{'N/A'}} @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->total_amount }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Delivery Fees <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->delivery_fees }}
                </div>
            </div>
            <!-- <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Markup Delivery Fees <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->markup_delivery_fees }}
                </div>
            </div> -->
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Extra Charges <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->delivery_fees != null) {{$order->delivery_fees}} @else {{'N/A'}} @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Remark <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->remark != null) {{$order->remark}} @else {{'N/A'}} @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Status <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->status == 'pending')
                    Pending
                    @elseif($order->status == 'picking-up')
                    Picking Up
                    @elseif($order->status == 'warehouse')
                    In Warehouse
                    @elseif($order->status == 'delivering')
                    Delivering
                    @elseif($order->status == 'success')
                    Delivered
                    @elseif($order->status == 'delay')
                    Delay
                    @elseif($order->status == 'cancel')
                    Cancel
                    @else
                    Cancel Request
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Item Type <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->itemType != null) {{$order->itemType->name}} @else {{'N/A'}} @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Address <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->full_address != null) {{$order->full_address}} @else {{'N/A'}} @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Schedule Date <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ \Carbon\Carbon::parse($order->schedule_date)->format('d-m-Y') }}
                </div>

            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Type <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->type == 'standard')
                    Standard
                    @elseif($order->type == 'express')
                    Express
                    @else
                    Door To Door
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Collection Method <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->collection_method == 'dropoff')
                    Drop Off
                    @else
                    Pick Up
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Proof of Payment <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->proof_of_payment != null)
                    <img src="{{asset('/storage/customer payment/' . $order->proof_of_payment)}}" alt="" style="width: 200px;">
                    @else
                    {{' N/A '}}
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Payment Method <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->payment_method == 'cash_on_delivery')
                    Cash On Delivery
                    @elseif($order->payment_method == 'item_prepaid')
                    Item Prepaid
                    @elseif($order->payment_method == 'all_prepaid')
                    All Prepaid
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Note <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->note)
                    {{ $order->note }}
                    @else {{'N/A'}} @endif
                </div>
            </div>
            @if($order->status == 'cancel' && $order->payable_or_not == 'pending')
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Remaining Amount Substraction <b>:</b></h4>
                </div>
                <div class="col-10">
                    <div class="create-button">
                        <a href="{{url('/remaining-amount-confirm/'.$order->id)}}" class="btn btn-dark yes-btn ">Yes</a>
                        <a href="{{url('/remaining-amount-cancel/'.$order->id)}}" class="btn btn-light no-btn">No</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection