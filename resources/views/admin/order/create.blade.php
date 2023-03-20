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
                            <input type="text" id="order_code" name="order_code" class="form-control"/>
                            @if ($errors->has('order_code'))
                                <span class="text-danger"><strong>{{ $errors->first('order_code') }}</strong></span>
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
                        <label for="township_id" class="col-2">
                            <h4>Township Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="township_id" id="township_id" class="form-control">
                                <option value="" selected disabled>Select the Township for This Order</option>
                                @foreach ( $townships as $township)
                                    <option value="{{$township->id}}">{{$township->name}}</option>
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
                                    <option value="{{$rider->id}}">{{$rider->name}}</option>
                                @endforeach
                            </select>
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
                        <label for="status" class="col-2">
                            <h4>Status <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="status" name="status" class="form-control"/>
                            @if ($errors->has('status'))
                            <span class="text-danger"><strong>{{ $errors->first('status') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="item_type" class="col-2">
                            <h4>Item Type <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="item_type" name="item_type" class="form-control"/>
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
                            <input type="text" id="schedule_date" name="schedule_date" class="form-control"/>
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
                            <input type="text" id="type" name="type" class="form-control"/>
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
                            <input type="text" id="collection_method" name="collection_method" class="form-control"/>
                            @if ($errors->has('collection_method'))
                            <span class="text-danger"><strong>{{ $errors->first('collection_method') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="proof_of_payment" class="col-2">
                            <h4>Proof of Payment <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="file" id="proof_of_payment" name="proof_of_payment" class="form-control"/>
                            @if ($errors->has('proof_of_payment'))
                            <span class="text-danger"><strong>{{ $errors->first('proof_of_payment') }}</strong></span>
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