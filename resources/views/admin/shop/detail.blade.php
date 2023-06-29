@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Shop Detail')
@section('content')
<style>
    .card-toolbar {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
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
        <div id="shop-id" data-shop-id="{{ $shop->id }}"></div>
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
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Payable Amuount <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $shop->payable_amount }}
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
                    <div class="create-button pb-5 d-inline-block">
                        <a class="btn create-btn" href="{{ url('/order-create-by-shop-id') }}?shop_id={{ $shop->id }}">Add Order</a>
                    </div>
                    <div class="create-button mb-3 d-inline-block">
                        <a class="btn create-btn" id="create-transaction">Add Transaction</a>
                    </div>

                    <table id="shop-order-datatable" class="table table-striped table-hover table-responsive datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th id="first_column"><input type="checkbox" name="check_all" id="checkAll"></th>
                                <th>Paid</th>
                                <th>Order Code</th>
                                <th>Customer Name</th>
                                <th>Customer Phone Number</th>
                                <th>City</th>
                                <th>Township</th>
                                <th>Rider</th>
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
                    <div class="create-button pb-5">
                        <a href="{{url('/shoppayment-create-by-shop-id')}}?shop_id={{$shop->id}}" class="btn create-btn">Add Shop Payment</a>
                    </div>

                    <table id="shop-payment-datatable" class="table table-striped table-hover table-responsive datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Description</th>
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
                    <div class="create-button pb-5">
                        <a href="{{url('/transactions-for-shop-create-by-shop-id')}}?shop_id={{$shop->id}}" class="btn create-btn">Add New Transaction</a>
                    </div>
                    <table id="transaction-for-shop-datatable" class="table table-striped table-hover table-responsive datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Paid By</th>
                                <th>Description</th>
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
    $(function() {
        var shop_id = document.getElementById('shop-id').getAttribute('data-shop-id');

        $("#create-transaction").click(function() {
            processPayment();
        });

        function processPayment() {
            var process_data = [];
            var shop_ids = [];
            var order_ids = [];

            $('.order-payment:checked').each(function() {
                var push_data = {
                    id: $(this).data('id'),
                    shop_id: $(this).data('shop_id'),
                    payment_flag: $(this).data('payment_flag')
                };
                process_data.push(push_data);
                shop_ids.push(push_data.shop_id);
                order_ids.push(push_data.id);
            });

            shop_ids = [...new Set(shop_ids)];
            order_ids = [...new Set(order_ids)];

            if (process_data.length === 0) {
                showErrorToast("Please select at least one order.");
            } else if (shop_ids.length > 1) {
                showErrorToast("Please select only orders of the same shop.");
            } else if (process_data.some(order => order.payment_flag === 1)) {
                showErrorToast("Please select only orders that are unpaid.");
            } else {
                var redirectUrl = '/create-transaction-for-shop-for-selected-orders?order_ids=' + order_ids + '&shop_id=' + shop_ids;
                window.location = redirectUrl;
            }
        }

        $('#checkAll').click(function() {
            var isChecked = $(this).prop("checked");
            $('td input').prop('checked', isChecked);
        });

        $('.order-payment').click(function() {
            var allChecked = $('.order-payment:checked').length === $('.order-payment').length;
            $('#checkAll').prop('checked', allChecked);
        });

        function showErrorToast(message) {
            Toastify({
                text: message,
                gravity: "top",
                position: "center",
                style: {
                    background: "red",
                },
                duration: 3000,
            }).showToast();
        }

        // Initialize shop-user-datatable
        $('#shop-user-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/shops/get-shop-users-by-shop-id/' + shop_id,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'phone_number',
                    name: 'phone_number'
                },
                {
                    data: 'email',
                    name: 'email'
                },
            ],
        });

        // Initialize shop-order-datatable
        $('#shop-order-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "/shops/get-shop-orders-by-shop-id/" + shop_id,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'first_column',
                    name: 'first_column',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'payment_flag',
                    name: 'paid'
                },
                {
                    data: 'order_code',
                    name: 'order_code'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
                },
                {
                    data: 'customer_phone_number',
                    name: 'customer_phone_number'
                },
                {
                    data: 'city_name',
                    name: 'city'
                },
                {
                    data: 'township_name',
                    name: 'township'
                },
                {
                    data: 'rider_name',
                    name: 'rider'
                },
                {
                    data: 'total_amount',
                    name: 'total_amount'
                },
                {
                    data: 'delivery_fees',
                    name: 'delivery_fees'
                },
                {
                    data: 'markup_delivery_fees',
                    name: 'markup_delivery_fees'
                },
                {
                    data: 'remark',
                    name: 'remark'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'item_type',
                    name: 'item_type'
                },
                {
                    data: 'full_address',
                    name: 'full_address'
                },
                {
                    data: 'schedule_date',
                    name: 'schedule_date'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'collection_method',
                    name: 'collection_method'
                },
                {
                    data: 'last_updated_by_name',
                    name: 'last_updated_by'
                },
            ],
            columnDefs: [{
                    "render": function(data, type, row) {
                        if (row.payment_flag == 0) {
                            return "Unpaid";
                        }
                        if (row.payment_flag == 1) {
                            return "Paid";
                        }
                    },
                    "targets": 2
                },
                {
                    "render": function(data, type, row) {
                        if (row.status == 'pending') {
                            return "Pending";
                        }
                        if (row.status == 'success') {
                            return "Success";
                        }
                        if (row.status == 'delay') {
                            return "Delay";
                        }
                        if (row.status == 'cancel') {
                            return "Cancel";
                        }
                        if (row.status == 'cancel_request') {
                            return 'Cancel Request';
                        }
                    },
                    "targets": 13
                },
                {
                    "render": function(data, type, row) {
                        if (row.schedule_date === null) {
                            return '';
                        }
                        var date = new Date(row.schedule_date);
                        var formattedDate = date.toLocaleDateString('my-MM', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        return formattedDate;
                    },
                    "targets": 16
                },
                {
                    "render": function(data, type, row) {
                        if (row.type == 'standard') {
                            return "Standard";
                        }
                        if (row.type == 'express') {
                            return "Express";
                        }
                        if (row.type == 'doortodoor') {
                            return "Door To Door";
                        }
                    },
                    "targets": 17
                },
                {
                    "render": function(data, type, row) {
                        if (row.collection_method == 'dropoff') {
                            return "Drop Off";
                        }
                        if (row.collection_method == 'pickup') {
                            return "Pick Up";
                        }
                    },
                    "targets": 18
                },
            ]
        });

        // Initialize shop-payment-datatable
        $("#shop-payment-datatable").DataTable({
            processing: true,
            serverSide: true,
            ajax: "/shops/" + shop_id + "/get-shop-payment-by-shop-id",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            columnDefs: [{
                "render": function(data, type, row) {
                    if (row.type == 'delivery_payment') {
                        return "Delivery Payment";
                    }
                    if (row.type == 'remaining_payment') {
                        return "Remaining Payment";
                    }
                },
                "targets": 2
            }, ]
        });
        // Initialize transaction-for-shop-datatable
        $("#transaction-for-shop-datatable").DataTable({
            processing: true,
            serverSide: true,
            ajax: "/shops/" + shop_id + "/get-transactions-for-shop-by-shop-id",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'paid_by',
                    name: 'paid_by'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            columnDefs: [{
                "render": function(data, type, row) {
                    if (row.type == 'loan_payment') {
                        return "Loan Payment";
                    }
                    if (row.type == 'fully_payment') {
                        return "Fully Payment";
                    }
                },
                "targets": 2
            }, ]
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.nav-tabs a').click(function() {
            $(this).tab('show');
        });
    });
</script>
@endsection