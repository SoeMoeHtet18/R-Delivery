@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Order Listing')
@section('content')


<div class="create-button">
    <a class="btn btn-success" href="{{route('orders.create')}}">Add Order</a>
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
                <label for="order_code">
                    <strong>Order Code</strong>
                </label>
                <div class="col-10">
                    <input type="text" id="order_code" name="order_code" class="form-control"/>
                </div>
            </div>
            <div class="mb-3 p-3 col-4">
                <label for="customer_name">
                    <strong>Customer Name</strong>
                </label>
                <div class="col-10">
                    <input type="text" id="customer_name" name="customer_name" class="form-control"/>
                </div>
            </div>
            <div class="mb-3 p-3 col-4">
                <label for="customer_phone_number">
                    <strong>Customer Phone Number</strong>
                </label>
                <div class="col-10">
                    <input type="text" id="customer_phone_number" name="customer_phone_number" class="form-control"/>
                </div>
            </div>
        </div>
        
    </div>

    <div class="row">
        <div class="filter-box">
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
            <div class="mb-3 p-3 col-4">
                <label for="status">
                    <strong>Status</strong>
                </label>
                <div class="col-10">
                    <select name="status" id="status" class="form-control">
                        <option value="" selected disabled>Select</option>
                        <option value="pending">Pending</option>
                        <option value="success">Success</option>
                        <option value="delay">Delay</option>
                        <option value="cancel">Cancel</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 p-3 col-4">
                <label for="township">
                    <strong>Township</strong>
                </label>
                <div class="col-10">
                    <select name="township" id="township" class="form-control">
                        <option value="" selected disabled>Select</option>
                        @foreach($townships as $township)
                            <option value="{{$township->id}}">{{$township->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="filter-box">
            <div class="mb-3 p-3 col-4">
                <label for="city">
                    <strong>Rider</strong>
                </label>
                <div class="col-10">
                    <select name="rider" id="rider" class="form-control">
                        <option value="" selected disabled>Select</option>
                        @foreach($riders as $rider)
                            <option value="{{$rider->id}}">{{$rider->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 p-3 col-4">
                <label for="township">
                    <strong>Shop</strong>
                </label>
                <div class="col-10">
                    <select name="shop" id="shop" class="form-control">
                        <option value="" selected disabled>Select</option>
                        @foreach($shops as $shop)
                            <option value="{{$shop->id}}">{{$shop->name}}</option>
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
        <div class="caption">Order Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Total Amount</th>
                    <th>Order Code</th>
                    <th>Shop</th>
                    <th>Rider</th>
                    <th>Customer Name</th>
                    <th>Customer Phone Number</th>
                    <th>City</th>
                    <th>Township</th>
                    <th>Quantity</th>
                    <th>Delivery Fees</th>
                    <th>Markup Delivery Fees</th>
                    <th>Remark</th>
                    <th>Status</th>
                    <th>Item Type</th>
                    <th>Full Address</th>
                    <th>Schedule Date</th>
                    <th>Type</th>
                    <th>Collection Method</th>
                    <th>Last Updated By</th>
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
    $('#city').select2([]);
    $('#status').select2();
    $('#township').select2();
    $('#rider').select2();
    $('#shop').select2();
    
    get_ajax_dynamic_data(order_code='',customer_name='',customer_phone_number='',city='',rider='',shop='',status='',township='');
    function get_ajax_dynamic_data(order_code,customer_name,customer_phone_number,city,rider,shop,status,township) {
        var table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": '/ajax-get-orders-data',
                "type": "GET",
                "data" : function( r ) {
                    r.order_code = order_code;
                    r.customer_name = customer_name;
                    r.customer_phone_number = customer_phone_number;
                    r.city  = city;
                    r.rider = rider;
                    r.shop  = shop;
                    r.status = status;
                    r.township = township;
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'total_amount', name: 'total_amount'},
                {data: 'order_code', name: 'order_code'},
                {data: 'shop_name', name: 'shop'},
                {data: 'rider_name', name: 'rider'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'customer_phone_number', name: 'customer_phone_number'},
                {data: 'city_name', name: 'city'},
                {data: 'township_name', name: 'township'},
                {data: 'quantity', name: 'quantity'},
                {data: 'delivery_fees', name: 'delivery_fees'},
                {data: 'markup_delivery_fees', name: 'markup_delivery_fees'},
                {data: 'remark', name: 'remark'},
                {data: 'status', name: 'status'},
                {data: 'item_type', name: 'item_type'},
                {data: 'full_address', name: 'full_address'},
                {data: 'schedule_date', name: 'schedule_date'},
                {data: 'type', name: 'type'},
                {data: 'collection_method', name: 'collection_method'},
                {data: 'last_updated_by_name', name: 'last_updated_by'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $('.search_filter').click(function(){
            var status = $('#status').val();
            var township = $('#township').val();
            var order_code = $('#order_code').val();
            var customer_name = $('#customer_name').val();
            var customer_phone_number = $('#customer_phone_number').val(); 
            var rider = $('#rider').val();
            var city = $('#city').val();
            var shop = $('#shop').val();
            table.destroy();
            get_ajax_dynamic_data(order_code,customer_name,customer_phone_number,city,rider,shop,status,township);
        });
        $("#reset").click(function(){
            $("#status").val("").trigger("change");
            $("#township").val("").trigger("change");
            $("#order_code").val("").trigger("change");
            $("#customer_name").val("").trigger("change");
            $("#customer_phone_number").val("").trigger("change");
            $("#rider").val("").trigger("change");
            $("#city").val("").trigger("change");
            $("#shop").val("").trigger("change");
            var status = $("#status").val();
            var township = $('#township').val();
            var order_code = $('#order_code').val();
            var customer_name = $('#customer_name').val();
            var customer_phone_number = $('#customer_phone_number').val(); 
            var rider = $('#rider').val();
            var city = $('#city').val();
            var shop = $('#shop').val();
            table.destroy();
            get_ajax_dynamic_data(order_code,customer_name,customer_phone_number,city,rider,shop,status,township);
        });
    };
    
  });
</script>
@endsection 
