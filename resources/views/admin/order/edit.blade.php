@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Order Editing')
@section('content')
<style>
    #second_amount{
        display: flex;
        /* justify-content: space-between; */
    }
    #total_amount_2 {
        padding-left: 2%;
        flex: 1;
    }
    #actual_amount_1 {
        flex: 1;
    }
</style>
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Order</strong>
        </h2>
        <form action="{{route('orders.update', $order->id)}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="shop_id">
                        <h4>Shop Name <b>:</b></h4>
                    </label>
                    <div>
                        <select name="shop_id" id="shop_id" class="form-control">
                            <option value="" selected disabled>Select the Shop for This Order</option>
                            @foreach ( $shops as $shop)
                            <option value="{{$shop->id}}" @if($order->shop_id == $shop->id) {{'selected'}} @endif>{{$shop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label for="order_code">
                        <h4>Order Code <b>:</b></h4>
                    </label>
                    <div>
                        <input type="text" id="order_code" name="order_code" value="{{$order->order_code}}" class="form-control" readonly />
                    </div>
                </div>
                
                
            </div>
            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="customer_phone_number">
                        <h4>Customer PhoneNumber <b>:</b></h4>
                    </label>
                    <div>
                        <input type="text" id="customer_phone_number" name="customer_phone_number" value="{{$order->customer_phone_number}}" class="form-control" />
                        @if ($errors->has('customer_phone_number'))
                        <span class="text-danger"><strong>{{ $errors->first('customer_phone_number') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <label for="customer_name">
                        <h4>Customer Name <b>:</b></h4>
                    </label>
                    <div>
                        <input type="text" id="customer_name" name="customer_name" value="{{$order->customer_name}}" class="form-control" />
                        @if ($errors->has('customer_name'))
                        <span class="text-danger"><strong>{{ $errors->first('customer_name') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="city_id">
                        <h4>City Name <b>:</b></h4>
                    </label>
                    <div>
                        <select name="city_id" id="city_id" class="form-control">
                            <option value="" selected disabled>Select the City for This Order</option>
                            @foreach ( $cities as $city)
                            <option value="{{$city->id}}" @if($order->city_id == $city->id) {{'selected'}}@endif>{{$city->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label for="township_id">
                        <h4>Township Name <b>:</b></h4>
                    </label>
                    <div>
                        <select name="township_id" id="township_id" class="form-control">
                            <option value="" selected disabled>Select the Township for This Order</option>
                            @foreach ( $townships as $township)
                            <option value="{{$township->id}}" @if($order->township_id == $township->id) {{'selected'}} @endif>{{$township->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="rider_id">
                        <h4>Rider Name <b>:</b></h4>
                    </label>
                    <div>
                        <select name="rider_id" id="rider_id" class="form-control">
                            <option value="" selected disabled>Select the Rider for This Order</option>
                            @foreach ( $riders as $rider)
                            <option value="{{$rider->id}}" @if($order->rider_id == $rider->id) {{'selected'}} @endif>{{$rider->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label for="delivery_fees">
                        <h4>Delivery Fees <b>:</b></h4>
                    </label>
                    <div>
                        <input type="text" id="delivery_fees" name="delivery_fees" value="{{$order->delivery_fees}}" class="form-control" />
                        @if ($errors->has('delivery_fees'))
                        <span class="text-danger"><strong>{{ $errors->first('delivery_fees') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="markup_delivery_fees">
                        <h4>Markup Delivery Fees <b>:</b></h4>
                    </label>
                    <div>
                        <input type="text" id="markup_delivery_fees" name="markup_delivery_fees" value="{{$order->markup_delivery_fees}}" class="form-control" />
                        @if ($errors->has('markup_delivery_fees'))
                        <span class="text-danger"><strong>{{ $errors->first('markup_delivery_fees') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <label for="extra_charges">
                        <h4>Extra Charges <b>:</b></h4>
                    </label>
                    <div>
                        <input type="text" id="extra_charges" name="extra_charges" value="{{$order->extra_charges}}" class="form-control" />
                        @if ($errors->has('extra_charges'))
                        <span class="text-danger"><strong>{{ $errors->first('extra_charges') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="d-flex align-items-center m-0 ms-3 col-6">
                <div>
                <input type="checkbox" id="is_deli_free" name="is_deli_free" value="true" @if($order->is_deli_free == true) checked @endif/>
                </div>
                <label for="is_deli_free" class="ms-2">
                    <h4>Is Delivery Free</h4>
                </label>
                    
                @if ($errors->has('is_deli_free'))
                <span class="text-danger"><strong>{{ $errors->first('is_deli_free') }}</strong></span>
                @endif
            </div>

            <div class="row m-0 mb-3">
                <div class="col" id="total_amount_1">
                    <label for="total_amount">
                        <h4>Total Amount <b>:</b></h4>
                    </label>
                    <div >
                        <input type="text" id="total_amount" name="total_amount" value="{{$order->total_amount}}" class="form-control" />
                        @if ($errors->has('total_amount'))
                        <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="col" id="second_amount">
                    <div class="col-6" id="actual_amount_1">
                        <label for="actual_amount">
                            <h4>Actual Amount <b>:</b></h4>
                        </label>
                        <div >
                            <input type="text" id="actual_amount" name="actual_amount" value="{{old('actual_amount')}}" class="form-control" />
                            @if ($errors->has('actual_amount'))
                            <span class="text-danger"><strong>{{ $errors->first('actual_amount') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div  class="col-6" id="total_amount_2">
                        <label for="second_total_amount">
                            <h4>Total Amount <b>:</b></h4>
                        </label>
                        <div>
                            <input type="text" id="second_total_amount" name="second_total_amount" value="{{old('second_total_amount')}}" class="form-control" disabled/>
                            @if ($errors->has('second_total_amount'))
                            <span class="text-danger"><strong>{{ $errors->first('second_total_amount') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="schedule_date">
                        <h4>Schedule Date <b>:</b></h4>
                    </label>
                    <div>
                        <input type="date" id="schedule_date" name="schedule_date" value="{{$scheduledate}}" class="form-control" />
                        @if ($errors->has('schedule_date'))
                        <span class="text-danger"><strong>{{ $errors->first('schedule_date') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <label for="status">
                        <h4>Status <b>:</b></h4>
                    </label>
                    <div>
                        <select name="status" id="status_id" class="form-control">
                            <option value="" selected disabled>Select Status for This Order</option>
                            <option value="pending" @if($order->status == "pending") {{'selected'}} @endif>Pending</option>
                            <option value="picking-up" @if($order->status == "picking-up") {{'selected'}} @endif>Picking Up</option>
                            <option value="warehouse" @if($order->status == "warehouse") {{'selected'}} @endif>In Warehouse</option>
                            <option value="delivering" @if($order->status == "delivering") {{'selected'}} @endif>Delivering</option>
                            <option value="success" @if($order->status == "success") {{'selected'}} @endif>Delivered</option>
                            <option value="delay" @if($order->status == "delay") {{'selected'}} @endif>Delay</option>
                            <option value="cancel" @if($order->status == "cancel") {{'selected'}} @endif>Cancel</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="full_address">
                        <h4>Address <b>:</b></h4>
                    </label>
                    <div>
                        <textarea id="full_address" name="full_address" class="form-control" style="height: 100px">{{$order->full_address}}</textarea>
                    </div>
                </div>
                <div class="col">
                    <label for="remark">
                        <h4>Remark <b>:</b></h4>
                    </label>
                    <div>
                        <textarea id="remark" name="remark" class="form-control" style="height: 100px">{{$order->remark}}</textarea>
                    </div>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="item_type_id">
                        <h4>Item Type <b>:</b></h4>
                    </label>
                    <div>
                        <select name="item_type_id" id="item_type_id" class="form-control">
                            <option value="" selected disabled>Select Item Type for This Order</option>
                            @foreach($item_types as $item_type)
                            <option value="{{$item_type->id}}" @if($order->item_type_id == $item_type->id) {{'selected'}} @endif>{{$item_type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label for="type">
                        <h4>Delivery Type <b>:</b></h4>
                    </label>
                    <div>
                        <select name="delivery_type_id" id="delivery_type_id" class="form-control">
                            <option value="" selected disabled>Select the Type for This Order</option>
                            @foreach($delivery_types as $delivery_type)
                                <option value="{{$delivery_type->id}}" @if($delivery_type->id == $order->delivery_type_id) {{'selected'}} @endif>{{$delivery_type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="collection_method">
                        <h4>Collection Method <b>:</b></h4>
                    </label>
                    <div>
                        <input type="radio" id="pickup" name="collection_method" value="pickup"
                        @if($order->collection_method == "pickup") checked @endif/>
                        <label for="pickup" class="ps-3 pe-5">Pick Up</label>

                        <input type="radio" id="dropoff" name="collection_method" value="dropoff"
                        @if($order->collection_method == "dropoff") checked @endif/>
                        <label for="dropoff" class="ps-3">Drop Off</label>
                    </div>
                </div>
                <div class="col">
                    <label for="payment_method">
                        <h4>Payment Method <b>:</b></h4>
                    </label>
                    <div class="col">
                        <input type="radio" id="cash_on_delivery" name="payment_method" value="cash_on_delivery"
                            @if($order->payment_method == 'cash_on_delivery' ) checked @endif/>
                        <label for="cash_on_delivery" class="ps-2 pe-4">Cash On Delivery</label>

                        <input type="radio" id="item_prepaid" name="payment_method" value="item_prepaid"
                            @if($order->payment_method == 'item_prepaid' ) checked @endif/>
                        <label for="item_prepaid" class="ps-2 pe-4">Item Prepaid</label>

                        <input type="radio" id="all_prepaid" name="payment_method" value="all_prepaid"
                            @if($order->payment_method == 'all_prepaid' ) checked @endif/>
                        <label for="all_prepaid" class="ps-2">All Prepaid</label>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center m-0 ms-3 col-6">
                <div>
                <input type="checkbox" id="pay_later" name="pay_later" value="true"  @if($order->pay_later == true) checked @endif/>
                </div>
                <label for="pay_later" class="ms-2">
                    <h4>Is Pay Later</h4>
                </label>
                    
                @if ($errors->has('pay_later'))
                <span class="text-danger"><strong>{{ $errors->first('pay_later') }}</strong></span>
                @endif
            </div>

            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="proof_of_payment">
                        <h4>Proof of Payment <b>:</b></h4>
                    </label>
                    <div>
                        <input type="file" id="proof_of_payment" name="proof_of_payment" class="form-control" />
                        @if ($errors->has('proof_of_payment'))
                        <span class="text-danger"><strong>{{ $errors->first('proof_of_payment') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            
            {{--<div class="row m-0 mb-3">
                <label for="collection_method" class="col-2">
                    <h4>Collection Method <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="collection_method" id="collection_method_id" class="form-control">
                        <option value="" selected disabled>Select the Collection Method for This Order</option>
                        <option value="pickup" @if($order->collection_method == "pickup") {{'selected'}} @endif>Pick Up</option>
                        <option value="dropoff" @if($order->collection_method == "dropoff") {{'selected'}} @endif>Drop Off</option>
                    </select>
                </div>
            </div>--}}
            
            {{--<div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Payment Method <b>:</b></h4>
                </div>
                <div class="col-10">
                    <select name="payment_method" id="payment_method" class="form-control">
                        <option value="" selected disabled>Select the Payment Method for This Order</option>
                        <option value="cash_on_delivery" @if($order->payment_method == "cash_on_delivery") {{'selected'}} @endif>Cash On Delivery</option>
                        <option value="item_prepaid" @if($order->payment_method == "item_prepaid") {{'selected'}} @endif>Item Prepaid</option>
                        <option value="all_prepaid" @if($order->payment_method == "all_prepaid") {{'selected'}} @endif>All Prepaid</option>
                    </select>
                </div>
            </div>--}}
            {{--<div class="row m-0 mb-3">
                <label for="note" class="col-2">
                    <h4>Note <b>:</b></h4>
                </label>
                <div class="col-10">
                    <textarea id="note" name="note" class="form-control" style="height: 100px">{{$order->note}}</textarea>
                </div>
            </div>--}}
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
        $('#city_id').select2({width: '100%'});
        $('#township_id').select2({width: '100%'});
        $('#rider_id').select2({width: '100%'});
        $('#shop_id').select2({width: '100%'});
        $('#status_id').select2({width: '100%'});
        $('#item_type_id').select2({width: '100%'});
        $('#delivery_type_id').select2({width: '100%'});
        $('#collection_method_id').select2({width: '100%'});
        $('#payment_method').select2({width: '100%'});
        $(".select2-selection").on("focus", function () {
            $(this).parent().parent().prev().select2("open");
        });

        function handleEnterKeyPress(event, checkbox) {
            if (event.keyCode === 13) {
                checkbox.checked = !checkbox.checked;
                event.preventDefault();
            }
        }

        var payLaterCheckBox = document.getElementById("pay_later");
        var deliFreeCheckBox = document.getElementById("is_deli_free");

        payLaterCheckBox.addEventListener("keydown", function(event) {
            handleEnterKeyPress(event, payLaterCheckBox);
        });

        deliFreeCheckBox.addEventListener("keydown", function(event) {
            handleEnterKeyPress(event, deliFreeCheckBox);
        });

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
        var isDeliFree = $('#is_deli_free').prop('checked');
        if (isDeliFree) {
            $('#total_amount_1').hide();
            var deliveryFees = $('#delivery_fees').val();
            var totalAmount = $('#total_amount').val();
            $('#actual_amount').val(parseFloat(totalAmount) + parseFloat(deliveryFees));
            $('#second_total_amount').val(totalAmount);
        } else {
            $('#second_amount').hide();
        }

        $('#is_deli_free').change(function() {
            var isDeliFree = $(this).prop('checked');
            var actualAmount = $('#actual_amount').val();
            var deliveryFees = $('#delivery_fees').val();
            var totalAmount = $('#total_amount').val();
            var secondTotalAmount = $('#second_total_amount').val();
            if (isDeliFree) {
                $('#total_amount_1').hide();
                $('#second_amount').show();
                if(totalAmount != '' && deliveryFees != ''){
                    $('#actual_amount').val(totalAmount);
                    $('#second_total_amount,#total_amount').val(parseFloat(totalAmount) - parseFloat(deliveryFees));
                }
            } else {
                $('#total_amount_1').show();
                $('#second_amount').hide();
                if(actualAmount != ''){
                    $('#total_amount').val(actualAmount);
                }
            }
        })

        $('#actual_amount').on('input',function() {
            var actualAmount = parseFloat($('#actual_amount').val());
            var deliveryFees = parseFloat($('#delivery_fees').val());
            var totalAmount  = actualAmount - deliveryFees;
            console.log(totalAmount);
            $('#total_amount').val(totalAmount);
            $('#second_total_amount').val(totalAmount);
        })
    });
</script>
@endsection