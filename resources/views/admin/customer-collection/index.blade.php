@extends('admin.layouts.master')
@section('title','Collections')
@section('sub-title','Customer Exchange Listing')
@section('content')
<div class="create-button">
    <a class="btn create-btn" href="{{route('customer-collections.create')}}">Add Customer Exchange</a>
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
                <label for="search">
                    <strong>Search</strong>
                </label>
                <div class="col-10">
                    <input type="text" id="search" name="search" class="form-control" />
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="filter-box">
            <div class="mb-3 p-3 col-4">
                <label for="status">
                    <strong>Status</strong>
                </label>
                <div class="col-10">
                    <select name="status" id="status" class="form-control">
                        <option value="" selected disabled>Select</option>
                        <option value="pending">Pending</option>
                        <option value="in-warehouse">In Warehouse</option>
                        <option value="complete">Completed</option>
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

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a href="#all-display" id="all-tab" class="nav-link active" data-toggle="tab">All Customer Exchanges</a>
    </li>
    <li class="nav-item">
        <a href="#warehouse-display" id="warehouse-tab" class="nav-link" data-toggle="tab">Warehouse Customer Exchanges</a>
    </li>
</ul>

<input type="hidden" id="current_screen" value="all-display">
<div class="tab-content">
    <div id="all-display" class="portlet box green tab-pane active">
        <div class="portlet-title">
            <div class="caption">Customer Exchange Lists</div>
        </div>
        <div class="portlet-body">
            <table id="datatable" class="table table-striped table-hover table-responsive datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer Exchange Code</th>
                        <th>Pick Up Group</th>
                        <th>Order Code</th>
                        <th>Customer Name</th>
                        <th>Shop</th>
                        <th>Items</th>
                        <th>Paid Amount To Customer</th>
                        <th>Is Way Fees Payable</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div id="warehouse-display" class="portlet box green tab-pane">
        <div class="portlet-title">
            <div class="caption">Warehouse Customer Exchange Lists</div>
        </div>
        <div class="portlet-body">
            <table id="warehouse-datatable" class="table table-striped table-hover table-responsive datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer Exchange Code</th>
                        <th>Pick Up Group</th>
                        <th>Order Code</th>
                        <th>Customer Name</th>
                        <th>Shop</th>
                        <th>Items</th>
                        <th>Paid Amount To Customer</th>
                        <th>Is Way Fees Payable</th>
                        <th>Note</th>
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
        $('#shop').select2();
        $('#status').select2();

        $('.nav-tabs a').click(function() {
            console.log('work')
            $(this).tab('show');
        });

        get_ajax_dynamic_data(search = '', shop = '', status = '');

        function get_ajax_dynamic_data(search, shop, status) {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-customer-collections-data',
                    "type": "GET",
                    "data": function(r) {
                        r.search = search;
                        r.shop = shop;
                        r.status = status;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'customer_collection_code',
                        name: 'customer_collection_code'
                    },
                    {
                        data: 'collection_group',
                        name: 'collection_group'
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
                        data: 'shop_name',
                        name: 'shop'
                    },
                    {
                        data: 'items',
                        name: 'items'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount_to_customer'
                    },
                    {
                        data: 'is_way_fees_payable',
                        name: 'is_way_fees_payable'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [ {
                        "render": function(data, type, row) {
                            if (row.is_way_fees_payable == 0) {
                                return "No";
                            }
                            if (row.is_way_fees_payable == 1) {
                                return "Yes";
                            }
                            if (row.is_way_fees_payable == null) {
                                return "Pending";
                            }
                        },
                        "targets": 8
                    },
                    {
                        "render": function(data, type, row) {
                            if (row.status == 'pending') {
                                return "Pending";
                            }
                            if (row.status == 'in-warehouse') {
                                return "In Warehouse";
                            }
                            if (row.status == 'complete') {
                                return "Completed";
                            }
                        },
                        "targets": 9
                    },

                ]
            });

            $('.search_filter').click(function() {
                var status = $('#status').val();
                var shop = $('#shop').val();
                var search = $('#search').val();
                table.destroy();
                get_ajax_dynamic_data(search, shop, status);
            })
            $("#reset").click(function() {
                $("#status").val("").trigger("change");
                $("#shop").val("").trigger("change");
                $("#search").val("").trigger("change");
                var status = $('#status').val();
                var shop = $('#shop').val();
                var search = $('#search').val();
                table.destroy();
                get_ajax_dynamic_data(search, shop, status);
            });
        };

        get_ajax_dynamic_data_for_warehouse(search = '', shop = '', status = '');

        function get_ajax_dynamic_data_for_warehouse(search, shop, status) {
            var table = $('#warehouse-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-warehouse-customer-collections-data',
                    "type": "GET",
                    "data": function(r) {
                        r.search = search;
                        r.shop = shop;
                        r.status = status;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'customer_collection_code',
                        name: 'customer_collection_code'
                    },
                    {
                        data: 'collection_group',
                        name: 'collection_group'
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
                        data: 'shop_name',
                        name: 'shop'
                    },
                    {
                        data: 'items',
                        name: 'items'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount_to_customer'
                    },
                    {
                        data: 'is_way_fees_payable',
                        name: 'is_way_fees_payable'
                    },
                    {
                        data: 'note',
                        name: 'note'
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
                        if (row.is_way_fees_payable == 0) {
                            return "No";
                        }
                        if (row.is_way_fees_payable == 1) {
                            return "Yes";
                        }
                        if (row.is_way_fees_payable == null) {
                            return "Pending";
                        }
                    },
                    "targets": 8
                }, ]
            });

            $('.search_filter').click(function() {
                var shop = $('#shop').val();
                var search = $('#search').val();
                table.destroy();
                get_ajax_dynamic_data_for_warehouse(search, shop);
            })
            $("#reset").click(function() {
                $("#shop").val("").trigger("change");
                $("#search").val("").trigger("change");
                var shop = $('#shop').val();
                var search = $('#search').val();
                table.destroy();
                get_ajax_dynamic_data_for_warehouse(search, shop);
            });
        };
    });
</script>
@endsection