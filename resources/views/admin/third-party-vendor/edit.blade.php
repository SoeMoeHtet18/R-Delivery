@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Third Party Vendor Editing')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Third Party Vendor</strong>
        </h2>
        <form action="{{route('third-party-vendor.update', $thirdPartyVendor->id)}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Third Party Vendor Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" value="{{$thirdPartyVendor->name}}" class="form-control" />
                    @if ($errors->has('name'))
                    <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            
            <div class="row m-0 mb-3">
                <label for="address" class="col-2">
                    <h4>Address <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="address" name="address" value="{{$thirdPartyVendor->address}}" class="form-control" />
                    @if ($errors->has('address'))
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
                        <option value="pickup" @if($thirdPartyVendor->type == 'pickup' ) selected @endif>Pick Up</option>
                        <option value="dropoff" @if($thirdPartyVendor->type == 'dropoff' ) selected @endif>Drop Off</option>
                    </select>
                    @if ($errors->has('type'))
                    <span class="text-danger"><strong>{{ $errors->first('type') }}</strong></span>
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
        $('#type').select2();
        $(".select2-selection").on("focus", function () {
            $(this).parent().parent().prev().select2("open");
        });
    });
</script>
@endsection