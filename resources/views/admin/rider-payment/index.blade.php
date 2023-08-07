@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Rider Payment')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <div class="create-button">
        <a href="{{route('rider-payments.create')}}" class="btn create-btn">Add New Rider Payment</a>
    </div>
    <button class="btn btn-link" id="toggleFilter">
        <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.87768 20C7.55976 20 7.29308 19.88 7.07765 19.64C6.86222 19.4 6.75487 19.1033 6.75562 18.75V11.25L0.247706 2C-0.0328074 1.58333 -0.0750713 1.14583 0.120914 0.6875C0.3169 0.229167 0.658378 0 1.14535 0H16.8541C17.3403 0 17.6818 0.229167 17.8785 0.6875C18.0753 1.14583 18.033 1.58333 17.7518 2L11.2438 11.25V18.75C11.2438 19.1042 11.1361 19.4012 10.9207 19.6412C10.7053 19.8812 10.439 20.0008 10.1218 20H7.87768Z" fill="black" />
        </svg>
    </button>
</div>


<div class="card m-3 mt-0 filter-content" style="display: none;">
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
         $("#toggleFilter").on("click", function() {
            $(".filter-content").slideToggle(300);
            $('#rider_name').select2();
        });

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