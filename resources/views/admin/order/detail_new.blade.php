@extends('admin.layouts.master_new')
@section('content')
<style>
    .page-content,
    .page-content::before {
        background: white !important;
    }

    .left-content {
        flex: 1;
    }

    .right-content {
        flex: 2;
        margin-left: 40px;
    }

    .order-detail-card {
        border-radius: 10px !important;
        background: #F1F5F5;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    }

    .yes-btn {
        background-color: green;
        color: white;
    }

    .no-btn {
        background-color: red;
        color: white;
    }

    .dropdown > .dropdown-menu:before {
        border-bottom: none;
        border-left: none;
    }

    .dropdown > .dropdown-menu:after {
        border-bottom: none;
    }
</style>
<div>
    <div class="d-flex align-items-center">
        <h4 class="m-0 me-3">
            <strong>Order ID: {{$order->order_code}}</strong>
        </h4>
        <div class="rounded-pill btn btn-{{$order->statusLabelColor}} status-box text-white"> 
            {{ $order->statusLabel }}
        </div>
    </div>
    <hr>
    
    <div class="card-toolbar">
        <div class="dropdown">
            
            <button class="btn btn-secondary green dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Actions
            </button>
            <ul class="dropdown-menu ">
            @if($order->status == 'cancel_request')
            <li>
            <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post" onclick="return confirm(`Are you sure you want to cancel this order?`);">
                @csrf
                @method('POST')
                <input type="hidden" value="cancel" name="status">
                <input type="submit" value="Approve Cancel" class="dropdown-item">
                </form>
            </li>
            <li>
                <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post">
                    @csrf
                    @method('POST')
                    <input type="hidden" value="delay" name="status">
                    <input type="submit" value="Reject Cancel" class="dropdown-item">
                </form>
            </li>
            @endif
                @if($order->status == 'pending')
                <li>
                    <a class="dropdown-item" id="assign-collection-group">Assign To Pick Up Group</a>
                </li>
                @endif
                @if($order->status == 'delay')
                <li>
                    <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post">
                        @csrf
                        @method('POST')
                        <input type="hidden" value="success" name="status">
                        <input type="submit" value="Delivered" class="btn btn-dark dropdown-item">
                    </form>
                </li>
                <li>
                    <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post">
                        @csrf
                        @method('POST')
                        <input type="hidden" value="warehouse" name="status">
                        <input type="submit" value="Assign To Warehouse" class="btn btn-secondary dropdown-item">
                    </form>
                </li>               
                @endif
                @if($order->status == 'warehouse' || $order->status == 'success')
                <li>
                    <a href="{{url('/customer-collections/create?order_id='.$order->id)}}" class="dropdown-item">Create Customer Collection</a>
                </li>
                @endif
                @if($order->payment_channel == 'shop_online_payment' && $order->is_payment_channel_confirm == false)
                <li>
                    <a href="{{url('/payment-channel-confirm/'.$order->id)}}" class="btn btn-secondary dropdown-item">Confirm Shop Payment</a>
                </li>
                @endif
                @if($order->payment_channel == 'company_online_payment' && $order->is_payment_channel_confirm == false)
                <li>
                    <a href="{{url('/payment-channel-confirm/'.$order->id)}}" class="btn btn-secondary dropdown-item">Confirm Company Payment</a>
                </li>
                @endif
                @if($order->status == 'pending' || $order->status == 'picking-up' || $order->status == 'cancel')
                <li>
                <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post">
                        @csrf
                        @method('POST')
                        <input type="hidden" value="warehouse" name="status">
                        <input type="submit" value="Assign To Warehouse" class="btn btn-dark dropdown-item">
                    </form>
                </li>
                @endif
                @if($order->status == 'pending' || $order->status == 'warehouse')
                <li>
                    <a href="{{url('/orders/'.$order->id.'/assign-rider')}}" class="btn btn-secondary dropdown-item">Assign Rider</a>
                </li>
                @endif
                <li><a href="{{route('orders.edit' , $order->id)}}" class="btn btn-light dropdown-item">Edit</a></li>
                <li> 
                    <form action="{{route('orders.destroy', $order->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this order?`);">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger dropdown-item">
                    </form>
                </li>
        
            </ul>
        </div>
    </div>
        
    <div class="d-flex">
        <div class="left-content">
            <div class="card order-detail-card">
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
            <div class="card order-detail-card">
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
            <div class="card order-detail-card">
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

            @if(in_array($order->status, ['cancel', 'cancel-request', 'delay']))
            <div class="card order-detail-card">
                <div class="card-body">
                    <div class="text-center">
                        <h5>
                            {{ $order->statusLabel}} Reason
                        </h5>
                    </div>
                    <hr>
                    <div class="text-center">
                        <b>{{ $order->note ? $order->note : 'N/A'}}</b>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="right-content">
            <div class="card order-detail-card">
                <div class="card-body">
                    <div class="text-start">
                        <h5>Customer</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 ms-5">
                            <b>NAME</b>
                            <br>
                            <b>PHONE NUMBER</b>
                            <br>
                            <b>CITY</b>
                            <br>
                            <b>TOWNSHIP</b>
                            <br>
                            <b>ADDRESS</b>
                        </div>
                        <div class="col-md-6">
                            <b>{{$order->customer_name}}</b>
                            <br>
                            <b>{{$order->customer_phone_number}}</b>
                            <br>
                            <b>{{$order->city->name}}</b>
                            <br>
                            <b>{{ $order->township ? $order->township->name : 'N/A'}}</b>
                            <br>
                            <b>{{ $order->full_address}}</b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card order-detail-card">
                <div class="card-body">
                    <div class="text-start">
                        <h5>Shop</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 ms-5">
                            <b>NAME</b>
                        </div>
                        <div class="col-md-6">
                            <b>{{$order->shop->name}}</b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card order-detail-card">
                <div class="card-body">
                <div class="text-start">
                        <h5>General</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 ms-5">
                            <b>ITEM TYPE</b>
                            <br>
                            <b>ORDER TYPE</b>
                            <br>
                            <b>COLLECTION METHOD</b>
                            <br>
                            <b>PAYMENT TYPE</b>
                            <br>
                            <b>PAYMENT CHANNEL</b>
                            <br>
                            <b>REMARK</b>
                        </div>
                        <div class="col-md-6">
                            <b>{{$order->itemType ? $order->itemType->name : 'N/A'}}</b>
                            <br>
                            <b>{{$order->delivery_type ? $order->delivery_type->name : 'N/A'}}</b>
                            <br>
                            <b>
                                @if($order->collection_method == 'dropoff')
                                    Drop Off
                                @else
                                    Pick Up
                                @endif
                            </b>
                            <br>
                            <b>
                                @if($order->payment_method == 'cash_on_delivery')
                                    Cash On Delivery
                                @elseif($order->payment_method == 'item_prepaid')
                                    Item Prepaid
                                @elseif($order->payment_method == 'all_prepaid')
                                    All Prepaid
                                @endif
                            </b>
                            <br>
                            <b>
                                @if($order->payment_channel == 'company_online_payment')
                                    Online Payment (Company)
                                @elseif($order->payment_channel == 'shop_online_payment')
                                    Online Payment (Shop)
                                @elseif($order->payment_channel == 'cash')
                                    Cash
                                @endif
                            </b>                            
                            <br>
                            <b>{{ $order->remark ? $order->remark : 'N/A'}}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection