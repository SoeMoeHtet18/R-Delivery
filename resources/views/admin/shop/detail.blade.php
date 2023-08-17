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

    .pdf-ul {
        padding-left: 0;
        padding-right: 0;
        border-radius: 3px;
        display: flex;
        list-style-type: none;
        margin-left: auto;
        margin-bottom: 0;
    }

    .pdf-ul li {
        background: #f4f5f8;
        margin: 0;
    }

    .pdf-ul li a {
        padding: 0;
        padding-right: 6px;
        padding-left: 2px;
        font-size: 13px;
        text-decoration: none;
    }

    .pdf-ul li a:first-child {
        border-right: 1px solid #dfe2ea;
    }

    /* Adjust border width and color for input elements */
    input[type="date"] {
        border: 1px solid #75827f;
    }

    :focus {
        outline: none; /* Remove default focus outline */
    }

    /* Style focus-visible for improved accessibility */
    :focus-visible {
        outline: 1px solid grey; /* Customize focus outline */
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
                    <h4>Payable Amount <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $shop->payable_amount }} MMK
                </div>
            </div>
        </div>

        <hr>
        <button class="btn btn-link" id="toggleFilter">
            <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.87768 20C7.55976 20 7.29308 19.88 7.07765 19.64C6.86222 19.4 6.75487 19.1033 6.75562 18.75V11.25L0.247706 2C-0.0328074 1.58333 -0.0750713 1.14583 0.120914 0.6875C0.3169 0.229167 0.658378 0 1.14535 0H16.8541C17.3403 0 17.6818 0.229167 17.8785 0.6875C18.0753 1.14583 18.033 1.58333 17.7518 2L11.2438 11.25V18.75C11.2438 19.1042 11.1361 19.4012 10.9207 19.6412C10.7053 19.8812 10.439 20.0008 10.1218 20H7.87768Z" fill="black" />
            </svg>
        </button>
        <div class="card m-3 mt-0 filter-content" style="display: none;">
            <div class="row tdFilter">
                <div class="col-md-12 col-sm-12 m-3">
                    <h2>Filter</h2>
                </div>
            </div>
            <div class="row">
                <div class="filter-box">
                    <div class="mb-3 p-3 col-4">
                        <label for="payment_channel">
                            <strong>Payment Channel</strong>
                        </label>
                        <div class="col-10">
                            <select name="payment_channel" id="payment_channel" class="form-control">
                                <option value="" selected disabled>Select</option>
                                <option value="cash">Cash</option>
                                <option value="company_online_payment">Online Payment (Company)</option>
                                <option value="shop_online_payment">Online Payment (Shop)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 p-3 col-4">
                        <label for="search">
                            <strong>Created Between</strong>
                        </label>
                        <div class="col-10">
                            <div style="position: relative;">
                                <input type="text" name="datefilter" value="" class="form-control"/>
                                <span class="fa fa-calendar" style="position: absolute; top: 12px; right: 8px;"></span>
                            </div>
                            <input type="hidden" name="start_date" id="start_date">
                            <input type="hidden" name="end_date" id="end_date">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-row-reverse pb-3">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 btncenter margin-btn">
                    <button id='filter' class="btn green ms-2 me-0">Filter</button>
                    <button id="clear" class="btn btn-secondary mx-0">Clear</button>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs my-4">
            <li class="nav-item">
                <a href="#shop-user-display" id="shop-user-tab" class="nav-link active" data-toggle="tab">Shop Users</a>
            </li>
            <li class="nav-item">
                <a href="#shop-order-display" id="shop-order-tab" class="nav-link" data-toggle="tab">Shop Orders</a>
            </li>
            <li>
                <a href="#shop-payment-display" id="shop-payment-tab" class="nav-link"
                    data-toggle="tab">Shop Payments</a>
            </li>
            <li>
                <a href="#payment-for-shop-display" id="payment-for-shop-tab" class="nav-link"
                    data-toggle="tab">Transactions For Shop</a>
            </li>
            <li>
                <a href="#collection-for-shop-display" id="collection-for-shop-tab" class="nav-link"
                    data-toggle="tab">Pick Ups For Shop</a>
            </li>
        </ul>
        <input type="hidden" id="current_screen" value="shop-user-display">
        <div class="d-flex justify-content-end">
            <div class="d-inline-block">
                <ul class="pdf-ul">
                    <li>
                        <form id="pdf_form" action="{{ url('/generate-pdf') }}" method="GET" style="display: inline;">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <button type="submit" id="pdf_button" class="btn border">
                                <i class="fa-regular fa-file-pdf"></i>&nbsp;<span>PDF</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content mt-4">
            <div id="shop-user-display" class="portlet box green tab-pane active">
                <div class="portlet-title">
                    <div class="caption">Shop User Lists</div>
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
                    <div class="caption">Shop Order Lists</div>
                </div>
                <div class="portlet-body">
                    <div class="create-button pb-5 d-inline-block">
                        <a class="btn create-btn"
                            href="{{ url('/order-create-by-shop-id') }}?shop_id={{ $shop->id }}">Add Order</a>
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
                                <th>Customer Phone Number</th>
                                <th>Customer Address</th>
                                <th>Customer Name</th>
                                <th>City</th>
                                <th>Township</th>
                                <th>Total Amount</th>
                                <th>Delivery Fees</th>
                                <th>Markup Delivery Fees</th>
                                <th>Item Type</th>
                                <th>Payment Type</th>
                                <th>Payment Channel</th>
                                <th>Collection Method</th>
                                <th>Schedule Date</th>
                                <th>Type</th>
                                <th>Rider</th>
                                <th>Remark</th>
                                <th>Status</th>
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
                        <a href="{{url('/transactions-for-shop-create-by-shop-id')}}?shop_id={{$shop->id}}"
                            class="btn create-btn">Add New Transaction</a>
                    </div>
                    <table id="transaction-for-shop-datatable"
                        class="table table-striped table-hover table-responsive datatable">
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
            <div id="collection-for-shop-display" class="portlet box green tab-pane">
                <div class="portlet-title">
                    <div class="caption">Pick Ups For Shop Lists</div>
                </div>
                <div class="portlet-body">
                    <div class="create-button pb-5">
                        <a href="{{url('/transactions-for-shop-create-by-shop-id')}}?shop_id={{$shop->id}}" class="btn create-btn">Add New Pick Up</a>
                    </div>
                    <table id="collection-for-shop-datatable" class="table table-striped table-hover table-responsive datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pick Up Code</th>
                                <th>Total Quantity</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Collection Group</th>
                                <th>Rider</th>
                                <th>Collected At</th>
                                <th>Note</th>
                                <th>Status</th>
                                <th>Is payable</th>
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
        $("#toggleFilter").on("click", function() {
            $(".filter-content").slideToggle(300);
            $('#payment_channel').select2();
        });

        var tabIndex = 0;
        $('.nav-tabs a').click(function() {
            $(this).tab('show');
            tabIndex = $('.nav-tabs a').index(this);
        });

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

        get_ajax_dynamic_shop_order_table(start = '', end = '', paymentChannel = '');

        // Initialize shop-order-datatable
        function get_ajax_dynamic_shop_order_table(start, end, paymentChannel) {
            var order_table = $('#shop-order-datatable').DataTable({
                processing: true,
                serverSide: true,
                buttons: [
                    {
                        extend: 'pdf',
                        title: 'Orders',
                        filename: 'orders',
                        pageSize: 'LEGAL',
                        charset: 'UTF-8',
                        orientation: 'landscape',
                    },
                ],
                ajax: {
                    "url": "/shops/get-shop-orders-by-shop-id/" + shop_id,
                    "type": "GET",
                    "data": function(r) {
                        r.from_date = start;
                        r.to_date = end;
                        r.payment_channel = paymentChannel
                    }
                },
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
                        data: 'customer_phone_number',
                        name: 'customer_phone_number'
                    },
                    {
                        data: 'full_address',
                        name: 'full_address'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
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
                        data: 'item_type_name',
                        name: 'item_type'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'payment_channel',
                        name: 'payment_channel'
                    },
                    {
                        data: 'collection_method',
                        name: 'collection_method'
                    },
                    {
                        data: 'schedule_date',
                        name: 'schedule_date'
                    },
                    {
                        data: 'delivery_type_name',
                        name: 'type'
                    },
                    {
                        data: 'rider_name',
                        name: 'rider'
                    },
                    {
                        data: 'remark',
                        name: 'remark'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                                return "Delivered";
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
                            if (row.status == 'warehouse') {
                                return "In Warehouse";
                            }
                            if (row.status == 'picking-up') {
                                return "Picking Up";
                            }
                            if (row.status == 'delivering') {
                                return "Delivering";
                            }
                        },
                        "targets": 20
                    },
                    {
                        "render": function(data, type, row) {
                            if (row.payment_method == 'cash_on_delivery') {
                                return "Cash On Delivery";
                            }
                            if (row.payment_method == 'all_prepaid') {
                                return "All Prepaid";
                            }
                            if (row.payment_method == 'item_prepaid') {
                                return "Item Prepaid";
                            }
                        },
                        "targets": 13
                    },
                    {
                        "render": function(data, type, row) {
                            if (row.payment_channel == 'cash') {
                                return "Cash";
                            } else if (row.payment_channel == 'company_online_payment') {
                                return "Online Payment (Company)";
                            } else if (row.payment_channel == 'shop_online_payment') {
                                return "Online Payment (Shop)";
                            } else {
                                return '';
                            }
                        },
                        "targets": 14
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
                        "targets": 15
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
                  
                ]
            });

            $('#filter').click(function() {
                var start = $('#start_date').val();
                var end = $('#end_date').val();
                var paymentChannel = $('#payment_channel').val();
                order_table.destroy();
                get_ajax_dynamic_shop_order_table(start, end, paymentChannel);
            });
            $("#clear").click(function() {
                $("#start_date").val("").trigger("change");
                $("#end_date").val("").trigger("change");
                $("#payment_channel").val("").trigger("change");
                var start = $("#start_date").val();
                var end = $('#end_date').val();
                var paymentChannel = $('#payment_channel').val();
                order_table.destroy();
                get_ajax_dynamic_shop_order_table(start, end, paymentChannel);
            });
        }
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
        get_ajax_dynamic_shop_transaction_table(start = '', end = '');
        function get_ajax_dynamic_shop_transaction_table(start, end) {
            var transaction_table = $("#transaction-for-shop-datatable").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url' : "/shops/" + shop_id + "/get-transactions-for-shop-by-shop-id",
                    'method' : 'GET',
                    'data': function(r) {
                        r.from_date = start;
                        r.to_date = end;
                    },
                },
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

            $('#filter').click(function() {
                var start = $('#start_date').val();
                var end = $('#end_date').val();
                transaction_table.destroy();
                get_ajax_dynamic_shop_transaction_table(start, end);
                console.log(end);
            });
            $("#clear").click(function() {
                $("#start_date").val("").trigger("change");
                $("#end_date").val("").trigger("change");
                var start = $("#start_date").val();
                var end = $('#end_date').val();
                transaction_table.destroy();
                get_ajax_dynamic_shop_transaction_table(start, end);
                console.log(start);

            });
        }
        // Initialize collection-for-shop-datatable
        get_ajax_dynamic_collection_for_shop_table(start = '', end = '');
        function get_ajax_dynamic_collection_for_shop_table(start, end) {
            var collection_table =  $("#collection-for-shop-datatable").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url' : "/shops/" + shop_id + "/get-collections-for-shop-by-shop-id",
                    'method' : 'GET',
                    'data': function(r) {
                        r.from_date = start;
                        r.to_date = end;
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'collection_code',
                        name: 'pick_up_code'
                    },
                    {
                        data: 'total_quantity',
                        name: 'total_quantity'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount',
                    },
                    {
                        data: 'collection_group_code',
                        name: 'collection_group',
                    },
                    {
                        data: 'rider_name',
                        name: 'rider',
                    },
                    {
                        data: 'collected_at',
                        name: 'collected_at',
                    },
                    {
                        data: 'note',
                        name: 'note',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'is_payable',
                        name: 'is_payable',
                    },
                ],
                columnDefs: [
                        {
                            "render": function(data, type, row) {
                                if (row.status == 'pending') {
                                    return "Pending";
                                }
                                if (row.status == 'complete') {
                                    return "Completed";
                                }
                                if (row.status == 'picking-up') {
                                    return "Picking Up";
                                }
                            },
                            "targets": 9 
                        },
                        {
                            "render": function(data, type, row) {
                                if (row.is_payable == 0) {
                                    return "No";
                                }
                                if (row.payment_flag == 1) {
                                    return "Yes";
                                }
                            },
                            "targets": 10
                        },
                    ],
            });

            $('#filter').click(function() {
                var start = $('#start_date').val();
                var end = $('#end_date').val();
                collection_table.destroy();
                get_ajax_dynamic_collection_for_shop_table(start, end);
            });
            $("#clear").click(function() {
                $("#start_date").val("").trigger("change");
                $("#end_date").val("").trigger("change");
                var start = $("#start_date").val();
                var end = $('#end_date').val();
                collection_table.destroy();
                get_ajax_dynamic_collection_for_shop_table(start, end);
            });
        }

        const form = $('#pdf_form');

        // Add event listener to the form submit event
        form.submit(function(event) {
            // Prevent the default form submission behavior
            event.preventDefault();
            var shop_id = document.getElementById('shop-id').getAttribute('data-shop-id');
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            var type = tabIndex == 1 ? 'order' : tabIndex == 4 ? 'pick_up' : '';
            console.log(type);
            
            generatePDF(start, end, shop_id, type);
        });

        function generatePDF(start, end, shop_id, type) {
            // Create the download URL with query parameters
            const downloadUrl = `/generate-shop-pdf?from_date=${encodeURIComponent(start)}&to_date=${encodeURIComponent(end)}&shop_id=${encodeURIComponent(shop_id)}&type=${encodeURIComponent(type)}`;
            // Navigate to the download URL
            window.location.href = downloadUrl;
        }

        // for date range picker
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $(".fa-calendar").on("click", function() {
            $('input[name="datefilter"]').trigger("click");
        });
    });
</script>
@endsection