@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Rider Payment Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Rider Payment</strong>
        </h2>
        <form action="{{route('rider-payments.store')}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            <div class="row m-0 mb-3">
                <label for="rider_id" class="col-2">
                    <h4>Rider Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="rider_id" id="rider_id" class="form-control">
                        <option value="" selected disabled>Select the Rider of this Payment</option>
                        @foreach($riders as $rider)
                        <option value="{{$rider->id}}" @isset($rider_id) @if($rider->id == $rider_id) selected
                            @endif
                            @else
                            @if($rider->id == old('rider_id')) selected
                            @endif
                            @endisset>{{$rider->name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('rider_id'))
                    <span class="text-danger"><strong>{{ $errors->first('rider_id') }}</strong></span>
                    @endif
                </div>
            </div>
            
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount" value="@isset($total_amount){{ $total_amount }}@else{{ old('total_amount') }}@endisset" class="form-control" />
                    @if($errors->has('total_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_routine" class="col-2">
                    <h4>Total Routine <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_routine" name="total_routine" value="@isset($total_routine){{ $total_routine }}@else{{ old('total_routine') }}@endisset" class="form-control" />
                    @if($errors->has('total_routine'))
                    <span class="text-danger"><strong>{{ $errors->first('total_routine') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{route('rider-payments.index')}}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        $('#rider_id').select2();
    });
</script>
@endsection