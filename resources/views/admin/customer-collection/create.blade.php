@extends('admin.layouts.master')
@section('title','Collection')
@section('sub-title','Customer Collection Create')
@section('style')
<style>
    .tabs {
        width: 150px;
        height: 40px;
        border: 1px solid #64C5B1;
        cursor: pointer;
    }

    .text-green {
        color: #64C5B1;
    }

    .bg-cyan {
        background-color: #64C5B1;
    }
</style>
@endsection
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Customer Collection</strong>
        </h2>
        <form action="{{route('customer-collections.store')}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            @if(isset($order))
            <input type="hidden" name="order_id" value="{{$order->id}}">
            <input type="hidden" name="shop_id" value="{{$order->shop_id}}">
            <input type="hidden" name="city_id" value="{{$order->city_id}}">
            <input type="hidden" name="township_id" value="{{$order->township_id}}">
            <input type="hidden" name="address" value="{{$order->full_address}}">
            <input type="hidden" name="rider_id" value="{{$order->rider_id}}">
            <input type="hidden" name="customer_name" value="{{$order->customer_name}}">
            <input type="hidden" name="phone_number" value="{{$order->customer_phone_number}}">
            @else
            <div class="row m-0 mb-3">
                <label for="order_id" class="col-2">
                    <h4>Order <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="order_id" id="order_id" class="form-control">
                        <option value="" selected disabled>Select Order For This Customer Exchange</option>
                        @foreach($orders as $order)
                        <option value="{{$order->id}}" @if($order->id == old('order_id')) selected @endif>{{$order->order_code}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="shop" class="col-2">
                    <h4>Shop <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="shop_id" id="shop" class="form-control">
                        <option value="" selected disabled>Select Shop For This Customer Exchange</option>
                        @foreach($shops as $shop)
                        <option value="{{$shop->id}}" @if($shop->id == old('shop_id')) selected @endif>{{$shop->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('shop_id'))
                    <span class="text-danger"><strong>{{ $errors->first('shop_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="customer_collection_code" class="col-2">
                    <h4>Customer Exchange Code<b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="customer_collection_code" name="customer_collection_code" class="form-control" 
                        value="{{old('customer_collection_code')}}" readonly/>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="city_id" class="col-2">
                    <h4>City Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="city_id" id="city_id" class="form-control">
                        <option value="" selected disabled>Select City for This Customer Exchange</option>
                        @foreach ( $cities as $city)
                        <option value="{{$city->id}}" @if($city->id == old('city_id')) {{'selected'}} @endif>{{$city->name}}</option>
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
                        <option value="" selected disabled>Select Township for This Customer Exchange</option>
                        @foreach ( $townships as $township)
                        <option value="{{$township->id}}" @if($township->id == old('township_id')) {{'selected'}} @endif>{{$township->name}}</option>
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
                        <option value="" selected disabled>Select Rider for This Customer Exchange</option>
                        @foreach ( $riders as $rider)
                        <option value="{{$rider->id}}" @if($rider->id == old('rider_id')) {{'selected'}} @endif>{{$rider->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="customer_name" class="col-2">
                    <h4>Customer Name<b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="customer_name" name="customer_name" value="{{old('customer_name')}}" class="form-control" />
                    @if ($errors->has('customer_name'))
                    <span class="text-danger"><strong>{{ $errors->first('customer_name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="phone_number" class="col-2">
                    <h4>Customer Phone Number<b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="phone_number" name="phone_number" value="{{old('phone_number')}}" class="form-control" />
                    @if ($errors->has('phone_number'))
                    <span class="text-danger"><strong>{{ $errors->first('phone_number') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="address" class="col-2">
                    <h4>Address <b>:</b></h4>
                </label>
                <div class="col-10">
                    <textarea id="address" name="address" class="form-control" style="height: 100px">{{old('address')}}</textarea>
                </div>
                @if ($errors->has('address'))
                <span class="text-danger"><strong>{{ $errors->first('address') }}</strong></span>
                @endif
            </div>
            @endif
            <div class="row m-0 mb-3">
                <label for="schedule_date" class="col-2">
                    <h4>Schedule Date <b>:</b></h4>
                </label>
                <div class="col-10">
                    <?php
                    // Get tomorrow's date
                    $today = date('Y-m-d');
                    // Set the default value to tomorrow's date
                    $defaultDate = old('schedule_date') ?? $today;
                    ?>
                    <input type="date" id="schedule_date" name="schedule_date" value="<?php echo $defaultDate; ?>" class="form-control" />
                    @if ($errors->has('schedule_date'))
                        <span class="text-danger"><strong>{{ $errors->first('schedule_date') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="items" class="col-2">
                    <h4>Item<b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="items" name="items" value="{{old('items')}}" class="form-control" />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="paid_amount" class="col-2">
                    <h4>Paid Amount<b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="paid_amount" name="paid_amount" value="{{old('paid_amount')}}" class="form-control required" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Paid Amount is required.</strong>
                    </span>
                    @if ($errors->has('paid_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('paid_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="note" class="col-2">
                    <h4>Note <b>:</b></h4>
                </label>
                <div class="col-10">
                    <textarea id="note" name="note" class="form-control" style="height: 100px">{{old('note')}}</textarea>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label>
                    <h4>Is Way Fees Payable</h4>
                </label>
                <div id="tabs-container" class="d-flex mt-2">
                    <div id="tab-one" class="tabs tab-one d-flex justify-content-center align-items-center text-green">Yes</div>
                    <div id="tab-two" class="tabs tab-two d-flex justify-content-center align-items-center text-green">No</div>
                </div>
            </div>
            <input type="hidden" name="is_way_fees_payable" id="is_way_fees_payable">
            <div class="footer-button float-end">
                <a href="{{url()->previous() }}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        $('#order_id').select2({width: '100%'});
        $("#shop").select2({width: '100%'});
        $("#rider_id").select2({width: '100%'});
        $("#city_id").select2({width: '100%'});
        $("#township_id").select2({width: '100%'});
        $(".select2-selection").on("focus", function () {
            $(this).parent().parent().prev().select2("open");
        });

        $("#order_id").on('change', function() {
            $.ajax({
                url: '/api/get-data-by-order-for-customer-collection',
                method: 'POST',
                data: {
                    order_id: $('#order_id').val()
                },
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {
                        var data = response.data;
                        $('#shop').val(data.shop_id);
                        $('#shop').trigger('change');
                        $('#city_id').val(data.city_id);
                        $('#city_id').trigger('change');
                        $('#rider_id').val(data.rider_id);
                        $('#rider_id').trigger('change');
                        $('#township_id').val(data.township_id);
                        $('#township_id').trigger('change');
                        
                        $('#customer_name').val(data.customer_name);
                        $('#phone_number').val(data.customer_phone_number);
                        $('#address').val(data.full_address);
                    }
                }
            });
        })

        $("#shop").on('change', function() {
            $.ajax({
                url: '/api/get-customer-collection-code',
                method: 'POST',
                data: {
                    shop_id: $('#shop').val()
                },
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {
                        var data = response.data;
                        console.log(data);
                        $('#customer_collection_code').val(data);
                    }
                }
            });
        })

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
                    getRidersByTownship(selectedTownshipId);
                },
            });
        });

        $("#township_id").change(function() {
            var township_id = $('#township_id').val();
            getRidersByTownship(township_id);
        });

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
                             if (rider_id == response.data[i].id) {
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

        // Attach the click event to the parent container
        $('#tabs-container').on('click', '.tabs', function() {
            var $this = $(this);
            var $tabs = $('.tabs'); // Cache all tabs

            $tabs.not($this).removeClass('bg-cyan text-white clicked'); // Remove classes from other tabs
            $this.addClass('bg-cyan text-white clicked'); // Add classes to the clicked tab

            if ($this.attr('id') == 'tab-one') {
                $('#is_way_fees_payable').val(1);
            } else {
                $('#is_way_fees_payable').val(0);
            }
        });
    });

    errFieldHandling();

    function errFieldHandling() {
        // Get all input and select elements on the page, including input elements with type="date"
        var inputAndSelectElements = document.querySelectorAll('input, select, input[type="date"]');

        inputAndSelectElements.forEach(function(element) {
            element.addEventListener('keydown', function (event) {
                if (event.key === 'Tab') {

                    // handling the Tab key press on this element
                    var currentIndex = Array.from(inputAndSelectElements).indexOf(document.activeElement);
                    var nextIndex = currentIndex + 1;

                    // Make sure the next index is within bounds
                    if (nextIndex < inputAndSelectElements.length) {
                        // Check if the input is required or not
                        if(inputAndSelectElements[currentIndex].classList.contains('required')) {
                                // Get the value of the checked input
                                $checkForvalue = inputAndSelectElements[currentIndex].value;
                                if($checkForvalue == '') {
                                    // if value is null, show err msg
                                    inputAndSelectElements[currentIndex].nextElementSibling.classList.remove('d-none');
                                } else {
                                    // if value is not null, hide err msg
                                    inputAndSelectElements[currentIndex].nextElementSibling.classList.add('d-none');
                                }
                        }
                    }
                }
            });
        });
    }
       
</script>
@endsection