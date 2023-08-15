@extends('admin.layouts.master_new')
@section('content')
<style>
    .page-content::before {
        background: none !important;
    }

    .left-content {
        flex: 1;
    }

    .right-content {
        flex: 2;
        margin-left: 40px;
    }
</style>
<div>
    <div class="d-flex align-items-center">
        <h4 class="m-0 me-3">
            <strong>Order ID: {{$order->order_code}}</strong>
        </h4>
        <div class="rounded-pill btn btn-warning status-box text-white"> @if($order->status == 'pending')
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
    <hr>
    <div class="d-flex">
        <div class="left-content">
            <div class="card">
                <div class="card-body ">
                    <div class="text-center">
                        <h5>Cash To Collect</h5>
                        <b>{{ $order->total_amount + $order->delivery_fees + $order->markup_delivery_fees + $order->extra_charges - $order->discount }} MMK</b>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <b>ORDER AMOUNT</b><b>{{$order->total_amount}} MMK</b>
                    </div>
                    <div class="d-flex justify-content-between">
                        <b>DELIVERY FEES</b><b> @if($order->discount == null || $order->discount == 0)
                            {{ $order->delivery_fees }}
                            @else
                            {{ $order->delivery_fees - $order->discount }}
                            @endif
                            MMK</b>
                    </div>
                    <div class="d-flex justify-content-between">
                        <b>MARKUP DELIVERY FEES</b><b>{{$order->markup_delivery_fees}} MMK</b>
                    </div>
                    <div class="d-flex justify-content-between">
                        <b>EXTRA CHARGES</b><b> @if($order->extra_charges == null || $order->extra_charges == 0)
                            0.00
                            @else
                            {{ $order->extra_charges }}
                            @endif
                            MMK</b>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <h5>Schedule Date</h5>
                        <hr>
                        <b>
                            {{ $order->schedule_date ? \Carbon\Carbon::parse($order->schedule_date)->format('j F, Y') : 'N/A'}}
                        </b>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <h5>Rider</h5>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-around">
                        <b>Name</b><b>{{ $order->rider ? $order->rider->name : 'N/A'}}</b>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-content">
            <div class="card">
                <div class="card-body">

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <h5>Shop</h5>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-around">
                        <b>Name</b><b>{{$order->shop->name}}</b>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>

@endsection