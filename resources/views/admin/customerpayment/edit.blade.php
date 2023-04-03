@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Customer Payment Editing')
@section('content')
        <div class="card card-container action-form-card">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Update Customer Payment</strong>
                </h2>
                <form action="{{route('customer-payments.update', $customer_payment->id)}}" method="POST" class="action-form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row m-0 mb-3">
                        <label for="order_id" class="col-2">
                            <h4>Order<b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="order_id" id="order_id" class="form-control">
                                <option value="" selected disabled>Select the order for This Customer Payment</option>
                                @foreach ($orders as $order)
                                    <option value="{{$order->id}}" @if($order->id == $customer_payment->order_id) selected @endif>{{$order->order_code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="amount" class="col-2">
                            <h4>Amount<b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="amount" name="amount" class="form-control" value="{{$customer_payment->amount}}"/>
                            @if ($errors->has('amount'))
                            <span class="text-danger"><strong>{{ $errors->first('amount') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row m-0 mb-3">
                        <label for="type" class="col-2">
                            <h4>Type<b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="type" id="type" class="form-control">
                                <option value="" selected disabled>Select the Type for This Customer Pay</option>
                                <option value="fully_paid" @if($customer_payment->type == "fully_paid") {{'selected'}} @endif>Fully Paid</option>
                                <option value="delivery_fees_only" @if($customer_payment->type == "delivery_fees_only") {{'selected'}} @endif>Delivery Fees Only</option>
                                <option value="remaining_amount" @if($customer_payment->type == "remaining_amount") {{'selected'}} @endif>Remaining Amount</option>
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="paid_at" class="col-2">
                            <h4>Paid at<b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="date" id="paid_at" name="paid_at" value="{{$paid_at}}" class="form-control"/>
                            @if ($errors->has('paid_at'))
                            <span class="text-danger"><strong>{{ $errors->first('paid_at') }}</strong></span>
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

                    <div class="row m-0 mb-3">
                        <label for="description" class="col-2">
                            <h4>Description <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <textarea name="description" id="description" class="form-control" style="height: 100px" placeholder="Write description here">{{$customer_payment->description}}</textarea>
                        </div>
                    </div>
                    
                    <div class="footer-button float-end">
                        <a href="{{route('customer-payments.index')}}" class="btn btn-light">Cancel</a>
                        <input type="submit" class="btn btn-success ">
                    </div>
                </form>
            </div>
        </div>
        
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#order_id').select2();
            $('#type').select2();
        });

    </script>
@endsection    
