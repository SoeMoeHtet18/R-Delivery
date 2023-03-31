@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Customer Payment Detail')
@section('content')
<style>
    .card-toolbar{
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
    }
    .create-button { 
        width: 70px;
        height: 30px;
        margin-bottom: 10px;
    }
</style>
        <div class="card card-container detail-card">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Customer Payment Detail</strong>
                </h2>
                <div class="card-toolbar">
                    <div class="create-button">
                        <a href="{{route('customer-payments.edit' , $customer_payment->id)}}" class="btn btn-light">Edit</a>
                    </div>
                    <form action="{{route('customer-payments.destroy', $customer_payment->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this customer payment?`);">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger float-end">
                    </form>
                </div>
                <div class="detail-infos">
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Order Code <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            {{ $customer_payment->order->order_code }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Amount<b>:</b></h4>
                        </div>
                        <div class="col-10">
                            {{ $customer_payment->amount }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Type<b>:</b></h4>
                        </div>
                        <div class="col-10">
                            {{ $customer_payment->type }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Paid At<b>:</b></h4>
                        </div>
                        <div class="col-10">
                            {{ $customer_payment->paid_at }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Proof of payment <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            <img src="{{asset('/storage/customer payment/' . $customer_payment->proof_of_payment)}}" alt="" style="width: 200px;">
                        </div>
                    </div>
                </div>
                    
            </div>
        </div>
@endsection