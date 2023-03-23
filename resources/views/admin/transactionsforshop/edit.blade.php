@extends('admin.layouts.master')

@section('content')
        <div class="card card-container action-form-card">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Update Transaction</strong>
                </h2>
                <form action="{{route('transactions-for-shop.update', $transaction_for_shop->id)}}" method="POST" class="action-form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row m-0 mb-3">
                        <label for="shop_id" class="col-2">
                            <h4>Shop Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="shop_id" id="shop_name" class="form-control">
                                <option value="" selected disabled>Select the Shop for This Payment</option>
                                @foreach ( $shops as $shop)
                                    <option value="{{$shop->id}}" @if($transaction_for_shop->shop_id == $shop->id) {{'selected'}} @endif>{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="amount" class="col-2">
                            <h4>Amount <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="amount" name="amount" value="{{$transaction_for_shop->amount}}" class="form-control"/>
                            @if ($errors->has('amount'))
                                <span class="text-danger"><strong>{{ $errors->first('amount') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="image" class="col-2">
                            <h4>Image <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="file" id="image" name="image" class="form-control"/>
                            @if ($errors->has('image'))
                            <span class="text-danger"><strong>{{ $errors->first('image') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="type" class="col-2">
                            <h4>Type <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="type" id="type" class="form-control">
                                <option value="" selected disabled>Select Type for This Payment</option>
                                <option value="delivery_payment" @if($transaction_for_shop->type == "fully_payment") {{'selected'}} @endif>Fully Payment</option>
                                <option value="remaining_payment" @if($transaction_for_shop->type == "loan_payment") {{'selected'}} @endif>Loan Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="paid_by" class="col-2">
                            <h4>Paid By <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="paid_by" id="paid_by" class="form-control">
                                <option value="" selected disabled>Select the User for This Payment</option>
                                @foreach ( $users as $user)
                                    <option value="{{$user->id}}" @if($transaction_for_shop->paid_by == $user->id) {{'selected'}} @endif>{{$user->name}}</option>
                                @endforeach
                            </select>
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
            $('#shop_name').select2();
            $('#type').select2();
            $('#paid_by').select2();
        });
    </script>
@endsection   