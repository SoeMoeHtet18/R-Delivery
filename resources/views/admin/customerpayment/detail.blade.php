@extends('admin.layouts.master')

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
        <div class="card card-container">
            <div class="card-body">
                    <h2 class="ps-1">
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
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Order Code <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $customer_payment->order->order_code }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Amount<b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $customer_payment->amount }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Type<b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $customer_payment->type }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Paid At<b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $customer_payment->paid_at }}
                        </div>
                    </div>
            </div>
        </div>
@endsection