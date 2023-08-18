@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Rider Detail')
@section('content')
<style>
    .ul-style {
        list-style: none;
        padding: 0;
        line-height: 2rem;
    }

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
<div id="popupCard" class="modal mt-5">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title text-center">Add Deficit</h5>
            </div>
            <form action="{{url('/add-deficit-to-rider')}}" method="POST" class="action-form">
                <!-- Modal Body -->
                <div class="modal-body">

                    @csrf
                    <input type="hidden" name="rider_id" id="rider_id" value="{{$rider->id}}">
                    <div class="row mb-2 me-3 ms-3">
                        <h5 class="ps-0 mb-0">Description</h5>
                        <div class="card order-detail-card" style="border-radius: 10px !important; background: #F1F5F5;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <b>Paid Amount (To Rider)</b><b>{{$paidAmountToRider}} MMK</b>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <b>Paid Amount (From Rider)</b><b>{{$paidAmountFromRider}} MMK</b>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <b>Deficit</b><b>{{$paidAmountToRider - $paidAmountFromRider}} MMK</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="amount">
                            <h5>Amount<b>:</b></h5>
                        </label>
                        <div>
                            <input type="text" id="amount" name="amount" class="form-control" style="border-radius: 2px !important;"/>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" id="pop-up-close-btn" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" id="add-deficit-btn" class="btn green" data-dismiss="modal" value="Add">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Rider Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{url('/rider-payments/create?rider_id='.$rider->id)}}" class="btn create-btn" id="add-rider-payment">Add Rider Payment</a>
            </div>
            <div class="create-button">
                <a class="btn create-btn" id="add-deficit">Add Deficit</a>
            </div>
            <a href="{{url('/riders/'.$rider->id.'/assign-township')}}" class="btn btn-secondary me-3">Assign Township</a>
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
            <div id="rider-id" data-rider-id="{{ $rider->id }}"></div>
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
                    @if(!$rider->email)
                    N/A
                    @endif
                    {{ $rider->email }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Salary Type <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if(!$rider->salary_type)
                    N/A
                    @endif
                    {{ strtoupper($rider->salary_type) }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Device ID <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if(!$rider->device_id)
                    N/A
                    @endif
                    {{ $rider->device_id }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Townships <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if(!$rider->townships()->exists())
                    N/A
                    @endif
                    <ul class="ul-style">
                        @foreach($rider->townships as $rider_township)
                        @foreach($townships as $township)
                        @if($rider_township->id == $township->id)
                        <li>{{$township->name}}</li>
                        @endif
                        @endforeach
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <hr>
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a href="#pending-order-display" id="pending-order-tab" class="nav-link active" data-toggle="tab">Today Orders</a>
            </li>
            <li class="nav-item">
                <a href="#order-history-display" id="order-history-tab" class="nav-link" data-toggle="tab">Orders History</a>
            </li>
            <li class="nav-item">
                <a href="#collection-display" id="collection-tab" class="nav-link" data-toggle="tab">Pick Ups</a>
            </li>
            <li class="nav-item">
                <a href="#deficit-display" id="deficit-tab" class="nav-link" data-toggle="tab">Deficits</a>
            </li>
        </ul>
        <input type="hidden" id="current_screen" value="pending-order-tab">
       <div class="d-flex justify-content-end mb-3">
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
        <div class="tab-content">
            <div id="pending-order-display" class="portlet box green tab-pane active">
                <div class="portlet-title">
                    <div class="caption">Today Orders</div>
                </div>
                <div class="portlet-body">
                    <table id="pending-order-datatable" class="table table-striped table-hover table-responsive datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order Code</th>
                                <th>Customer Name</th>
                                <th>Customer Phone Number</th>
                                <th>City</th>
                                <th>Township</th>
                                <th>Shop</th>
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
                    <div class="caption">Order History</div>
                </div>
                <div class="portlet-body">
                    <table id="order-history-datatable" class="table table-striped table-hover table-responsive datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order Code</th>
                                <th>Customer Name</th>
                                <th>Customer Phone Number</th>
                                <th>City</th>
                                <th>Township</th>
                                <th>Shop</th>
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
            <div id="collection-display" class="portlet box green tab-pane">
                <div class="portlet-title">
                    <div class="caption">Pick Ups</div>
                </div>
                <div class="portlet-body">
                    <table id="collection-datatable" class="table table-striped table-hover table-responsive datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pick Up Code</th>
                                <th>Total Quantity</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Collection Group</th>
                                <th>Shop</th>
                                <th>Collected At</th>
                                <th>Note</th>
                                <th>Status</th>
                                <th>Is Payable</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div id="deficit-display" class="portlet box green tab-pane">
                <div class="portlet-title">
                    <div class="caption">Deficits</div>
                </div>
                <div class="portlet-body">
                    <table id="deficit-datatable" class="table table-striped table-hover table-responsive datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
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
        var tabIndex = 0;
        $('.nav-tabs a').click(function() {
            $(this).tab('show');
            tabIndex = $('.nav-tabs a').index(this);
        });

        var rider_id = document.getElementById('rider-id').getAttribute('data-rider-id');

        $('#add-deficit').click(function() {
            console.log('showed');
            showPopupCard();
        });

        $('#pop-up-close-btn').click(function() {
            hidePopupCard();
        });

        function showPopupCard() {
            var popupCard = document.getElementById('popupCard');
            popupCard.style.display = 'block';
        }

        function hidePopupCard() {
            console.log('hided');
            var popupCard = document.getElementById('popupCard');
            popupCard.style.display = 'none';
        }

        $('#pending-order-datatable').DataTable({
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
            ajax: "/riders/get-today-orders-by-rider-id/" + rider_id,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
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
                    data: 'shop_name',
                    name: 'shop'
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
                    data: 'delivery_type_name',
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
            columnDefs: [
                    // link with shop
                    {
                            "render": function(data, type, row) {
                            return '<a href="/shops/' + row.shop_id + '">'
                                + row.shop_name + '</a>';
                            },
                        "targets": 6
                    },
                    // link with order
                    {
                        "render": function(data, type, row) {
                                return '<a href="/orders/' + row.id + '">'
                                + row.order_code + '</a>';
                        },
                        "targets": 1
                    },
                    // link with city
                    {
                        "render": function(data, type, row) {
                            return '<a href="/cities/' + row.city_id + '">'
                                + row.city_name + '</a>';
                            },
                        "targets": 4
                    },
                    // link with township
                    {
                        "render": function(data, type, row) {
                            return '<a href="/townships/' + row.township_id + '">'
                                + row.township_name + '</a>';
                            },
                        "targets": 5
                    },
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
                        "targets": 8
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
                    "targets": 13
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
            ]
        });

        $('#order-history-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "/riders/get-order-history-by-rider-id/" + rider_id,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
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
                    data: 'shop_name',
                    name: 'shop'
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
                    data: 'delivery_type_name',
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
            columnDefs: [
                // link with shop
                {
                    "render": function(data, type, row) {
                        return '<a href="/shops/' + row.shop_id + '">'
                            + row.shop_name + '</a>';
                        },
                    "targets": 6
                },
                // link with order
                {
                    "render": function(data, type, row) {
                            return '<a href="/orders/' + row.id + '">'
                            + row.order_code + '</a>';
                    },
                    "targets": 1
                },
                // link with city
                {
                    "render": function(data, type, row) {
                        return '<a href="/cities/' + row.city_id + '">'
                            + row.city_name + '</a>';
                        },
                    "targets": 4
                },
                // link with township
                {
                    "render": function(data, type, row) {
                        return '<a href="/townships/' + row.township_id + '">'
                            + row.township_name + '</a>';
                        },
                    "targets": 5
                },
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
                        "targets": 8
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
                    "targets": 13
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
            ]
        });
        $('#collection-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "/riders/get-collection-by-rider-id/" + rider_id,
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
                    name: 'collection_group_id',
                },
                {
                    data: 'shop_name',
                    name: 'shop',
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
                    // link with collection
                    {
                        "render": function(data, type, row) {
                            return '<a href="/collections/' + row.id + '">'
                                + row.collection_code + '</a>';
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
                        "targets": 5
                    },
                    // link with shop
                    {
                        "render": function(data, type, row) {
                            return '<a href="/shops/' + row.shop_id + '">'
                                + row.shop_name + '</a>';
                            },
                        "targets": 6
                    },
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

        $('#deficit-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "/riders/get-deficit-by-rider-id/" + rider_id,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'total_amount',
                    name: 'amount',
                },
            ],
        });

        const form = $('#pdf_form');

        // Add event listener to the form submit event
        form.submit(function(event) {
            // Prevent the default form submission behavior
            event.preventDefault();
            var rider_id = document.getElementById('rider-id').getAttribute('data-rider-id');
            var type = tabIndex == 0 ? 'order' : tabIndex == 2 ? 'pick_up' : '';            
            generatePDF(rider_id, type);
        });

        function generatePDF(rider_id, type) {
            // Create the download URL with query parameters
            const downloadUrl = `/generate-rider-pdf?rider_id=${encodeURIComponent(rider_id)}&type=${encodeURIComponent(type)}`;
            // Navigate to the download URL
            window.location.href = downloadUrl;
        }
    });
</script>
@endsection