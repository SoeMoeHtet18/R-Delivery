@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Rider Listing')
@section('content')

<div class="create-button">
    <a href="{{route('riders.create')}}" class="btn btn-success">Add Rider</a>
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
                <strong>Rider Name</strong>
            </label>
            <div class="col-10">
                <input type="text" id="rider_name" name="rider_name" class="form-control"/>
            </div>
        </div>
        <div class="mb-3 p-3 col-4">
            <label for="phone_number">
                <strong>Phone Number</strong>
            </label>
            <div class="col-10">
                <input type="text" id="phone_number" name="phone_number" class="form-control"/>
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
        <div class="caption">Rider Lists</div>
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
<script type="text/javascript">

$(document).ready(function() {
    get_ajax_dynamic_data(rider_name='',phone_number='');
    function get_ajax_dynamic_data(rider_name,phone_number) {
        var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": '/ajax-get-riders-data',
            "type": "GET",
            "data" : function( r ) {
                r.rider_name = rider_name;
                r.phone_number = phone_number;
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
            var rider_name = $('#rider_name').val();
            var phone_number = $('#phone_number').val();
            table.destroy();
            get_ajax_dynamic_data(rider_name,phone_number);
        });
        $("#reset").click(function(){
            $("#rider_name").val("").trigger("change");
            $("#phone_number").val("").trigger("change");
            var rider_name = $('#rider_name').val();
            var phone_number = $('#phone_number').val();
            table.destroy();
            get_ajax_dynamic_data(rider_name,phone_number);
        });
    }
  });
</script>
@endsection