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

    .dropdown > .dropdown-menu:before {
        border-bottom: none;
        border-left: none;
    }

    .dropdown > .dropdown-menu:after {
        border-bottom: none;
    }
</style>
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Order Detail</strong>
        </h2>
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
        <div id="popupCard" class="modal mt-5">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header text-center">
                        <h5 class="modal-title text-center text-bold">Assign To Pick Up Group</h5>
                    </div>
                    <form action="{{url('/orders/'. $order->id . '/assign-collection-group')}}" method="post">
                        <!-- Modal Body -->
                        <div class="modal-body">

                            @csrf
                            @method('POST')
                            <div class="row m-0 mb-3">
                                <div>
                                    <h4>Pick Up Group</h4>
                                </div>
                                <div>
                                    <select name="collection_group_id" id="collection_group_id" class="form-control">
                                        <option value="" selected disabled>Select Pick Up Group</option>
                                        @foreach($collection_groups as $collection_group)
                                    <option value="{{$collection_group->id}}">{{$collection_group->collection_group_code}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="pop-up-close-btn" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" id="assign-collection-group-btn" class="btn green" data-dismiss="modal" value="Assign">
                        </div>
                    </form>
                </div>
            </div>
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
                    <h4>Extra Charges <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->delivery_fees != null) {{$order->delivery_fees}} @else {{'N/A'}} @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Discount <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($order->discount != null) {{$order->discount}} @else N/A @endif
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
                    <h4>Delivery Type <b>:</b></h4>
                </div>
                <div class="col-10">
                @if($order->delivery_type != null) {{$order->delivery_type->name}} @else {{'N/A'}} @endif
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
                    <h4>Remaining Amount Subtraction <b>:</b></h4>
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
@section('javascript')
<script>
   
    $('#assign-collection-group').click(function() {
        var popupCard = document.getElementById('popupCard');
            popupCard.style.display = 'block';
            $('#collection_group_id').select2({width: '100%'});
    });

    $('#pop-up-close-btn').click(function() {
        var popupCard = document.getElementById('popupCard');
            popupCard.style.display = 'none';
        });
</script>

@endsection