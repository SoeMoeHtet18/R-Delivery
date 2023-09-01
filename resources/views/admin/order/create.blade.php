@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Order Create')
@section('content')
<style>
.modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        border-radius: 10px !important;
        /* Could be more or less, depending on screen size */
    }

    #popupCard.modal {
        /* display: flex !important; */
        align-items: center;
        justify-content: center;
    }

    #confirm-no {
        background-color: white;
        color: black;
        border-color: green;
        border-radius: 2px !important;
    }

    #confirm-yes {
        border-radius: 2px !important;
    }

    #second_amount{
        display: flex;
        justify-content: space-between;
    }

</style>
<div class="card card-container action-form-card">
    
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Order</strong>
        </h2>
        <form action="{{route('orders.store')}}" method="POST" class="action-form">
            @csrf
            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="shop_id">
                        <h4>Shop Name <b>:</b></h4>
                    </label>
                    <div>
                        <select name="shop_id" id="shop_id" class="form-control">
                            <option value="" selected disabled>Select the Shop for This Order</option>
                            @foreach ( $shops as $shop)
                            <option value="{{$shop->id}}" @isset($shop_id) @if($shop->id == $shop_id) selected
                                @endif
                                @else
                                @if($shop->id == old('shop_id')) selected
                                @endif
                                @endisset
                                >{{$shop->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('shop_id'))
                        <span class="text-danger"><strong>{{ $errors->first('shop_id') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <label for="order_code">
                        <h4>Order Code <b>:</b></h4>
                    </label>
                    <div >
                        <input type="text" id="order_code" name="order_code" class="form-control" readonly />
                    </div>
                </div>
            </div>

            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="customer_phone_number">
                        <h4>Customer PhoneNumber <b>:</b></h4>
                    </label>
                    <div>
                        <input type="text" id="customer_phone_number" name="customer_phone_number" value="{{old('customer_phone_number')}}" class="form-control" />
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
                        <input type="text" id="customer_name" name="customer_name" value="{{old('customer_name')}}" class="form-control" />
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
                            <option value="{{$city->id}}"
                                @if($city->id == old('city_id')) selected @endif
                            >{{$city->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('city_id'))
                        <span class="text-danger"><strong>{{ $errors->first('city_id') }}</strong></span>
                        @endif
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
                            <option value="{{$township->id}}"
                                @if($township->id == old('township_id')) selected @endif
                            >{{$township->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('township_id'))
                        <span class="text-danger"><strong>{{ $errors->first('township_id') }}</strong></span>
                        @endif
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
                            <option value="{{$rider->id}}"
                            @if($rider->id == old('rider_id')) {{'selected'}} @endif
                            >{{$rider->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label for="delivery_fees">
                        <h4>Delivery Fees <b>:</b></h4>
                    </label>
                    <div>
                        <input type="text" id="delivery_fees" name="delivery_fees" value="{{old('delivery_fees')}}" class="form-control" />
                        @if ($errors->has('delivery_fees'))
                        <span class="text-danger"><strong>{{ $errors->first('delivery_fees') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="markup_delivery_fees" class="text-nowrap">
                        <h4>Markup Delivery Fees <b>:</b></h4>
                    </label>
                    <div>
                        <input type="text" id="markup_delivery_fees" name="markup_delivery_fees" value="{{old('markup_delivery_fees')}}" class="form-control" />
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
                        <input type="text" id="extra_charges" name="extra_charges" value="{{old('extra_charges')}}" class="form-control" />
                        @if ($errors->has('extra_charges'))
                        <span class="text-danger"><strong>{{ $errors->first('extra_charges') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="d-flex align-items-center m-0 ms-3 col-6">
                <div>
                <input type="checkbox" id="is_deli_free" name="is_deli_free" value="true" @if(old('is_deli_free', false)) checked @endif/>
                </div>
                <label for="is_deli_free" class="ms-2">
                    <h4>Is Delivery Free</h4>
                </label>
                    
                @if ($errors->has('is_deli_free'))
                <span class="text-danger"><strong>{{ $errors->first('is_deli_free') }}</strong></span>
                @endif
            </div>
            
            <!-- <div class="row m-0 mb-3">
                <label for="quantity" class="col-2">
                    <h4>Quantity <b>:</b></h4>
                </label>
                <div class="ps-4 col-10">
                    <input type="text" id="quantity" name="quantity" value="{{old('quantity')}}" class="form-control" />
                    @if ($errors->has('quantity'))
                    <span class="text-danger"><strong>{{ $errors->first('quantity') }}</strong></span>
                    @endif
                </div>
            </div> -->
            <div class="row m-0 mb-3">
                <div class="col" id="total_amount_1">
                    <label for="total_amount">
                        <h4>Total Amount <b>:</b></h4>
                    </label>
                    <div>
                        <input type="text" id="total_amount" name="total_amount" value="{{old('total_amount')}}" class="form-control" />
                        @if ($errors->has('total_amount'))
                        <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="col" id="second_amount">
                    <div class="col-5" id="actual_amount_1">
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
                    <div  class="col-5" id="total_amount_2">
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
                <div class="col">
                    <label for="schedule_date">
                        <h4>Schedule Date <b>:</b></h4>
                    </label>
                    <div>
                        <?php
                        // Get tomorrow's date
                        $tomorrow = date('Y-m-d', strtotime('+1 day'));
                        // Set the default value to tomorrow's date
                        $defaultDate = old('schedule_date') ?? $tomorrow;
                        ?>
                        <input type="date" id="schedule_date" name="schedule_date" value="<?php echo $defaultDate; ?>" class="form-control" />
                        @if ($errors->has('schedule_date'))
                            <span class="text-danger"><strong>{{ $errors->first('schedule_date') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="row m-0 mb-3">
                <div class="col">
                    <label for="full_address">
                        <h4>Address <b>:</b></h4>
                    </label>
                    <div>
                        <textarea id="full_address" name="full_address" class="form-control" style="height: 100px">{{old('full_address')}}</textarea>
                    </div>
                    @if ($errors->has('full_address'))
                    <span class="text-danger"><strong>{{ $errors->first('full_address') }}</strong></span>
                    @endif
                </div>
                <div class="col">
                    <label for="remark">
                        <h4>Remark <b>:</b></h4>
                    </label>
                    <div>
                        <textarea id="remark" name="remark" class="form-control" style="height: 100px">{{old('remark')}}</textarea>
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
                            <option value="{{$item_type->id}}" @if($item_type->id == old('item_type_id')) selected @endif>{{$item_type->name}}</option>
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
                            <option value="" selected disabled>Select Delivery Type for This Order</option>
                            @foreach($delivery_types as $delivery_type)
                            <option value="{{$delivery_type->id}}" @if($delivery_type->id == old('delivery_type_id')) selected @endif>{{$delivery_type->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('type'))
                        <span class="text-danger"><strong>{{ $errors->first('type') }}</strong></span>
                        @endif
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
                            @if(old('collection_method')=='pickup' ) checked @endif/>
                        <label for="pickup" class="ps-3 pe-5">Pick Up</label>

                        <input type="radio" id="dropoff" name="collection_method" value="dropoff"
                            @if(old('collection_method')=='dropoff' ) checked @endif/>
                        <label for="dropoff" class="ps-3">Drop Off</label>
                    </div>
                    @if ($errors->has('collection_method'))
                        <span class="text-danger"><strong>{{ $errors->first('collection_method') }}</strong></span>
                    @endif
                </div>
                <div class="col">
                    <label for="payment_method">
                        <h4>Payment Method <b>:</b></h4>
                    </label>
                    <div class="col">
                        <input type="radio" id="cash_on_delivery" name="payment_method" value="cash_on_delivery"
                            @if(old('payment_method')=='cash_on_delivery' ) checked @endif/>
                        <label for="cash_on_delivery" class="ps-2 pe-4">Cash On Delivery</label>

                        <input type="radio" id="item_prepaid" name="payment_method" value="item_prepaid"
                            @if(old('payment_method')=='item_prepaid' ) checked @endif/>
                        <label for="item_prepaid" class="ps-2 pe-4">Item Prepaid</label>

                        <input type="radio" id="all_prepaid" name="payment_method" value="all_prepaid"
                            @if(old('payment_method')=='all_prepaid' ) checked @endif/>
                        <label for="all_prepaid" class="ps-2">All Prepaid</label>
                    </div>
                    @if ($errors->has('payment_method'))
                        <span class="text-danger"><strong>{{ $errors->first('payment_method') }}</strong></span>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center m-0 ms-3 col-6">
                <div>
                <input type="checkbox" id="pay_later" name="pay_later" value="true"  @if(old('pay_later', false)) checked @endif/>
                </div>
                <label for="pay_later" class="ms-2">
                    <h4>Is Pay Later</h4>
                </label>
                    
                @if ($errors->has('pay_later'))
                <span class="text-danger"><strong>{{ $errors->first('pay_later') }}</strong></span>
                @endif
            </div>
            
            {{--<div class="row m-0 mb-3">
                <label for="collection_method" class="col-2">
                    <h4>Collection Method <b>:</b></h4>
                </label>
                <div class="ps-4 col-10">
                    <select name="collection_method" id="collection_method_id" class="form-control">
                        <option value="" selected disabled>Select the Collection Method for This Order</option>
                        <option value="pickup" @if(old('collection_method')=='pickup' ) selected @endif>Pick Up</option>
                        <option value="dropoff" @if(old('collection_method')=='dropoff' ) selected @endif>Drop Off</option>
                    </select>
                    @if ($errors->has('collection_method'))
                    <span class="text-danger"><strong>{{ $errors->first('collection_method') }}</strong></span>
                    @endif
                </div>
            </div>--}}
            {{--<div class="row m-0 mb-3">
                <label for="payment_method" class="col-2">
                    <h4>Payment Method <b>:</b></h4>
                </label>
                <div class="ps-4 col-10">
                    <select name="payment_method" id="payment_method" class="form-control">
                        <option value="" selected disabled>Select the Payment Method for This Order</option>
                        <option value="cash_on_delivery" @if(old('payment_method')=='cash_on_delivery' ) selected @endif>Cash On Delivery</option>
                        <option value="item_prepaid" @if(old('payment_method')=='item_prepaid' ) selected @endif>Item Prepaid</option>
                        <option value="all_prepaid" @if(old('payment_method')=='all_prepaid' ) selected @endif>All Prepaid</option>
                    </select>
                    @if ($errors->has('payment_method'))
                    <span class="text-danger"><strong>{{ $errors->first('payment_method') }}</strong></span>
                    @endif
                </div>
            </div>--}}
            <!-- <div class="row m-0 mb-3">
                <label for="note" class="col-2">
                    <h4>Note <b>:</b></h4>
                </label>
                <div class="ps-4 col-10">
                    <textarea id="note" name="note" class="form-control" style="height: 100px">{{old('note')}}</textarea>
                </div>
            </div> -->
            <div class="footer-button float-end">
                <a href="{{route('orders.index')}}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

<div id="popupCard" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title text-center">Do you want to add more orders?</h5>
                
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <!-- "No" Button -->
                <button type="button" id="confirm-no" class="btn btn-secondary" data-more-orders="false" data-dismiss="modal">No</button>
                <!-- "Yes" Button -->
                <button type="button" id="confirm-yes" class="btn btn-success" data-more-orders="true">Yes</button>
            </div>
        </div>
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
        $('#status_id').select2();
        $('#item_type_id').select2({width: '100%'});
        $('#delivery_type_id').select2({width: '100%'});
        $('#collection_method_id').select2();
        $('#payment_method').select2();

        $('#type_id').change(function() {
            console.log($('#type_id').val());
            if($('#type_id').val() == 'doortodoor') {
            var today = new Date();
            var futureDate = new Date(today.getTime() + (5 * 24 * 60 * 60 * 1000));

            var formattedDate = futureDate.toISOString().split('T')[0];

            $('#schedule_date').val(formattedDate);

            }
        });

        function updateDeliveryFees(township_id) {
            $.ajax({
                url: '/api/get-delivery-fees-by-township',
                type: 'POST',
                dataType: 'json',
                data: {
                    township_id: township_id
                },
                success: function(response) {
                    $("#delivery_fees").val(response.data);
                }
            });
        }

        function getRidersByTownship(township_id) {
            $.ajax({
                url: '/api/riders-get-by-township',
                type: 'POST',
                dataType: 'json',
                data: {
                    township_id: township_id
                },
                success: function(response) {
                    var rider_id = $('#rider_id').val();
                    var riders = '<option value="" selected disabled>Select the Rider for This Order</option>';
                    if (response.data) {
                        for (let i = 0; i < response.data.length; i++) {
                            if(response.data[0]) {
                                riders += '<option value="' + response.data[i].id + '" selected>' + response.data[i].name + '</option>';
                            } else if (rider_id == response.data[i].id) {
                                riders += '<option value="' + response.data[i].id + '" selected>' + response.data[i].name + '</option>';
                            } else {
                                riders += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>';
                            }
                        }
                    }
                    $('#rider_id').html(riders);
                },
            })
        }

        // Call the function on page load with the initial value of township_id
        var initialTownshipId = $('#township_id').val();
        updateDeliveryFees(initialTownshipId);
        getRidersByTownship(initialTownshipId);

        // Attach event handlers
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
                    var township_id = $('#township_id').val();
                    var townships = '<option value="" disabled>Select the Township for This Order</option>';
                    if (response.data) {
                        for (var i = 0; i < response.data.length; i++) {
                            if (township_id == response.data[i].id) {
                                townships += '<option value="' + response.data[i].id + '" selected>' + response.data[i].name + '</option>';
                            } else {
                                townships += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>';
                            }
                        }
                    }
                    console.log(townships);
                    $('#township_id').html(townships);

                    // Update the delivery fees after selecting the township
                    var selectedTownshipId = $('#township_id').val();
                    updateDeliveryFees(selectedTownshipId);
                    getRidersByTownship(selectedTownshipId);
                },
            });
        });

        $("#township_id").change(function() {
            var township_id = $('#township_id').val();
            // Update the delivery fees after selecting the township
            updateDeliveryFees(township_id);
            getRidersByTownship(township_id);
        });



        $('#customer_phone_number').on('keyup', debounce(function() {
            var phone = $(this).val();

            $.ajax({
                url: '/api/get-data-by-customer-phone',
                method: 'POST',
                data: {
                    phone_number: phone
                },
                success: function(response) {
                    console.log(response)
                    if (response.status == 'success') {
                        var data = response.data;
                        $('#customer_name').val(data.customer_name);
                        $('#city_id').val(data.city_id).trigger('change');
                        $('#township_id').val(data.township_id).trigger('change');
                        console.log($('#township_id').val());
                    }
                }
            });
        }, 300));

        function debounce(func, delay) {
            let timer;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    func.apply(this, args);
                }, delay);
            }
        }

        function setOrderCode(code) {
            $('#order_code').val(code);
            console.log($('#order_code').val());
        }

        function getOrderCode() {
            var shop_id = $('#shop_id').val();
            if (shop_id) {
                $.ajax({
                    url: '/api/get-order-code',
                    method: 'POST',
                    data: {
                        shop_id: shop_id
                    },
                    success: function(response) {
                        console.log(response)
                        if (response.status == 'success') {
                            var data = response.data;
                            setOrderCode(data);
                        }
                    }
                });
            }
        }

        getOrderCode();

        $('#shop_id').change(function() {
            getOrderCode();
        });

        $('#confirm-yes').click(function() {
            $('#popupCard').fadeOut(300);
            submitFormWithParameter(true);
        });

        $('#confirm-no').click(function() {
            $('#popupCard').fadeOut(300);
            submitFormWithParameter(false);
        });

        $('form.action-form').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission
            // $('#popupCard').modal('show');
            var popupCard = document.getElementById('popupCard');
            popupCard.style.display = 'block';
            popupCard.style.background = 'none';
            popupCard.style.height = '100vh';
            popupCard.style.top = '500px';
        });

        function submitFormWithParameter(moreOrders) {
            console.log(moreOrders);
            var form = $('form.action-form');
            var actionUrl = form.attr('action');
            var newUrl = actionUrl + '?more_orders=' + moreOrders;

            // Update the form action URL
            form.attr('action', newUrl);

            // Submit the form
            form.unbind('submit').submit();
        }

        $('#second_amount').hide();
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