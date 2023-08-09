@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Gate Editing')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Gate</strong>
        </h2>
        <form action="{{route('gates.update', $gate->id)}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="gate_id" name="gate_id" value="{{$gate->id}}" class="form-control" />
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" value="{{$gate->name}}" class="form-control" />
                    @if($errors->has('name'))
                    <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="city_id" class="col-2">
                    <h4>City Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="city_id" id="city_id" class="form-control">
                        <option value="" selected disabled>Select the City for This Gate</option>
                        @foreach ( $cities as $city)
                        <option value="{{$city->id}}" @if($gate->city_id == $city->id) {{'selected'}} @endif>{{$city->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('city_id'))
                    <span class="text-danger"><strong>{{ $errors->first('city_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3 selectsearch">
                <label for="township_id" class="col-2">
                    <h4>Township Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="township_id[]" id="township_id" class="form-control" multiple="">
                        @foreach ( $townships as $township)
                        <option value="{{$township->id}}" @if(in_array($township->id, $assignedTownshipID)) selected @endif>{{$township->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('township_id'))
                    <span class="text-danger"><strong>{{ $errors->first('township_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="address" class="col-2">
                    <h4>Address <b>:</b></h4>
                </label>
                <div class="col-10">
                    <textarea id="address" name="address" class="form-control" style="height: 100px">{{$gate->address}}</textarea>
                    @if($errors->has('address'))
                    <span class="text-danger"><strong>{{ $errors->first('address') }}</strong></span>
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
        $('#city_id').select2();
        $('#township_id').select2();
        $('#city_id').change(function() {
            var city_id = $('#city_id').val();
            var gate_id = $('#gate_id').val();
            $.ajax({
                url: '/api/get-township-by-associable',
                type: 'POST',
                dataType: 'json',
                data: {
                    city_id: city_id,
                    associable_id: gate_id,
                },
                success: function(response) {
                    var township_id = $('#township_id').val();
                    var townships = '<option value="" disabled>Select the Township for This Order</option>';
                    if (response.data) {
                        for (var i = 0; i < response.data.length; i++) {
                            if (township_id == response.data[i].id) {
                                townships += '<option value="' + response.data[i].id + '" selected>' + response.data[i].name + '</option>';
                            } else {
                                townships += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>';
                            }
                        }
                    }
                    console.log(townships);
                    $('#township_id').html(townships);
                },
            });
        });
    });
</script>
@endsection