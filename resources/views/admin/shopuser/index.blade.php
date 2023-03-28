@extends('admin.layouts.master')

@section('content')

<div class="card m-3">
<div class="row tdFilter">
    <div class="col-md-12 col-sm-12 m-3"> 
        <h2>Filter</h2>
    </div>
    </div>
    <div class="row">
    <div class="filter-box">
        <div class="mb-3 p-3 col-4">
            <label for="name">
                <strong>Name</strong>
            </label>
            <div class="col-10">
                <input type="text" id="name" name="name" class="form-control"/>
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

<div class="create-button">
    <a href="{{route('shopusers.create')}}" class="btn btn-success">Add ShopUser</a>
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

    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">

$(document).ready(function() {
    get_ajax_dynamic_data(name='',phone_number='');
    function get_ajax_dynamic_data(name,phone_number) {
        var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": '/ajax-get-shop-users-data',
            "type": "GET",
            "data" : function( r ) {
                r.name = name;
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
            var name = $('#name').val();
            var phone_number = $('#phone_number').val();
            table.destroy();
            get_ajax_dynamic_data(name,phone_number);
        });
        $("#reset").click(function(){
            $("#name").val("").trigger("change");
            $("#phone_number").val("").trigger("change");
            var shop_name = $('#name').val();
            var phone_number = $('#phone_number').val();
            table.destroy();
            get_ajax_dynamic_data(shop_name,phone_number);
        });
    }
  });
</script>
@endsection