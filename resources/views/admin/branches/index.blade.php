@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Branch')
@section('content')


<div class="create-button" style="margin-bottom: 50px">
    <a href="{{route('branches.create')}}" class="btn create-btn">Add New Branch</a>
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
                <label for="city">
                    <strong>City Name</strong>
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
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 btncenter margin-btn">
            <button class="btn btn-primary search_filter">Filter</button>

            <button class="btn btn-secondary" id="reset">Reset</button>
        </div>
    </div>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Branch Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Branch Name</th>
                    <th>City Name</th>
                    <th>Phone Number</th>
                    <th>Address</th>
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
        $('#city').select2();

        get_ajax_dynamic_data(city = '');

        function get_ajax_dynamic_data(city) {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-branch-data',
                    "type": "GET",
                    "data": function(r) {
                        r.city = city;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'branch_name'
                    },
                    {
                        data: 'city_name',
                        name: 'city_name'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'address',
                        name: 'address'
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
                var city = $('#city').val();
                table.destroy();
                get_ajax_dynamic_data(city);
            });
            $("#reset").click(function() {
                $("#city").val("").trigger("change");
                var city = $('#city').val();
                table.destroy();
                get_ajax_dynamic_data(city);
            });
        }
    })
</script>
@endsection