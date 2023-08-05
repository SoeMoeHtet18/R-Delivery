@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Order')
@section('more-sub-title')
<li class="page-sub-title">Assign Rider</li>
@endsection
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Assign Rider To Order</strong>
        </h2>
        <form action="{{url('/orders/'.$order->id.'/assign-rider')}}" method="post" class="action-form">
            @csrf
            @method('post')
            <div class="row m-0 mb-3">
                <label for="township_id" class="col-2">
                    <h4>Township Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="township_id" id="township_id" class="form-control">
                        <option value="" selected disabled>Select Township For This Order</option>
                        @foreach($townships as $township)
                        <option value="{{$township->id}}" @if($order->township_id == $township->id) {{'selected'}} @endif>{{$township->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('township_id'))
                    <span class="text-danger"><strong>{{ $errors->first('township_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <input type="hidden" name="assigned_rider" value="{{$order->rider_id}}" id="assigned_rider" class="form-control">
            <div class="row m-0 mb-3">
                <label for="rider_id" class="col-2">
                    <h4>Rider Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="rider_id" id="rider_id" class="form-control">
                        <option value="" selected disabled>Select Rider For This Order</option>
                        {{--@foreach($riders as $rider)
                        <option value="{{$rider->id}}">{{$rider->name}}</option>
                        @endforeach--}}
                    </select>
                    @if ($errors->has('rider_id'))
                    <span class="text-danger"><strong>{{ $errors->first('rider_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <input type="submit" value="Assign" class="btn btn-success float-end">
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        $('#township_id').select2();
        $('#rider_id').select2();

        getRidersByTownshipId();
        function getRidersByTownshipId() {
            var township_id = $('#township_id').val();
            $.ajax({
                url: '/get-riders-by-township',
                type: 'GET',
                dataType: 'json',
                data: {
                    township_id : township_id
                },
                success: function(response) {
                    var assigned_rider = $('#assigned_rider').val();
                    var riders = '<option value="" selected disabled>Select Rider For This Order</option>';
                    if (response.data) {
                        for (var i = 0; i < response.data.length; i++) {
                            if (assigned_rider == response.data[i].id) {
                                riders += '<option value="' + response.data[i].id + '" selected>' + response.data[i].name + '</option>';
                            } else {
                                riders += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>'; 
                            } 
                        }
                    }
                    console.log(riders);
                    $('#rider_id').html(riders);
                },
            });
        }

        $('#township_id').change(function() {
            getRidersByTownshipId();
        });
    });
    
</script>
@endsection