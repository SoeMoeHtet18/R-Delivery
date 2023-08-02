@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Rider Payment')
@section('content')


<div class="create-button">
    <a href="{{route('rider-payments.create')}}" class="btn create-btn">Add New Rider Payment</a>
</div>

<div class="card m-3">
    <div class="row tdFilter">
        <div class="col-md-12 col-sm-12 m-3">
            <h2>Filter</h2>
        </div>
    </div>
    <div class="row">
        <div class="filter-box">
            <div class="mb-3 p-3 col-4">
                <label for="rider_name">
                    <strong>Rider</strong>
                </label>
                <div class="col-10">
                    <select name="rider_name" id="rider_name" class="form-control">
                        <option value="" selected disabled>Select</option>
                        @foreach($riders as $rider)
                        <option value="{{$rider->id}}">{{$rider->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
        </div>
        

    </div>
    <div class="d-flex flex-row-reverse pb-3">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 btncenter margin-btn">
            <button class="btn btn-primary search_filter">Filter</button>

            <button class="btn btn-secondary" id="reset">Reset</button>
        </div>
    </div>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Rider Payment Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Rider Name</th>
                    <th>Total Amount</th>
                    <th>Total Routine</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@endsection
@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {
        $('#rider_name').select2();

        get_ajax_dynamic_data(rider_name = '');

        function get_ajax_dynamic_data(rider_name) {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-rider-payment-data',
                    "type": "GET",
                    "data": function(r) {
                        r.rider_name = rider_name;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'rider_name',
                        name: 'rider_name'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'total_routine',
                        name: 'total_routine'
                    },
                    
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                
            });
            $('.search_filter').click(function() {
                var rider_name = $('#rider_name').val();
                table.destroy();
                get_ajax_dynamic_data(rider_name);
            });
            $("#reset").click(function() {
                $("#rider_name").val("").trigger("change");
                var rider_name = $('#rider_name').val();
                table.destroy();
                get_ajax_dynamic_data(rider_name);
            });
        }
    })
</script>
@endsection