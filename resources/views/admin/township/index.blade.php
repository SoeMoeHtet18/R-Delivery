@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Township Listing')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <div class="create-button">
        <a href="{{route('townships.create')}}" class="btn create-btn">Add Township</a>
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
                <label for="search">
                    <strong>Search</strong>
                </label>
                <div class="col-10">
                    <input type="text" id="search" name="search" class="form-control" />
                </div>
            </div>
            <div class="mb-3 p-3 col-4">
                <label for="city">
                    <strong>City</strong>
                </label>
                <div class="col-10">
                    <select name="city" id="city" class="form-control">
                        <option value="" selected disabled>Select</option>
                        @foreach($cities as $city)
                        <option value="{{$city->id}}">{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </div>
    <div class="d-flex flex-row-reverse pb-3">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 btncenter margin-btn filter-footer">
            <button class="btn btn-primary search_filter">Filter</button>

            <button class="btn btn-secondary" id="reset">Reset</button>
        </div>
    </div>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Township Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>City</th>
                    <th>Delivery Fees</th>
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
            $('#city').select2({width: '100%'});
        });

        get_ajax_dynamic_data(search = '', city = '');

        function get_ajax_dynamic_data(search, city) {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-townships-data',
                    "type": "GET",
                    "data": function(r) {
                        r.search = search;
                        r.city = city;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'city_name',
                        name: 'city'
                    },
                    {
                        data: 'delivery_fees',
                        name: 'delivery_fees'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    {
                        "render": function(data, type, row) {
                            return '<a href="/townships/' + row.id + '">' + row.name + '</a>';
                        },
                        "targets": 1
                    },
                    {
                        "render": function(data, type, row) {
                            return '<a href="/cities/' + row.city_id + '">' + row.city_name + '</a>';
                        },
                        "targets": 2
                    },
                ]
            });
            $('.search_filter').click(function() {
                var search = $('#search').val();
                var city = $('#city').val();
                table.destroy();
                get_ajax_dynamic_data(search, city);
            });
            $("#reset").click(function() {
                $("#search").val("").trigger("change");
                $("#city").val("").trigger("change");
                var search = $('#search').val();
                var city = $('#city').val();
                table.destroy();
                get_ajax_dynamic_data(search, city);
            })
        };
    });
</script>
@endsection