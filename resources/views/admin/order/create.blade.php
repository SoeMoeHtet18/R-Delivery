@extends('admin.layouts.master')

@section('content')
        <div class="card card-container">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Add New Order</strong>
                </h2>
                <form action="{{route('orders.store')}}" method="POST">
                    @csrf
                    <div class="row m-0 mb-3">
                        <label for="order_code" class="col-2">
                            <h4>Order Code <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="order_code" name="order_code" class="form-control" 
                                value="{{$order_code}}" readonly/>
                            @if ($errors->has('order_code'))
                                <span class="text-danger"><strong>{{ $errors->first('order_code') }}</strong></span>
                            @endif
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
                                    <option value="{{$shop->id}}">{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="customer_phone_number" class="col-2">
                            <h4>Customer PhoneNumber <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="customer_phone_number" name="customer_phone_number" class="form-control"/>
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
                            <input type="text" id="customer_name" name="customer_name" class="form-control"/>
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
                                    <option value="{{$city->id}}">{{$city->name}}</option>
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
                               
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="quantity" class="col-2">
                            <h4>Quantity <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="quantity" name="quantity" class="form-control"/>
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
                            <input type="text" id="total_amount" name="total_amount" class="form-control"/>
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
                            <input type="text" id="delivery_fees" name="delivery_fees" class="form-control"/>
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
                            <input type="text" id="markup_delivery_fees" name="markup_delivery_fees" class="form-control"/>
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
                            <input type="text" id="remark" name="remark" class="form-control"/>
                            @if ($errors->has('remark'))
                            <span class="text-danger"><strong>{{ $errors->first('remark') }}</strong></span>
                            @endif
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
                                    <option value="{{$item_type->name}}">{{$item_type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="full_address" class="col-2">
                            <h4>Address <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="full_address" name="full_address" class="form-control"/>
                            @if ($errors->has('full_address'))
                            <span class="text-danger"><strong>{{ $errors->first('full_address') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="schedule_date" class="col-2">
                            <h4>Schedule Date <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="date" id="schedule_date" name="schedule_date" class="form-control"/>
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
                                    <option value="standard">Standard</option>
                                    <option value="express">Express</option>
                                    <option value="doortodoor">Door To Door</option>
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="collection_method" class="col-2">
                            <h4>Collection Method <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="collection_method" id="collection_method_id" class="form-control">
                                <option value="" selected disabled>Select the Collection Method for This Order</option>
                                <option value="pickup">Pick Up</option>
                                <option value="dropoff">Drop Off</option>
                            </select>
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
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
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
                    data: { city_id: city_id },
                    success: function(response) {
                        var townships = '<option value="" selected disabled>Select the Township for This Order</option>';
                        if(response.data){
                            for(let i = 0; i < response.data.length; i++){
                                townships += '<option value="'+ response.data[i].id + '">' + response.data[i].name+'</option>';
                            }                            
                        }
                        $('#township_id').html(townships);
                    },
                })
            })
        });

    </script>
@endsection    
