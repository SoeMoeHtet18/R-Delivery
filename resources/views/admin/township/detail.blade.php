@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Township Detail')
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
                        <strong>Township Detail</strong>
                    </h2>
                    <div class="card-toolbar">
                    <div class="create-button">
                    <a href="{{route('townships.edit' , $township->id)}}" class="btn btn-light">Edit</a>
                    </div>
                    <form action="{{route('townships.destroy', $township->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this township?`);">
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
                            {{ $township->name }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>City <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            {{ $township->city->name }}
                        </div>
                    </div>
                </div>
                <hr>
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a href="#current-order-display" id="current-order-tab" class="nav-link active" data-toggle="tab">Current Orders</a>
                    </li>
                    <li class="nav-item">
                        <a href="#completed-order-display" id="completed-order-tab" class="nav-link" data-toggle="tab">Completed Orders</a>
                    </li>
                    <li class="nav-item">
                        <a href="#canceled-order-display" id="cancel-order-tab" class="nav-link" data-toggle="tab">Canceled Orders</a>
                    </li>
                </ul>
                <input type="hidden" id="current_screen" value="current-order-tab">
                <div class="tab-content">
                <div id="current-order-display" class="portlet box green tab-pane active">
                    <div class="portlet-title">
                        <div class="caption">Pending Orders</div>
                    </div>
                    <div class="portlet-body">
                        <table id="current-order-datatable" class="table table-striped table-hover table-responsive datatable">
                            <thead>
                                <tr>
                                <th>#</th>
                                        <th>Total Amount</th>
                                        <th>Order Code</th>
                                        <th>Shop</th>
                                        <th>Rider</th>
                                        <th>Customer Name</th>
                                        <th>Customer Phone Number</th>
                                        <th>Quantity</th>
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
                    <div id="completed-order-display" class="portlet box green tab-pane">
                        <div class="portlet-title">
                            <div class="caption">ShopOrder Lists</div>
                        </div>
                        <div class="portlet-body">
                            <table id="completed-order-datatable" class="table table-striped table-hover table-responsive datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Total Amount</th>
                                        <th>Order Code</th>
                                        <th>Shop</th>
                                        <th>Rider</th>
                                        <th>Customer Name</th>
                                        <th>Customer Phone Number</th>
                                        <th>Quantity</th>
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
                    <div id="canceled-order-display" class="portlet box green tab-pane">
                        <div class="portlet-title">
                            <div class="caption">ShopOrder Lists</div>
                        </div>
                        <div class="portlet-body">
                            <table id="canceled-order-datatable" class="table table-striped table-hover table-responsive datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Total Amount</th>
                                        <th>Order Code</th>
                                        <th>Shop</th>
                                        <th>Rider</th>
                                        <th>Customer Name</th>
                                        <th>Customer Phone Number</th>
                                        <th>Quantity</th>
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
<script type="text/javascript">
        $(function () {
            var township_id = {!!json_encode($township['id'])!!};
            $('#current-order-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/townships/"+ township_id+"/get-pending-orders-by-township-id" ,
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'order_code', name: 'order_code'},
                    {data: 'shop_name', name: 'shop'},
                    {data: 'rider_name', name: 'rider'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'customer_phone_number', name: 'customer_phone_number'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'delivery_fees', name: 'delivery_fees'},
                    {data: 'markup_delivery_fees', name: 'markup_delivery_fees'},
                    {data: 'remark', name: 'remark'},
                    {data: 'item_type', name: 'item_type'},
                    {data: 'full_address', name: 'full_address'},
                    {data: 'schedule_date', name: 'schedule_date'},
                    {data: 'type', name: 'type'},
                    {data: 'collection_method', name: 'collection_method'},
                    {data: 'last_updated_by_name', name: 'last_updated_by'},
                ],
            });
            $('#completed-order-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/townships/"+ township_id+"/get-completed-orders-by-township-id" ,
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'order_code', name: 'order_code'},
                    {data: 'shop_name', name: 'shop'},
                    {data: 'rider_name', name: 'rider'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'customer_phone_number', name: 'customer_phone_number'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'delivery_fees', name: 'delivery_fees'},
                    {data: 'markup_delivery_fees', name: 'markup_delivery_fees'},
                    {data: 'remark', name: 'remark'},
                    {data: 'item_type', name: 'item_type'},
                    {data: 'full_address', name: 'full_address'},
                    {data: 'schedule_date', name: 'schedule_date'},
                    {data: 'type', name: 'type'},
                    {data: 'collection_method', name: 'collection_method'},
                    {data: 'last_updated_by_name', name: 'last_updated_by'},
                ],
            });
            $('#canceled-order-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/townships/"+ township_id+"/get-canceled-orders-by-township-id" ,
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'order_code', name: 'order_code'},
                    {data: 'shop_name', name: 'shop'},
                    {data: 'rider_name', name: 'rider'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'customer_phone_number', name: 'customer_phone_number'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'delivery_fees', name: 'delivery_fees'},
                    {data: 'markup_delivery_fees', name: 'markup_delivery_fees'},
                    {data: 'remark', name: 'remark'},
                    {data: 'item_type', name: 'item_type'},
                    {data: 'full_address', name: 'full_address'},
                    {data: 'schedule_date', name: 'schedule_date'},
                    {data: 'type', name: 'type'},
                    {data: 'collection_method', name: 'collection_method'},
                    {data: 'last_updated_by_name', name: 'last_updated_by'},
                ],
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