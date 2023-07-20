@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Collection Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Collection</strong>
        </h2>
        <form action="{{route('collections.store')}}" method="POST" class="action-form">
            @csrf
            <div class="row m-0 mb-3">
                <label for="total_quantity" class="col-2">
                    <h4>Total Quantity <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_quantity" name="total_quantity" class="form-control" />
                    @if ($errors->has('total_quantity'))
                    <span class="text-danger"><strong>{{ $errors->first('total_quantity') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount" class="form-control" />
                    @if ($errors->has('total_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_quantity" class="col-2">
                    <h4>Paid Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="paid_amount" name="paid_amount" class="form-control" />
                    @if ($errors->has('paid_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('paid_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="rider_id" class="col-2">
                    <h4>Rider <b>:</b></h4>
                </label>
                <div class="ps-4 col-10">
                    <select name="rider_id" id="rider_id" class="form-control">
                        <option value="" selected disabled>Select the Rider for This Collection</option>
                        @foreach ( $riders as $rider)
                        <option value="{{$rider->id}}" 
                            @if($rider->id == old('rider_id')) selected
                            @endif
                            >{{$rider->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('rider_id'))
                    <span class="text-danger"><strong>{{ $errors->first('rider_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="shop_id" class="col-2">
                    <h4>Shop <b>:</b></h4>
                </label>
                <div class="ps-4 col-10">
                    <select name="shop_id" id="shop_id" class="form-control">
                        <option value="" selected disabled>Select the Rider for This Collection</option>
                        @foreach ( $shops as $shop)
                        <option value="{{$shop->id}}" 
                            @if($shop->id == old('shop_id')) selected
                            @endif
                            >{{$shop->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('shop_id'))
                    <span class="text-danger"><strong>{{ $errors->first('shop_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="assigned_at" class="col-2">
                    <h4>Assigned At <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="date" id="assigned_at" name="assigned_at" class="form-control" />
                    @if ($errors->has('assigned_at'))
                    <span class="text-danger"><strong>{{ $errors->first('assigned_at') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="collected_at" class="col-2">
                    <h4>Collected At <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="date" id="collected_at" name="collected_at" class="form-control" />
                    @if ($errors->has('collected_at'))
                    <span class="text-danger"><strong>{{ $errors->first('collected_at') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="note" class="col-2">
                    <h4>Note <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="note" name="note" class="form-control" />
                    @if ($errors->has('note'))
                    <span class="text-danger"><strong>{{ $errors->first('note') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="status" class="col-2">
                    <h4>Status <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="status" name="status" class="form-control" />
                    @if ($errors->has('status'))
                    <span class="text-danger"><strong>{{ $errors->first('status') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{route('collections.index')}}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection