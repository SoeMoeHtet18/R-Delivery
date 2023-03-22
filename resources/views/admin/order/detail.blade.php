@extends('admin.layouts.master')

@section('content')
<style>
    .card-toolbar{
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
    }
    .create-button { 
        width: 70px;
        height: 30px;
        margin-bottom: 10px;
    }
</style>
        <div class="card card-container">
            <div class="card-body">
                    <h2 class="ps-1">
                        <strong>Order Detail</strong>
                    </h2>
                    <div class="card-toolbar">
                    <div class="create-button">
                    <a href="{{route('orders.edit' , $order->id)}}" class="btn btn-light">Edit</a>
                    </div>
                    <form action="{{route('orders.destroy', $order->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this order?`);">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger float-end">
                    </form>
                </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Order Code <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->order_code }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Customer Name <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->customer_name }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Customer PhoneNumber <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->customer_phone_number }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>City Name <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->city->name }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Township Name <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->township->name }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Rider Name <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            @if($order->rider !== null){{ $order->rider->name }} @endif
                        </div>
                    </div><div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Shop Name <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->shop->name }}
                        </div>
                    </div><div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Quantity <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->quantity }}
                        </div>
                    </div><div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Delivery Fees <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->delivery_fees }}
                        </div>
                    </div><div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Markup Delivery Fees <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->markup_delivery_fees }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Remark <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->remark }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Status <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->status }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Item Type <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->item_type }}
                        </div>
                    </div><div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Address <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->full_address }}
                        </div>
                    </div><div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Schedule Date <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->schedule_date }}
                        </div>
                    </div><div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Type <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->type }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Collection Method <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->collection_method }}
                        </div>
                    </div><div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Proof of Payment <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $order->proof_of_payment }}
                        </div>
                    </div>
            </div>
        </div>
@endsection