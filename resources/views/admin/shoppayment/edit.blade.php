@extends('admin.layouts.master')

@section('content')
        <div class="card card-container">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Update Shop Payment</strong>
                </h2>
                <form action="{{route('shoppayments.update', $shop_payment->id)}}" method="POST">
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
                                    <option value="{{$shop->id}}" @if($shop_payment->shop_id == $shop->id) {{'selected'}} @endif>{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="amount" class="col-2">
                            <h4>Amount <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="amount" name="amount" value="{{$shop_payment->amount}}" class="form-control"/>
                            @if ($errors->has('phone_number'))
                                <span class="text-danger"><strong>{{ $errors->first('phone_number') }}</strong></span>
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
                                <option value="delivery_payment" @if($shop_payment->type == "delivery_payment") {{'selected'}} @endif>Delivery Payment</option>
                                <option value="remaining_payment" @if($shop_payment->type == "remaining_payment") {{'selected'}} @endif>Remaining Payment</option>
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
            
        });

    </script>
@endsection   