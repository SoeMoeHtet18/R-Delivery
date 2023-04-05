@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Order Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Order</strong>
        </h2>
        <form action="{{route('orders.store')}}" method="POST" class="action-form">
            @csrf
            <div class="row m-0 mb-3">
                <label for="order_code" class="col-2">
                    <h4>Order Code <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="order_code" name="order_code" class="form-control" value="{{$order_code}}" readonly />
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
                        <option value="{{$shop->id}}" @if($shop->id == old('shop_id')) selected @endif>{{$shop->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('shop_id'))
                    <span class="text-danger"><strong>{{ $errors->first('shop_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="customer_phone_number" class="col-2">
                    <h4>Customer PhoneNumber <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="customer_phone_number" name="customer_phone_number" value="{{old('customer_phone_number')}}" class="form-control" />
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
                    <input type="text" id="customer_name" name="customer_name" value="{{old('customer_name')}}" class="form-control" />
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
                        <option value="{{$city->id}}" @if($city->id == old('city_id')) selected @endif>{{$city->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('city_id'))
                    <span class="text-danger"><strong>{{ $errors->first('city_id') }}</strong></span>
                    @endif
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
                        <option value="{{$township->id}}" @if($township->id == old('township_id')) selected @endif>{{$township->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('township_id'))
                    <span class="text-danger"><strong>{{ $errors->first('township_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="quantity" class="col-2">
                    <h4>Quantity <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="quantity" name="quantity" value="{{old('quantity')}}" class="form-control" />
                    @if ($errors->has('quantity'))
                    <span class="text-danger"><strong>{{ $errors->first('quantity') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount" value="{{old('total_amount')}}" class="form-control" />
                    @if ($errors->has('total_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="delivery_fees" class="col-2">
                    <h4>Delivery Fees <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="delivery_fees" name="delivery_fees" value="{{old('delivery_fees')}}" class="form-control" />
                    @if ($errors->has('delivery_fees'))
                    <span class="text-danger"><strong>{{ $errors->first('delivery_fees') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="markup_delivery_fees" class="col-2">
                    <h4>Markup Delivery Fees <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="markup_delivery_fees" name="markup_delivery_fees" value="{{old('markup_delivery_fees')}}" class="form-control" />
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
                    <textarea id="remark" name="remark" class="form-control" style="height: 100px">{{old('remark')}}</textarea>
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
                        <option value="{{$item_type->name}}" @if($item_type->name == old('item_type')) selected @endif>{{$item_type->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('item_type'))
                    <span class="text-danger"><strong>{{ $errors->first('item_type') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="full_address" class="col-2">
                    <h4>Address <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="full_address" name="full_address" value="{{old('full_address')}}" class="form-control" />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="schedule_date" class="col-2">
                    <h4>Schedule Date <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="date" id="schedule_date" name="schedule_date" value="{{old('schedule_date')}}" class="form-control" />
                    @if ($errors->has('schedule_date'))
                    <span class="text-danger"><strong>{{ $errors->first('schedule_date') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="type" class="col-2">
                    <h4>Type <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="type" id="type_id" class="form-control">
                        <option value="" selected disabled>Select the Type for This Order</option>
                        <option value="standard" @if(old('type')=='standard' ) selected @endif>Standard</option>
                        <option value="express" @if(old('type')=='express' ) selected @endif>Express</option>
                        <option value="doortodoor" @if(old('type')=='doortodoor' ) selected @endif>Door To Door</option>
                    </select>
                    @if ($errors->has('type'))
                    <span class="text-danger"><strong>{{ $errors->first('type') }}</strong></span>
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
                        <option value="pickup" @if(old('collection_method')=='pickup' ) selected @endif>Pick Up</option>
                        <option value="dropoff" @if(old('collection_method')=='dropoff' ) selected @endif>Drop Off</option>
                    </select>
                    @if ($errors->has('collection_method'))
                    <span class="text-danger"><strong>{{ $errors->first('collection_method') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{route('orders.index')}}" class="btn btn-light">Cancel</a>
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
        $('#shop_id').select2();
        $('#status_id').select2();
        $('#item_type_id').select2();
        $('#type_id').select2();
        $('#collection_method_id').select2();

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
                        for (var i = 0; i < (response.data.length); i++) {
                            if (township_id == response.data[i].id) {
                                townships += '<option value="' + response.data[i].id + '" selected>' + response.data[i].name + '</option>';
                            } else {
                                townships += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>';
                            }
                        }
                    }
                    console.log(townships);
                    $('#township_id').html(townships);
                },
            })
        })
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
    });
</script>
@endsection