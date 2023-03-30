@extends('admin.layouts.master')

@section('content')


<div class="create-button">
    <a href="{{route('townships.create')}}" class="btn btn-success">Add Township</a>
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
            <label for="search">
                <strong>Search</strong>
            </label>
            <div class="col-10">
                <input type="text" id="search" name="search" class="form-control"/>
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
    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 btncenter margin-btn">
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

    get_ajax_dynamic_data(search='',city='');
    function get_ajax_dynamic_data(search,city) {
        var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": '/ajax-get-townships-data',
            "type": "GET",
            "data" : function( r ) {
                r.search = search;
                r.city = city;
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'city_name', name: 'city'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
    });
    $('.search_filter').click(function(){
        var search = $('#search').val();
        var city = $('#city').val();
        table.destroy();
        get_ajax_dynamic_data(search,city);
    });
    $("#reset").click(function(){
        $("#search").val("").trigger("change");
        $("#city").val("").trigger("change");
        var search = $('#search').val();
        var city = $('#city').val();
        table.destroy();
        get_ajax_dynamic_data(search,city);
    })
    };
  });
</script>
@endsection