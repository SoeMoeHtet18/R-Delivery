@extends('admin.layouts.master')
@section('title','Collections')
@section('sub-title','Customer Exchange Listing')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <div class="create-button">
        <a class="btn create-btn" href="{{route('customer-collections.create')}}">Add Customer Exchange</a>
    </div>
    <button class="btn btn-link" id="toggleFilter"><svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                <label for="shop">
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
            <div class="mb-3 p-3 col-4">
                <label for="rider">
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
                        <th>Customer Phone Number</th>
                        <th>Shop</th>
                        <th>Rider</th>
                        <th>City</th>
                        <th>Township</th>
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
                        <th>Customer Phone Number</th>
                        <th>Shop</th>
                        <th>Rider</th>
                        <th>City</th>
                        <th>Township</th>
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
    function formatWithNumberingSystem(number, decimal_place = 2) {
        return parseFloat(number).toFixed(decimal_place).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    $(document).ready(function() {
        $("#toggleFilter").on("click", function() {
            $(".filter-content").slideToggle(300);
            $('#shop').select2();
            $('#status').select2();
            $('#rider').select2();
        });
       

        $('.nav-tabs a').click(function() {
            console.log('work')
            $(this).tab('show');
        });

        get_ajax_dynamic_data(search = '', shop = '', status = '', rider = '');

        function get_ajax_dynamic_data(search, shop, status, rider) {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-customer-collections-data',
                    "type": "GET",
                    "data": function(r) {
                        r.rider = rider
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
                        data: 'collection_group_code',
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
                        data: 'customer_phone_number',
                        name: 'customer_phone_number'
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
                        data: 'city_name',
                        name: 'city'
                    },{
                        data: 'township_name',
                        name: 'township'
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
                columnDefs: [
                    // link with self
                    {
                        "render": function(data, type, row) {
                            return '<a href="/customer-collections/' + row.id + '">'
                                + row.customer_collection_code + '</a>';
                            },
                        "targets": 1
                    },
                    // link with collection group
                    {
                        "render": function(data, type, row) {
                            if(row.collection_group_id != null) {
                                return '<a href="/collection-groups/' + row.collection_group_id + '">'
                                    + row.collection_group_code + '</a>';
                                } else {
                                    return '';
                                }
                            },
                        "targets": 2
                    },
                    // link with order
                    {
                        "render": function(data, type, row) {
                            if(row.order_id != null) {
                                return '<a href="/orders/' + row.order_id + '">'
                                    + row.order_code + '</a>';
                                } else {
                                    return '';
                                }
                            },
                        "targets": 3
                    },
                    // link with shop
                    {
                        "render": function(data, type, row) {
                            return '<a href="/shops/' + row.shop_id + '">'
                                + row.shop_name + '</a>';
                            },
                        "targets": 6
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
                        "targets": 7
                    },
                    {
                        "render": function(data, type, row) {
                            if(row.city_id != null) {
                                return '<a href="/cities/' + row.city_id + '">'
                                + row.city_name + '</a>';
                            } else {
                                return '';
                            }
                        },
                        "targets": 8
                    },
                    {
                        "render": function(data, type, row) {
                            if(row.township_id != null) {
                                return '<a href="/townships/' + row.township_id + '">'
                                + row.township_name + '</a>';
                            } else {
                                return '';
                            }
                        },
                        "targets": 9
                    },
                    // render with numbering system
                    {
                        "render": function(data, type, row) {
                            if(row.paid_amount != null) {
                                return formatWithNumberingSystem(row.paid_amount);
                            } else {
                                return '';
                            }
                        },
                        "targets": 11
                    },
                    {
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
                        "targets": 12
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
                        "targets": 13
                    },

                ]
            });

            $('.search_filter').click(function() {
                var status = $('#status').val();
                var shop = $('#shop').val();
                var search = $('#search').val();
                var rider = $('#rider').val();
                table.destroy();
                get_ajax_dynamic_data(search, shop, status, rider);
            })
            $("#reset").click(function() {
                $("#status").val("").trigger("change");
                $("#shop").val("").trigger("change");
                $("#search").val("").trigger("change");
                $("#rider").val("").trigger("change");
                var status = $('#status').val();
                var shop = $('#shop').val();
                var search = $('#search').val();
                var rider = $('#rider').val();
                table.destroy();
                get_ajax_dynamic_data(search, shop, status, rider);
            });
        };

        get_ajax_dynamic_data_for_warehouse(search = '', shop = '',  rider = '');

        function get_ajax_dynamic_data_for_warehouse(search, shop,  rider) {
            var table = $('#warehouse-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-warehouse-customer-collections-data',
                    "type": "GET",
                    "data": function(r) {
                        r.search = search;
                        r.rider = rider
                        r.shop = shop;
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
                        data: 'collection_group_code',
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
                        data: 'customer_phone_number',
                        name: 'customer_phone_number'
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
                        data: 'city_name',
                        name: 'city'
                    },{
                        data: 'township_name',
                        name: 'township'
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
                columnDefs: [
                    // link with self
                    {
                        "render": function(data, type, row) {
                            return '<a href="/customer-collections/' + row.id + '">'
                                + row.customer_collection_code + '</a>';
                            },
                        "targets": 1
                    },
                    // link with collection group
                    {
                        "render": function(data, type, row) {
                            if(row.collection_group_id != null) {
                                return '<a href="/collection-groups/' + row.collection_group_id + '">'
                                    + row.collection_group_code + '</a>';
                                } else {
                                    return '';
                                }
                            },
                        "targets": 2
                    },
                    // link with order
                    {
                        "render": function(data, type, row) {
                            if(row.order_id != null) {
                                return '<a href="/orders/' + row.order_id + '">'
                                    + row.order_code + '</a>';
                                } else {
                                    return '';
                                }
                            },
                        "targets": 3
                    },
                    // link with shop
                    {
                        "render": function(data, type, row) {
                            return '<a href="/shops/' + row.shop_id + '">'
                                + row.shop_name + '</a>';
                            },
                        "targets": 6
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
                        "targets": 7
                    },
                    // link with city
                    {
                        "render": function(data, type, row) {
                            if(row.city_id != null) {
                                return '<a href="/cities/' + row.city_id + '">'
                                + row.city_name + '</a>';
                            } else {
                                return '';
                            }
                        },
                        "targets": 8
                    },
                    //link with township
                    {
                        "render": function(data, type, row) {
                            if(row.township_id != null) {
                                return '<a href="/townships/' + row.township_id + '">'
                                + row.township_name + '</a>';
                            } else {
                                return '';
                            }
                        },
                        "targets": 9
                    },
                    {
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
                    "targets": 10
                }, ]
            });

            $('.search_filter').click(function() {
                var shop = $('#shop').val();
                var search = $('#search').val();
                var rider = $('#rider').val();
                table.destroy();
                get_ajax_dynamic_data_for_warehouse(search, shop, rider);
            })
            $("#reset").click(function() {
                $("#shop").val("").trigger("change");
                $("#search").val("").trigger("change");
                var shop = $('#shop').val();
                var search = $('#search').val();
                var rider = $('#rider').val();
                table.destroy();
                get_ajax_dynamic_data_for_warehouse(search, shop, rider);
            });
        };
    });
</script>
@endsection