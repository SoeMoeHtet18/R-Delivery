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
                        <option value="{{$rider->id}}" @if($rider->id == old('rider_id')) selected @endif>{{$rider->name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('rider_id'))
                    <span class="text-danger"><strong>{{ $errors->first('rider_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3" id="date_input">
                <label for="date" class="col-2">
                    <h4>Date <b>:</b></h4>
                </label>
                <div class="col-10">
                    <?php
                    // Get today's date
                    $today = date('Y-m-d');
                    // Set the default value to today's date
                    $defaultDate = old('date') ?? $today;
                    ?>
                    <input type="date" id="date" name="date" value="<?php echo $defaultDate; ?>" class="form-control" />
                    @if ($errors->has('date'))
                        <span class="text-danger"><strong>{{ $errors->first('date') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3" id="date_input2">
                <label for="monthly" class="col-2">
                    <h4>Monthly <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="month" id="monthly" name="monthly" value="{{ old('monthly', date('Y-m')) }}" class="form-control" />
                    @if ($errors->has('monthly'))
                        <span class="text-danger"><strong>{{ $errors->first('monthly') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="pick_up" class="col-2">
                    <h4>Total Pick-Up Ways <b>:</b></h4>
                </label>
                <div class="col-4">
                    <input type="text" id="pick_up" name="pick_up" class="form-control" readonly/>
                    @if($errors->has('pick_up'))
                    <span class="text-danger"><strong>{{ $errors->first('pick_up') }}</strong></span>
                    @endif
                </div>
                <label for="delivery" class="col-2">
                    <h4>Total Delivery Ways <b>:</b></h4>
                </label>
                <div class="col-4">
                    <input type="text" id="delivery" name="delivery" class="form-control" readonly/>
                    @if($errors->has('delivery'))
                    <span class="text-danger"><strong>{{ $errors->first('delivery') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="deficit" class="col-2">
                    <h4>Deficit <b>:</b></h4>
                </label>
                <div class="col-4">
                    <input type="text" id="deficit" name="deficit" class="form-control" readonly/>
                    @if($errors->has('deficit'))
                    <span class="text-danger"><strong>{{ $errors->first('deficit') }}</strong></span>
                    @endif
                </div>
                <label for="type" class="col-2">
                    <h4>Salary Type <b>:</b></h4>
                </label>
                <div class="col-4">
                    <input type="text" id="type" name="type" class="form-control" readonly/>
                    @if($errors->has('type'))
                    <span class="text-danger"><strong>{{ $errors->first('type') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="base_salary" class="col-2">
                    <h4>Base Salary <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="base_salary" name="base_salary" class="form-control" readonly/>
                    @if($errors->has('base_salary'))
                    <span class="text-danger"><strong>{{ $errors->first('base_salary') }}</strong></span>
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
            <input type="hidden" id="total_routine" name="total_routine" class="form-control" />
            {{--<div class="row m-0 mb-3">
                <label for="total_routine" class="col-2">
                    <h4>Total Routine <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_routine" name="total_routine" value="@isset($total_routine){{ $total_routine }}@else{{ old('total_routine') }}@endisset" class="form-control" />
                    @if($errors->has('total_routine'))
                    <span class="text-danger"><strong>{{ $errors->first('total_routine') }}</strong></span>
                    @endif
                </div>
            </div>--}}
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
        // $('#type').select2();
        // $('#type').change(function() {
        //     console.log('success');
        //     var type = $('#type').val();
        //     if(type == 'daily'){
        //         $('#date_input').show();
        //         $('#date_input2').hide();
        //     }
        //     if(type == 'monthly'){
        //         $('#date_input2').show();
        //         $('#date_input').hide();
        //     }
        //     $.ajax({
        //         url: '/get-rider-by-type',
        //         type: 'GET',
        //         dataType: 'json',
        //         data: {
        //             type: type
        //         },
        //         success: function(response) {
        //             // var township_id = $('#township_id').val();
        //             var riders = '<option value="" selected disabled>Select the Rider of this Payment</option>';
        //             if (response.data) {
        //                 for (var i = 0; i < response.data.length; i++) {
        //                     riders += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>'; 
        //                 }
        //             }
        //             console.log(riders);
        //             $('#rider_id').html(riders);
        //         },
        //     });
        // });
        $('#rider_id').select2({width: '100%'});
        $('#date_input, #date_input2').hide();
        $(".select2-selection").on("focus", function () {
            $(this).parent().parent().prev().select2("open");
        });
        
        function updateRiderTotalSalaryBaseOnDate() {
            var rider_id = $('#rider_id').val();
            var type = $('#type').val();
            var daily = $('#date').val();
            var monthly = $('#monthly').val();

            $.ajax({
                url: '/get-rider-total-salary-by-date',
                type: 'GET',
                dataType: 'json',
                data: {
                    type: type,
                    rider_id: rider_id,
                    daily: daily,
                    monthly: monthly
                },
                success: function(response) {
                    console.log(response);
                    $('#pick_up').val(response.data.total_pick_up_count);
                    $('#delivery').val(response.data.total_deli_count);
                    $('#deficit').val(response.data.deficit_fees);
                    $('#total_amount').val(response.data.total_salary);
                    $('#base_salary').val(response.data.base_salary);
                    $('#total_routine').val(response.data.total_pick_up_count + response.data.total_deli_count);
                    var salary_type = response.data.salary_type;
                    $('#type').val(salary_type);
                    if(salary_type == 'daily'){
                        $('#date_input').show();
                        $('#date_input2').hide();
                    } else{
                        $('#date_input').hide();
                        $('#date_input2').show();
                    }
                },
            });
        }
        
        // Initialize values if rider_id is present in the URL
        var urlParams = new URLSearchParams(window.location.search);
        var riderId = urlParams.get('rider_id');
        if (riderId) {
            $('#rider_id').val(riderId).trigger('change.select2');
            updateRiderTotalSalaryBaseOnDate();
        }

        $('#rider_id, #date, #monthly').change(updateRiderTotalSalaryBaseOnDate);
    });
</script>
@endsection