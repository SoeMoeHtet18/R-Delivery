@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Township Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Township Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('townships.edit' , $township->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('townships.destroy', $township->id)}}"
                method="post" onclick="return confirm(`Are you sure you want to delete this township?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div id="township_id" data-township-id="{{$township->id}}">
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
                        <a href="/cities/{{ $township->city_id }}">
                            {{ $township->city->name }}
                        </a>
                    </div>
                </div>
                <div class="row m-0 mb-3">
                    <div class="col-2">
                        <h4>Delivery Fees <b>:</b></h4>
                    </div>
                    <div class="col-10">
                        {{ $township->delivery_fees }}
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
                        <table id="current-order-datatable"
                            class="table table-striped table-hover table-responsive datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Total Amount</th>
                                    <th>Order Code</th>
                                    <th>Shop</th>
                                    <th>Rider</th>
                                    <th>Customer Name</th>
                                    <th>Customer Phone Number</th>
                                    <th>Delivery Fees</th>
                                    <th>MarkUp Delivery Fees</th>
                                    <th>Remark</th>
                                    <th>Full Address</th>
                                    <th>Schedule Date</th>
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
                        <div class="caption">Completed Orders</div>
                    </div>
                    <div class="portlet-body">
                        <table id="completed-order-datatable"
                            class="table table-striped table-hover table-responsive datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Total Amount</th>
                                    <th>Order Code</th>
                                    <th>Shop</th>
                                    <th>Rider</th>
                                    <th>Customer Name</th>
                                    <th>Customer Phone Number</th>
                                    <th>Delivery Fees</th>
                                    <th>MarkUp Delivery Fees</th>
                                    <th>Remark</th>
                                    <th>Full Address</th>
                                    <th>Schedule Date</th>
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
                        <div class="caption">Canceled Lists</div>
                    </div>
                    <div class="portlet-body">
                        <table id="canceled-order-datatable"
                            class="table table-striped table-hover table-responsive datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Total Amount</th>
                                    <th>Order Code</th>
                                    <th>Shop</th>
                                    <th>Rider</th>
                                    <th>Customer Name</th>
                                    <th>Customer Phone Number</th>
                                    <th>Delivery Fees</th>
                                    <th>MarkUp Delivery Fees</th>
                                    <th>Remark</th>
                                    <th>Full Address</th>
                                    <th>Schedule Date</th>
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
        $(function() {
            var township_id = $("#township_id").attr('data-township-id');
            $('#current-order-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/townships/" + township_id + "/get-pending-orders-by-township-id",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
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
                        data: 'full_address',
                        name: 'full_address'
                    },
                    {
                        data: 'schedule_date',
                        name: 'schedule_date'
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
                columnDefs: [
                    // calculate actual delivery fees
                    {
                        "render": function(data, type, row) {
                            var delivery_fees = parseFloat(row.delivery_fees);

                            if (row.extra_charges != null) {
                                delivery_fees += parseFloat(row.extra_charges);
                            }

                            if (row.discount != null) {
                                delivery_fees -= parseFloat(row.discount);
                            }

                            return delivery_fees;
                        },
                        "targets": 7
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
                        "targets": 11
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
                        "targets": 12
                    },
                      // link with shop
                      {
                        "render": function(data, type, row) {
                            return '<a href="/shops/' + row.shop_id + '">'
                                + row.shop_name + '</a>';
                            },
                        "targets": 3
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
                        "targets": 4
                    },
                ]
            });
            $('#completed-order-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/townships/" + township_id + "/get-completed-orders-by-township-id",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
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
                        data: 'full_address',
                        name: 'full_address'
                    },
                    {
                        data: 'schedule_date',
                        name: 'schedule_date'
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
                columnDefs: [
                    // calculate actual delivery fees
                    {
                        "render": function(data, type, row) {
                            var delivery_fees = parseFloat(row.delivery_fees);

                            if (row.extra_charges != null) {
                                delivery_fees += parseFloat(row.extra_charges);
                            }

                            if (row.discount != null) {
                                delivery_fees -= parseFloat(row.discount);
                            }

                            return delivery_fees;
                        },
                        "targets": 7
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
                        "targets": 11
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
                        "targets": 12
                    },
                      // link with shop
                      {
                        "render": function(data, type, row) {
                            return '<a href="/shops/' + row.shop_id + '">'
                                + row.shop_name + '</a>';
                            },
                        "targets": 3
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
                        "targets": 4
                    },
                ]
            });
            $('#canceled-order-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/townships/" + township_id + "/get-canceled-orders-by-township-id",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
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
                        data: 'full_address',
                        name: 'full_address'
                    },
                    {
                        data: 'schedule_date',
                        name: 'schedule_date'
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
                columnDefs: [
                    // calculate actual delivery fees
                    {
                        "render": function(data, type, row) {
                            var delivery_fees = parseFloat(row.delivery_fees);

                            if (row.extra_charges != null) {
                                delivery_fees += parseFloat(row.extra_charges);
                            }

                            if (row.discount != null) {
                                delivery_fees -= parseFloat(row.discount);
                            }

                            return delivery_fees;
                        },
                        "targets": 7
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
                        "targets": 11
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
                        "targets": 12
                    },
                      // link with shop
                      {
                        "render": function(data, type, row) {
                            return '<a href="/shops/' + row.shop_id + '">'
                                + row.shop_name + '</a>';
                            },
                        "targets": 3
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
                        "targets": 4
                    },
                ]
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