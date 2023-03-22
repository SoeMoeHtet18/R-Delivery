@extends('admin.layouts.master')

@section('content')
<style>
    .create-button { 
        width: 100px;
        height: 30px;
        margin-bottom: 10px;
    }
</style>

<div class="create-button">
    <a class="btn btn-success" href="{{route('orders.create')}}">Add Order</a>
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
                    <th>Order Code</th>
                    <th>Customer Name</th>
                    <th>Customer Phone Number</th>
                    <th>City</th>
                    <th>Township</th>
                    <th>Rider</th>
                    <th>Shop</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
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

    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
  $(function () {
      
    var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('orders.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'order_code', name: 'order_code'},
            {data: 'customer_name', name: 'customer_name'},
            {data: 'customer_phone_number', name: 'customer_phone_number'},
            {data: 'city_name', name: 'city'},
            {data: 'township_name', name: 'township'},
            {data: 'rider_name', name: 'rider'},
            {data: 'shop_name', name: 'shop'},
            {data: 'quantity', name: 'quantity'},
            {data: 'total_amount', name: 'total_amount'},
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
      
  });
</script>
@endsection