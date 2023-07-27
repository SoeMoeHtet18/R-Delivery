@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Collection Editing')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Collection</strong>
        </h2>
        <form action="{{route('collections.update', $collection->id)}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row m-0 mb-3">
                <label for="total_quantity" class="col-2">
                    <h4>Total Quantity of Collection <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_quantity" name="total_quantity" value="{{$collection->total_quantity}}" class="form-control" readonly />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount of Collection <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount" value="{{$collection->total_amount}}" class="form-control" readonly />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="paid_amount" class="col-2">
                    <h4>Paid Amount By Rider <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="paid_amount" name="paid_amount" value="{{$collection->paid_amount}}" class="form-control" readonly />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="collection_group_id" class="col-2">
                    <h4>Collection Group <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="collection_group_id" id="collection_group_id" class="form-control">
                        <option value="" selected disabled>Select the Collection Group for This Collection</option>
                        @foreach ( $collection_groups as $collection_group)
                        <option value="{{$collection_group->id}}" @if($collection->collection_group_id == $collection_group->id) {{'selected'}} @endif>{{$collection_group->id}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="rider_id" class="col-2">
                    <h4>Rider <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="rider_id" id="rider_id" class="form-control">
                        <option value="" selected disabled>Select the Rider for This Collection</option>
                        @foreach ( $riders as $rider)
                        <option value="{{$rider->id}}" @if($collection->rider_id == $rider->id) {{'selected'}} @endif>{{$rider->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="shop_id" class="col-2">
                    <h4>Shop <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="shop_id" id="shop_id" class="form-control">
                        <option value="" selected disabled>Select the Shop for This Collection</option>
                        @foreach ( $shops as $shop)
                        <option value="{{$shop->id}}" @if($collection->shop_id == $shop->id) {{'selected'}} @endif>{{$shop->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="assigned_at" class="col-2">
                    <h4>Assigned At <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="date" id="assigned_at" name="assigned_at" value="{{$assignedAt}}" class="form-control" />
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
                    <input type="date" id="collected_at" name="collected_at" value="{{$collectedAt}}" class="form-control" />
                    @if ($errors->has('collected_at'))
                    <span class="text-danger"><strong>{{ $errors->first('collected_at') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Note <b>:</b></h4>
                </div>
                <div class="col-10">
                    <input type="text" id="note" name="note" value="{{$collection->note}}" class="form-control" />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Status <b>:</b></h4>
                </div>
                <div class="col-10">
                    <select name="status" id="status" class="form-control">
                        <option value="" selected disabled>Select the Status for This Order</option>
                        <option value="pending" @if($collection->status == "pending") {{'selected'}} @endif>Pending</option>
                        <option value="in-warehouse" @if($collection->status == "in-warehouse") {{'selected'}} @endif>In Warehouse</option>
                        <option value="complete" @if($collection->status == "complete") {{'selected'}} @endif>Complete</option>
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
        $('#city_id').select2();
        $('#township_id').select2();
        $('#rider_id').select2();
        $('#shop_id').select2();
        $('#status_id').select2();
        $('#item_type_id').select2();
        $('#type_id').select2();
        $('#collection_method_id').select2();

        $('#type_id').change(function() {
            console.log($('#type_id').val());
            if($('#type_id').val() == 'doortodoor') {
            var today = new Date();
            var futureDate = new Date(today.getTime() + (5 * 24 * 60 * 60 * 1000));

            var formattedDate = futureDate.toISOString().split('T')[0];

            $('#schedule_date').val(formattedDate);

            }
        });

        $('#city_id').change(function() {
            console.log('success');
            var city_id = $('#city_id').val();
            $.ajax({
                url: '/api/townships-get-by-city',
                type: 'POST',
                dataType: 'json',
                data: {
                    city_id: city_id
                },
                success: function(response) {
                    var townships = '<option value="" selected disabled>Select the Township for This Order</option>';
                    if (response.data) {
                        for (let i = 0; i < response.data.length; i++) {
                            townships += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>';
                        }
                    }
                    $('#township_id').html(townships);
                    var riders = '<option value="" selected disabled>Select the Rider for This Order</option>';
                    $('#rider_id').html(riders);
                },
            })
        });

        $('#township_id').change(function() {
            console.log('township changed');
            var township_id = $('#township_id').val();
            console.log(township_id)
            $.ajax({
                url: '/api/riders-get-by-township',
                type: 'POST',
                dataType: 'json',
                data: {
                    township_id: township_id
                },
                success: function(response) {
                    var riders = '<option value="" selected disabled>Select the Rider for This Order</option>';
                    if (response.data) {
                        for (let i = 0; i < response.data.length; i++) {
                            riders += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>';
                        }
                    }
                    $('#rider_id').html(riders);
                },
            })
        });
    });
</script>
@endsection