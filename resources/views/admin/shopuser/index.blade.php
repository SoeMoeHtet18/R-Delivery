@extends('admin.layouts.master')

@section('content')


<div class="create-button">
    <a href="{{route('shopusers.create')}}" class="btn create-btn">Add ShopUser</a>
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
        <div class="caption">ShopUser Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
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
@section('title','Dashboard')
@section('sub-title','Shop User Listing')
<script type="text/javascript">

$(document).ready(function() {
    get_ajax_dynamic_data(search='');
    function get_ajax_dynamic_data(search) {
        var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": '/ajax-get-shop-users-data',
            "type": "GET",
            "data" : function( r ) {
                r.search = search;
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'phone_number', name: 'phone_number'},
            {data: 'email', name: 'email'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
    });
    $('.search_filter').click(function(){
            var search = $('#search').val();
            table.destroy();
            get_ajax_dynamic_data(search);
        });
        $("#reset").click(function(){
            $("#search").val("").trigger("change");
            var search = $('#search').val();
            table.destroy();
            get_ajax_dynamic_data(search);
        });
    }
  });
</script>
@endsection