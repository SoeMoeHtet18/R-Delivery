@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Township Editing')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Township</strong>
        </h2>
        <form action="{{route('townships.update', $township->id)}}" method="POST" class="action-form">
            @csrf
            @method('PUT')
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" value="{{$township->name}}" class="form-control" />
                    @if ($errors->has('name'))
                    <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="city" class="col-2">
                    <h4>City <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="city" id="city" class="form-control">
                        <option value="" selected disabled>Select City for This Township</option>
                        @foreach ( $cities as $city)
                        <option value="{{$city->id}}" @if($township->city_id == $city->id) {{'selected'}} @endif>{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="delivery_fees" class="col-2">
                    <h4>Delivery Fees <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="delivery_fees" name="delivery_fees" value="{{$township->delivery_fees}}" class="form-control" />
                    @if ($errors->has('delivery_fees'))
                    <span class="text-danger"><strong>{{ $errors->first('delivery_fees') }}</strong></span>
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
        $('#city').select2({width: '100%'});
        $(".select2-selection").on("focus", function () {
            $(this).parent().parent().prev().select2("open");
        });
    });
</script>
@endsection