@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Rider Payment Editing')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Rider Payment</strong>
        </h2>
        <form action="{{route('rider-payments.update', $rider_payment->id)}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row m-0 mb-3">
                <label for="rider_id" class="col-2">
                    <h4>Rider Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="rider_id" id="rider_id" class="form-control">
                        <option value="" selected disabled>Select the Shop for This Payment</option>
                        @foreach ( $riders as $rider)
                        <option value="{{$rider->id}}" @if($rider_payment->rider_id == $rider->id) {{'selected'}} @endif>{{$rider->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount" value="{{$rider_payment->total_amount}}" class="form-control" />
                    @if ($errors->has('total_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_routine" class="col-2">
                    <h4>Total Routine <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_routine" name="total_routine" value="{{$rider_payment->total_routine}}" class="form-control" />
                    @if ($errors->has('total_routine'))
                    <span class="text-danger"><strong>{{ $errors->first('total_routine') }}</strong></span>
                    @endif
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