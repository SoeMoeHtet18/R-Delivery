@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Order Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Order Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div>
                <a href="{{url('/orders/'.$order->id.'/assign-rider')}}" class="btn btn-secondary me-3">Assign Rider</a>
            </div>
            <div class="create-button">
                <a href="{{route('orders.edit' , $order->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('orders.destroy', $order->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this order?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
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
                    @if($order->rider !== null){{ $order->rider->name }} @endif
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
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Markup Delivery Fees <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->markup_delivery_fees }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Remark <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->remark }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Status <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->status == 'pending')
                    Pending
                    @elseif($order->status == 'success')
                    Success
                    @elseif($order->status == 'delay')
                    Delay
                    @else
                    Cancel
                    @endif
                </div>

            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Item Type <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->itemType->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Address <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->full_address }}
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
                    <img src="{{asset('/storage/customer payment/' . $order->proof_of_payment)}}" alt="" style="width: 200px;">
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Payment Method <b>:</b></h4>
                </div>
                <div class="col-10">
                @if($order->payment_method == 'cash-on-delivery')
                    Cash On Delivery
                    @elseif($order->payment_method == 'item-prepaid')
                    Item Prepaid
                    @elseif($order->payment_method == 'all-prepaid')
                    All Prepaid
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Note <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $order->note }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection