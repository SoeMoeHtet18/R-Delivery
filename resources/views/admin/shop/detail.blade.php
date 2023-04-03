@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Shop Detail')
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
                        <strong>Shop Detail</strong>
                    </h2>
                    <div class="card-toolbar">
                        <div class="create-button">
                            <a href="{{route('shops.edit' , $shop->id)}}" class="btn btn-light">Edit</a>
                        </div>
                        <form action="{{route('shops.destroy', $shop->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this shop?`);">
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
                                {{ $shop->name }}
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-2">
                                <h4>Address <b>:</b></h4>
                            </div>
                            <div class="col-10">
                                {{ $shop->address }}
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <div class="col-2">
                            <h4>Phone Number <b>:</b></h4>
                            </div>
                            <div class="col-10">
                                {{ $shop->phone_number }}
                            </div>
                        </div>
                    </div>
                    
                <hr>
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a href="#shop-user-display" id="shop-user-tab" class="nav-link active" data-toggle="tab">Shop Users</a>
                    </li>
                    <li class="nav-item">
                        <a href="#shop-order-display" id="shop-order-tab" class="nav-link" data-toggle="tab">Shop Orders</a>
                    </li>
                    <li>
                        <a href="#shop-payment-display" id="shop-payment-tab" class="nav-link" data-toggle="tab">Shop Payments</a>
                    </li>
                    <li>
                        <a href="#payment-for-shop-display" id="payment-for-shop-tab" class="nav-link" data-toggle="tab">Transactions For Shop</a>
                    </li>
                </ul>
                <input type="hidden" id="current_screen" value="shop-user-display">
                <div class="tab-content">
                    <div id="shop-user-display" class="portlet box green tab-pane active">
                        <div class="portlet-title">
                            <div class="caption">ShopUser Lists</div>
                        </div>
                        <div class="portlet-body">
                            <table id="shop-user-datatable" class="table table-striped table-hover table-responsive datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone Number</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="shop-order-display" class="portlet box green tab-pane">
                        <div class="portlet-title">
                            <div class="caption">ShopOrder Lists</div>
                        </div>
                        <div class="portlet-body">
                            <table id="shop-order-datatable" class="table table-striped table-hover table-responsive datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order Code</th>
                                        <th>Customer Name</th>
                                        <th>Customer Phone Number</th>
                                        <th>Township</th>
                                        <th>Rider</th>
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
                                    </tr>
                                </thead>

                                <tbody>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="shop-payment-display" class="portlet box green tab-pane">                 
                        <div class="portlet-title">
                            <div class="caption">Shop Payment Lists</div>
                        </div>
                        <div class="portlet-body">
                            <table id="shop-payment-datatable" class="table table-striped table-hover table-responsive datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="payment-for-shop-display" class="portlet box green tab-pane">
                        <div class="portlet-title">
                            <div class="caption">Transactions For Shop Lists</div>
                        </div>
                        <div class="portlet-body">
                            <table id="transaction-for-shop-datatable" class="table table-striped table-hover table-responsive datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Paid By</th>
                                        <th>Action</th>
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
            var shop_id = {!!json_encode($shop['id'])!!};
            $('#shop-user-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/shops/get-shop-users-by-shop-id/' + shop_id,
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'phone_number', name: 'phone_number'},
                    {data: 'email', name: 'email'},
                ], 
            });
            $('#shop-order-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/shops/get-shop-orders-by-shop-id/" + shop_id,
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'order_code', name: 'order_code'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'customer_phone_number', name: 'customer_phone_number'},
                    {data: 'township_name', name: 'township'},
                    {data: 'rider_name', name: 'rider'},
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
                ]
            });
            $("#shop-payment-datatable").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/shops/" + shop_id +"/get-shop-payment-by-shop-id",
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'amount', name: 'amount'},
                    {data: 'type', name: 'type'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
            $("#transaction-for-shop-datatable").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/shops/" + shop_id +"/get-transactions-for-shop-by-shop-id",
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'amount', name: 'amount'},
                    {data: 'type', name: 'type'},
                    {data: 'paid_by', name: 'paid_by'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
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