@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Payment Due Order List')
@section('content')
<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-link" id="toggleFilter">
        <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.87768 20C7.55976 20 7.29308 19.88 7.07765 19.64C6.86222 19.4 6.75487 19.1033 6.75562 18.75V11.25L0.247706 2C-0.0328074 1.58333 -0.0750713 1.14583 0.120914 0.6875C0.3169 0.229167 0.658378 0 1.14535 0H16.8541C17.3403 0 17.6818 0.229167 17.8785 0.6875C18.0753 1.14583 18.033 1.58333 17.7518 2L11.2438 11.25V18.75C11.2438 19.1042 11.1361 19.4012 10.9207 19.6412C10.7053 19.8812 10.439 20.0008 10.1218 20H7.87768Z" fill="black" />
        </svg>
    </button>
</div>
<div class="card m-3 mt-0 filter-content" style="display: none;">
    <div class="row tdFilter">
        <div class="col-md-12 col-sm-12 m-3">
            <h2>Filter</h2>
        </div>
    </div>
<div class="row">
        <div class="filter-box">
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
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 btncenter margin-btn filter-footer">
            <button class="btn btn-primary search_filter">Filter</button>

            <button class="btn btn-secondary" id="reset">Reset</button>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between mb-3">
    <div class="create-button">
        <a class="btn create-btn" id="create-transaction">Add Transaction</a>
    </div>
</div>

<input type="hidden" id="current_screen" value="all-orders-display">
<div class="tab-content">
    <div id="all-orders-display" class="portlet box green tab-pane active">
        <div class="portlet-title">
            <div class="caption">All Order Lists</div>
        </div>
        <div class="portlet-body">
            <table id="all-orders-datatable" class="table table-striped table-hover table-responsive datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th id="first_column"><input type="checkbox" name="check_all" id="checkAll"></th>
                        <th>Type</th>
                        <th>Schedule Date</th>
                        <th>Status</th>
                        <th>Shop</th>
                        <th>Paid</th>
                        <th>Total Amount</th>
                        <th>Delivery Fees</th>
                        <th>Markup Delivery Fees</th>
                        <th>Order Code</th>
                        <th>Rider</th>
                        <th>Customer Name</th>
                        <th>Customer Phone Number</th>
                        <th>City</th>
                        <th>Township</th>
                        <th>Remark</th>
                        <th>Item Type</th>
                        <th>Full Address</th>
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
    
    
    
</div>

@endsection
@section('javascript')

<script type="text/javascript">
    function formatWithNumberingSystem(number, decimal_place = 2) {
        return parseFloat(number).toFixed(decimal_place).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    $(document).ready(function() {
        $("#toggleFilter").on("click", function() {
            $(".filter-content").slideToggle(300);
            $('#shop').select2({width: '100%'});
        }); 

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

        get_ajax_dynamic_data(search = '', city = '', rider = '', shop = '', status = '', township = '');

        function get_ajax_dynamic_data(shop) {
            var visible_column = [];
            var table = $('#all-orders-datatable').DataTable({
                processing: true,
                serverSide: true,
                buttons: [{
                        extend: 'csv',
                        title: 'Orders',
                        filename: 'orders',
                        pageSize: 'LEGAL',
                        charset: 'UTF-8',
                        orientation: 'landscape',
                    },
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
                    "url": '/ajax-get-unpaid-order-list',
                    "type": "GET",
                    "data": function(r) {
                        r.search = search;
                        r.city = city;
                        r.rider = rider;
                        r.shop = shop;
                        r.status = status;
                        r.township = township;
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
                        data: 'delivery_type_name',
                        name: 'delivery_type_name'
                    },
                    {
                        data: 'schedule_date',
                        name: 'schedule_date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'shop_name',
                        name: 'shop_name'
                    },
                    {
                        data: 'payment_flag',
                        name: 'paid'
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
                        data: 'order_code',
                        name: 'order_code'
                    },
                    {
                        data: 'rider_name',
                        name: 'rider'
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
                        data: 'remark',
                        name: 'remark'
                    },
                    {
                        data: 'item_type_name',
                        name: 'item_type'
                    },
                    {
                        data: 'full_address',
                        name: 'full_address'
                    },
                    {
                        data: 'collection_method',
                        name: 'collection_method'
                    },
                    {
                        data: 'last_updated_by_name',
                        name: 'last_updated_by'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    // render with numbering system
                    {
                        "render": function(data, type, row) {
                            return formatWithNumberingSystem(row.total_amount);
                        },
                        "targets": 7
                    },
                    {
                        "render": function(data, type, row) {
                            if(row.markup_delivery_fees != null) {
                                return formatWithNumberingSystem(row.markup_delivery_fees);
                            } else {
                                return '';
                            }
                        },
                        "targets": 9
                    },
                    // calculate the acutual delivery fees
                    {
                        "render": function(data, type, row) {
                            var delivery_fees = parseFloat(row.delivery_fees);

                            if (row.extra_charges != null) {
                                delivery_fees += parseFloat(row.extra_charges);
                            }

                            if (row.discount != null) {
                                delivery_fees -= parseFloat(row.discount);
                            }

                            return formatWithNumberingSystem(delivery_fees);
                        },
                        "targets": 8
                    },
                    {
                        "render": function(data, type, row) {
                            if (row.payment_flag == 0) {
                                return "Unpaid";
                            }
                            if (row.payment_flag == 1) {
                                return "Paid";
                            }
                        },
                        "targets": 6
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
                            if (row.status == 'cancel_request') {
                                return "Cancel Request";
                            }
                            if (row.status == 'cancel') {
                                return "Cancel";
                            }
                            if (row.status == 'in-warehouse') {
                                return "In Warehouse";
                            }
                        },
                        "targets": 4
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
                        "targets": 3
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
                        "targets": 19
                    },
                      // link with shop
                      {
                        "render": function(data, type, row) {
                            return '<a href="/shops/' + row.shop_id + '">'
                                + row.shop_name + '</a>';
                            },
                        "targets": 5
                    },
                    // link with rider
                    {
                        "render": function(data, type, row) {
                            if(row.rider_id != null) {
                                return '<a href="/riders/' + row.rider_id + '">'
                                + row.rider_name + '</a>';
                            } else {
                                return '';
                            }
                        },
                        "targets": 11
                    },
                     // link with city
                     {
                        "render": function(data, type, row) {
                            return '<a href="/cities/' + row.city_id + '">'
                                + row.city_name + '</a>';
                            },
                        "targets": 14
                    },
                    // link with township
                    {
                        "render": function(data, type, row) {
                            return '<a href="/townships/' + row.township_id + '">'
                                + row.township_name + '</a>';
                            },
                        "targets": 15
                    },
                ]
            });

            $('.search_filter').click(function() {
                var shop = $('#shop').val();
                table.destroy();
                get_ajax_dynamic_data(shop);
            });
            $("#reset").click(function() {
                $("#shop").val("").trigger("change");
                var shop = $('#shop').val();
                table.destroy();
                get_ajax_dynamic_data(shop);
            });
        };


    });
</script>
@endsection