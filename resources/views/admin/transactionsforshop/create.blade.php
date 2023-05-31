@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Transactions For Shop Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Transaction</strong>
        </h2>
        <form action="{{route('transactions-for-shop.store')}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            <div class="row m-0 mb-3">
                <label for="shop" class="col-2">
                    <h4>Shop Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="shop_id" id="shop_name" class="form-control">
                        <option value="" selected disabled>Select the Shop of this Payment</option>
                        @foreach($shops as $shop)
                        <option value="{{$shop->id}}" @isset($shop_id) @if($shop->id == $shop_id) selected
                            @endif
                            @else
                            @if($shop->id == old('shop_id')) selected
                            @endif
                            @endisset>{{$shop->name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('shop_id'))
                    <span class="text-danger"><strong>{{ $errors->first('shop_id') }}</strong></span>
                    @endif
                </div>
            </div>
            @isset($order_ids)
            @foreach($order_ids as $orderId)
                <input type="hidden" name="order_ids[]" value="{{ $orderId }}">
            @endforeach
            @endisset
            <div class="row m-0 mb-3">
                <label for="amount" class="col-2">
                    <h4>Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="amount" name="amount" value="@isset($actual_amount){{ $actual_amount }}@else{{ old('amount') }}@endisset" class="form-control" />
                    @if($errors->has('amount'))
                    <span class="text-danger"><strong>{{ $errors->first('amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="image" class="col-2">
                    <h4>Image <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="file" id="image" name="image" class="form-control" />
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
                        <option value="" selected disabled>Select the Type for This Payment</option>
                        <option value="fully_payment" @if(old('type')=='fully_payment' ) selected @endif>Fully Payment</option>
                        <option value="loan_payment" @if(old('type')=='loan_payment' ) selected @endif>Loan Payment</option>
                    </select>
                    @if ($errors->has('type'))
                    <span class="text-danger"><strong>{{ $errors->first('type') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="paid_by" class="col-2">
                    <h4>Paid By <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="paid_by" id="paid_by" class="form-control">
                        <option value="" selected disabled>Select the User of this Payment</option>
                        @foreach($users as $user)
                        <option value="{{$user->id}}" @if($user->id == old('paid_by')) selected @endif>{{$user->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('paid_by'))
                    <span class="text-danger"><strong>{{ $errors->first('paid_by') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="description" class="col-2">
                    <h4>Description <b>:</b></h4>
                </label>
                <div class="col-10">
                    <textarea name="description" id="description" class="form-control" style="height: 100px" placeholder="Write description here">{{old('description')}}</textarea>
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{route('transactions-for-shop.index')}}" class="btn btn-light">Cancel</a>
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