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
            <label for="shop_name">
                <strong>Shop Name</strong>
            </label>
            <div class="col-10">
                <input type="text" id="shop_name" name="shop_name" class="form-control"/>
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
    <a href="{{route('shops.create')}}" class="btn btn-success">Add Shop</a>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Shop Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
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
    get_ajax_dynamic_data(shop_name='',phone_number='');
    function get_ajax_dynamic_data(shop_name,phone_number) {
        var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": '/ajax-get-shops-data',
            "type": "GET",
            "data" : function( r ) {
                r.shop_name = shop_name;
                r.phone_number = phone_number;
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'address', name: 'address'},
            {data: 'phone_number', name: 'phone_number'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],  
    });
        $('.search_filter').click(function(){
            var shop_name = $('#shop_name').val();
            var phone_number = $('#phone_number').val();
            table.destroy();
            get_ajax_dynamic_data(shop_name,phone_number);
        });
        $("#reset").click(function(){
            $("#shop_name").val("").trigger("change");
            $("#phone_number").val("").trigger("change");
            var shop_name = $('#shop_name').val();
            var phone_number = $('#phone_number').val();
            table.destroy();
            get_ajax_dynamic_data(shop_name,phone_number);
        });
    }
  });
</script>
@endsection