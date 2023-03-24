@extends('admin.layouts.master')

@section('content')
<style>
    .card-toolbar{
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
    }
    .create-button { 
        width: 70px;
        height: 30px;
        margin-bottom: 10px;
    }
</style>
        <div class="card card-container detail-card">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Rider Detail</strong>
                </h2>
                <div class="card-toolbar">
                    <div class="create-button">
                    <a href="{{route('riders.edit' , $rider->id)}}" class="btn btn-light">Edit</a>
                    </div>
                    <form action="{{route('riders.destroy', $rider->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this rider?`);">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger float-end">
                    </form>
                </div>
                <div class="detail-infos">
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Name <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            {{ $rider->name }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Phone Number <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            {{ $rider->phone_number }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Email <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            {{ $rider->email }}
                        </div>
                    </div>
                    
                </div>
                <hr>
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a href="#pending-order-display" id="pending-order-tab" class="nav-link active" data-toggle="tab">Pending Orders</a>
                    </li>
                    <li class="nav-item">
                        <a href="#order-history-display" id="order-history-tab" class="nav-link" data-toggle="tab">Orders History</a>
                    </li>
                </ul>
                <input type="hidden" id="current_screen" value="pending-order-tab">
                <div class="tab-content">
                                <div id="pending-order-display" class="portlet box green tab-pane active">
                                    <div class="portlet-title">
                                        <div class="caption">Pending Orders</div>
                                    </div>
                                    <div class="portlet-body">
                                        <table id="pending-order-datatable" class="table table-striped table-hover table-responsive datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Order Code</th>
                                                    <th>Customer Name</th>
                                                    <th>Customer Phone Number</th>
                                                    <th>Township</th>
                                                    <th>Shop</th>
                                                    <th>Quantity</th>
                                                    <th>Total Amount</th>
                                                    <th>Delivery Fees</th>
                                                    <th>Markup Delivery Fees</th>
                                                    <th>Remark</th>
                                                    <th>Item Type</th>
                                                    <th>Full Address</th>
                                                    <th>Schedule Date</th>
                                                    <th>Type</th>
                                                    <th>Collection Method</th>
                                                    <th>Last Updated By</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="order-history-display" class="portlet box green tab-pane">
                                    <div class="portlet-title">
                                        <div class="caption">Pending Orders</div>
                                    </div>
                                    <div class="portlet-body">
                                        <table id="order-history-datatable" class="table table-striped table-hover table-responsive datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Order Code</th>
                                                    <th>Customer Name</th>
                                                    <th>Customer Phone Number</th>
                                                    <th>Township</th>
                                                    <th>Shop</th>
                                                    <th>Quantity</th>
                                                    <th>Total Amount</th>
                                                    <th>Delivery Fees</th>
                                                    <th>Markup Delivery Fees</th>
                                                    <th>Remark</th>
                                                    <th>Item Type</th>
                                                    <th>Full Address</th>
                                                    <th>Schedule Date</th>
                                                    <th>Type</th>
                                                    <th>Collection Method</th>
                                                    <th>Last Updated By</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    
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
            var rider_id = {!!json_encode($rider['id'])!!};
            $('#pending-order-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "/riders/get-pending-orders-by-rider-id/" + rider_id,
                    columns: [
                        {data: 'DT_RowIndex', name: 'id'},
                        {data: 'order_code', name: 'order_code'},
                        {data: 'customer_name', name: 'customer_name'},
                        {data: 'customer_phone_number', name: 'customer_phone_number'},
                        {data: 'township_name', name: 'township'},
                        {data: 'shop_name', name: 'shop'},
                        {data: 'quantity', name: 'quantity'},
                        {data: 'total_amount', name: 'total_amount'},
                        {data: 'delivery_fees', name: 'delivery_fees'},
                        {data: 'markup_delivery_fees', name: 'markup_delivery_fees'},
                        {data: 'remark', name: 'remark'},
                        {data: 'item_type', name: 'item_type'},
                        {data: 'full_address', name: 'full_address'},
                        {data: 'schedule_date', name: 'schedule_date'},
                        {data: 'type', name: 'type'},
                        {data: 'collection_method', name: 'collection_method'},
                        {data: 'last_updated_by_name', name: 'last_updated_by'},
                    ]
                });
                
            $('#order-history-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "/riders/get-order-history-by-rider-id/" + rider_id,
                    columns: [
                        {data: 'DT_RowIndex', name: 'id'},
                        {data: 'order_code', name: 'order_code'},
                        {data: 'customer_name', name: 'customer_name'},
                        {data: 'customer_phone_number', name: 'customer_phone_number'},
                        {data: 'township_name', name: 'township'},
                        {data: 'shop_name', name: 'shop'},
                        {data: 'quantity', name: 'quantity'},
                        {data: 'total_amount', name: 'total_amount'},
                        {data: 'delivery_fees', name: 'delivery_fees'},
                        {data: 'markup_delivery_fees', name: 'markup_delivery_fees'},
                        {data: 'remark', name: 'remark'},
                        {data: 'item_type', name: 'item_type'},
                        {data: 'full_address', name: 'full_address'},
                        {data: 'schedule_date', name: 'schedule_date'},
                        {data: 'type', name: 'type'},
                        {data: 'collection_method', name: 'collection_method'},
                        {data: 'last_updated_by_name', name: 'last_updated_by'},
                    ]
                });
            });
    </script>
     <script>
        $(document).ready(function(){
            $('.nav-tabs a').click(function(){
                $(this).tab('show');
            });
        });
    </script>
@endsection