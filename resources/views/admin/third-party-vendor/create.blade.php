@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Third Party Vendor Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Third Party Vendor</strong>
        </h2>
        <form action="{{route('third-party-vendor.store')}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Third Party Vendor Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" value="@isset($name){{ $name }}@else{{ old('name') }}@endisset" class="form-control" />
                    @if($errors->has('name'))
                    <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="address" class="col-2">
                    <h4>Address <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="address" name="address" value="@isset($address){{ $address }}@else{{ old('address') }}@endisset" class="form-control" />
                    @if($errors->has('address'))
                    <span class="text-danger"><strong>{{ $errors->first('address') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="type" class="col-2">
                    <h4>Type <b>:</b></h4>
                </label>
                <div class=" col-10">
                    <select name="type" id="type" class="form-control">
                        <option value="pickup" @if(old('collection_method')=='pickup' ) selected @endif>Pick Up</option>
                        <option value="dropoff" @if(old('collection_method')=='dropoff' ) selected @endif selected>Drop Off</option>
                    </select>
                    @if ($errors->has('type'))
                    <span class="text-danger"><strong>{{ $errors->first('type') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{route('branches.index')}}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        $('#type').select2();
    });
</script>
@endsection