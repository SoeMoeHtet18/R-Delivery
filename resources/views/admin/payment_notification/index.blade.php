@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Order Listing')
@section('content')
<style>
    .pdf-ul {
        padding-left: 0;
        padding-right: 0;
        border-radius: 3px;
        display: flex;
        list-style-type: none;
        padding-left: 0;
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
</style>

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
                        <th>Paid</th>
                        <th>Total Amount</th>
                        <th>Delivery Fees</th>
                        <th>Markup Delivery Fees</th>
                        <th>Order Code</th>
                        <th>Shop</th>
                        <th>Rider</th>
                        <th>Customer Name</th>
                        <th>Customer Phone Number</th>
                        <th>City</th>
                        <th>Township</th>
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
    
    
    
</div>

@endsection
@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {  

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

        function get_ajax_dynamic_data(search, city, rider, shop, status, township) {
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
                        data: 'shop_name',
                        name: 'shop'
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
                        data: 'status',
                        name: 'status'
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
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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
                        "targets": 1
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

            $('.search_filter').click(function() {
                var status = $('#status').val();
                var township = $('#township').val();
                var search = $('#search').val();
                var rider = $('#rider').val();
                var city = $('#city').val();
                var shop = $('#shop').val();
                table.destroy();
                get_ajax_dynamic_data(search, city, rider, shop, status, township);
            });
            $("#reset").click(function() {
                $("#status").val("").trigger("change");
                $("#township").val("").trigger("change");
                $("#search").val("").trigger("change");
                $("#rider").val("").trigger("change");
                $("#city").val("").trigger("change");
                $("#shop").val("").trigger("change");
                var status = $("#status").val();
                var township = $('#township').val();
                var search = $('#search').val();
                var rider = $('#rider').val();
                var city = $('#city').val();
                var shop = $('#shop').val();
                table.destroy();
                get_ajax_dynamic_data(search, city, rider, shop, status, township);
            });
        };


    });
</script>
@endsection