@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Order Editing')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Order</strong>
        </h2>
        <form action="{{route('orders.update', $order->id)}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row m-0 mb-3">
                <label for="order_code" class="col-2">
                    <h4>Order Code <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="order_code" name="order_code" value="{{$order->order_code}}" class="form-control" readonly />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="shop_id" class="col-2">
                    <h4>Shop Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="shop_id" id="shop_id" class="form-control">
                        <option value="" selected disabled>Select the Shop for This Order</option>
                        @foreach ( $shops as $shop)
                        <option value="{{$shop->id}}" @if($order->shop_id == $shop->id) {{'selected'}} @endif>{{$shop->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="customer_phone_number" class="col-2">
                    <h4>Customer PhoneNumber <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="customer_phone_number" name="customer_phone_number" value="{{$order->customer_phone_number}}" class="form-control" />
                    @if ($errors->has('customer_phone_number'))
                    <span class="text-danger"><strong>{{ $errors->first('customer_phone_number') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="customer_name" class="col-2">
                    <h4>Customer Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="customer_name" name="customer_name" value="{{$order->customer_name}}" class="form-control" />
                    @if ($errors->has('customer_name'))
                    <span class="text-danger"><strong>{{ $errors->first('customer_name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="city_id" class="col-2">
                    <h4>City Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="city_id" id="city_id" class="form-control">
                        <option value="" selected disabled>Select the City for This Order</option>
                        @foreach ( $cities as $city)
                        <option value="{{$city->id}}" @if($order->city_id == $city->id) {{'selected'}}@endif>{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="township_id" class="col-2">
                    <h4>Township Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="township_id" id="township_id" class="form-control">
                        <option value="" selected disabled>Select the Township for This Order</option>
                        @foreach ( $townships as $township)
                        <option value="{{$township->id}}" @if($order->township_id == $township->id) {{'selected'}} @endif>{{$township->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="rider_id" class="col-2">
                    <h4>Rider Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="rider_id" id="rider_id" class="form-control">
                        <option value="" selected disabled>Select the Rider for This Order</option>
                        @foreach ( $riders as $rider)
                        <option value="{{$rider->id}}" @if($order->rider_id == $rider->id) {{'selected'}} @endif>{{$rider->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="delivery_fees" class="col-2">
                    <h4>Delivery Fees <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="delivery_fees" name="delivery_fees" value="{{$order->delivery_fees}}" class="form-control" />
                    @if ($errors->has('delivery_fees'))
                    <span class="text-danger"><strong>{{ $errors->first('delivery_fees') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount" value="{{$order->total_amount}}" class="form-control" />
                    @if ($errors->has('total_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="markup_delivery_fees" class="col-2">
                    <h4>Markup Delivery Fees <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="markup_delivery_fees" name="markup_delivery_fees" value="{{$order->markup_delivery_fees}}" class="form-control" />
                    @if ($errors->has('markup_delivery_fees'))
                    <span class="text-danger"><strong>{{ $errors->first('markup_delivery_fees') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="remark" class="col-2">
                    <h4>Remark <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="remark" name="remark" value="{{$order->remark}}" class="form-control" />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="status" class="col-2">
                    <h4>Status <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="status" id="status_id" class="form-control">
                        <option value="" selected disabled>Select Status for This Order</option>
                        <option value="pending" @if($order->status == "pending") {{'selected'}} @endif>Pending</option>
                        <option value="success" @if($order->status == "success") {{'selected'}} @endif>Success</option>
                        <option value="delay" @if($order->status == "delay") {{'selected'}} @endif>Delay</option>
                        <option value="cancel" @if($order->status == "cancel") {{'selected'}} @endif>Cancel</option>
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="item_type" class="col-2">
                    <h4>Item Type <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="item_type" id="item_type_id" class="form-control">
                        <option value="" selected disabled>Select Item Type for This Order</option>
                        @foreach($item_types as $item_type)
                        <option value="{{$item_type->name}}" @if($order->item_type == $item_type->name) {{'selected'}} @endif>{{$item_type->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="full_address" class="col-2">
                    <h4>Address <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="full_address" name="full_address" value="{{$order->full_address}}" class="form-control" />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="type" class="col-2">
                    <h4>Type <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="type" id="type_id" class="form-control">
                        <option value="" selected disabled>Select the Type for This Order</option>
                        <option value="standard" @if($order->type == "standard") {{'selected'}} @endif>Standard</option>
                        <option value="express" @if($order->type == "express") {{'selected'}} @endif>Express</option>
                        <option value="doortodoor" @if($order->type == "doortodoor") {{'selected'}} @endif>Door To Door</option>
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="schedule_date" class="col-2">
                    <h4>Schedule Date <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="date" id="schedule_date" name="schedule_date" value="{{$scheduledate}}" class="form-control" />
                    @if ($errors->has('schedule_date'))
                    <span class="text-danger"><strong>{{ $errors->first('schedule_date') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="collection_method" class="col-2">
                    <h4>Collection Method <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="collection_method" id="collection_method_id" class="form-control">
                        <option value="" selected disabled>Select the Collection Method for This Order</option>
                        <option value="pickup" @if($order->collection_method == "pickup") {{'selected'}} @endif>PickUp</option>
                        <option value="dropoff" @if($order->collection_method == "dropoff") {{'selected'}} @endif>Drop Off</option>
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="proof_of_payment" class="col-2">
                    <h4>Proof of Payment <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="file" id="proof_of_payment" name="proof_of_payment" class="form-control" />
                    @if ($errors->has('proof_of_payment'))
                    <span class="text-danger"><strong>{{ $errors->first('proof_of_payment') }}</strong></span>
                    @endif
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
        $('#city_id').select2();
        $('#township_id').select2();
        $('#rider_id').select2();
        $('#shop_id').select2();
        $('#status_id').select2();
        $('#item_type_id').select2();
        $('#type_id').select2();
        $('#collection_method_id').select2();

        $('#type_id').change(function() {
            console.log($('#type_id').val());
            if($('#type_id').val() == 'doortodoor') {
            var today = new Date();
            var futureDate = new Date(today.getTime() + (5 * 24 * 60 * 60 * 1000));

            var formattedDate = futureDate.toISOString().split('T')[0];

            $('#schedule_date').val(formattedDate);

            }
        });

        $('#city_id').change(function() {
            console.log('success');
            var city_id = $('#city_id').val();
            $.ajax({
                url: '/api/townships-get-by-city',
                type: 'POST',
                dataType: 'json',
                data: {
                    city_id: city_id
                },
                success: function(response) {
                    var townships = '<option value="" selected disabled>Select the Township for This Order</option>';
                    if (response.data) {
                        for (let i = 0; i < response.data.length; i++) {
                            townships += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>';
                        }
                    }
                    $('#township_id').html(townships);
                    var riders = '<option value="" selected disabled>Select the Rider for This Order</option>';
                    $('#rider_id').html(riders);
                },
            })
        });

        $('#township_id').change(function() {
            console.log('township changed');
            var township_id = $('#township_id').val();
            console.log(township_id)
            $.ajax({
                url: '/api/riders-get-by-township',
                type: 'POST',
                dataType: 'json',
                data: {
                    township_id: township_id
                },
                success: function(response) {
                    var riders = '<option value="" selected disabled>Select the Rider for This Order</option>';
                    if (response.data) {
                        for (let i = 0; i < response.data.length; i++) {
                            riders += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>';
                        }
                    }
                    $('#rider_id').html(riders);
                },
            })
        });
    });
</script>
@endsection