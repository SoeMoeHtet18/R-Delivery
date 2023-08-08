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

    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        /* Could be more or less, depending on screen size */
    }

    /* Close button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .tabs {
        width: 150px;
        height: 40px;
        border: 1px solid #64C5B1;
        cursor: pointer;
    }

    .text-green {
        color: #64C5B1;
    }

    .bg-cyan {
        background-color: #64C5B1;
    }

    .filter-content {
        display: none;
    }
</style>

<div class='d-flex justify-content-between mb-3'>
    <div class="create-button">
        <a class="btn create-btn" href="{{route('orders.create')}}">Add Order</a>
    </div>

    <button class="btn btn-link" id="toggleFilter"><svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.87768 20C7.55976 20 7.29308 19.88 7.07765 19.64C6.86222 19.4 6.75487 19.1033 6.75562 18.75V11.25L0.247706 2C-0.0328074 1.58333 -0.0750713 1.14583 0.120914 0.6875C0.3169 0.229167 0.658378 0 1.14535 0H16.8541C17.3403 0 17.6818 0.229167 17.8785 0.6875C18.0753 1.14583 18.033 1.58333 17.7518 2L11.2438 11.25V18.75C11.2438 19.1042 11.1361 19.4012 10.9207 19.6412C10.7053 19.8812 10.439 20.0008 10.1218 20H7.87768Z" fill="black" />
        </svg>
    </button>
</div>
<div class="card m-3 mt-0 filter-content">
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
                <label for="city">
                    <strong>City</strong>
                </label>
                <div class="col-10">
                    <select name="city" id="city" class="form-control">
                        <option value="" selected disabled>Select</option>
                        @foreach($cities as $city)
                        <option value="{{$city->id}}">{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 p-3 col-4">
                <label for="status">
                    <strong>Status</strong>
                </label>
                <div class="col-10">
                    <select name="status" id="status" class="form-control">
                        <option value="" selected disabled>Select</option>
                        <option value="pending">Pending</option>
                        <option value="picking-up">Picking Up</option>
                        <option value="warehouse">In Warehouse</option>
                        <option value="delivering">Delivering</option>
                        <option value="delay">Delay</option>
                        <option value="cancel_request">Cancel Request</option>
                        <option value="success">Delivered</option>
                        <option value="cancel">Cancel</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 p-3 col-4">
                <label for="township">
                    <strong>Township</strong>
                </label>
                <div class="col-10">
                    <select name="township" id="township" class="form-control">
                        <option value="" selected disabled>Select</option>
                        @foreach($townships as $township)
                        <option value="{{$township->id}}">{{$township->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="filter-box">
            <div class="mb-3 p-3 col-4">
                <label for="city">
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
            <div class="mb-3 p-3 col-4">
                <label for="pay_later">
                    <strong>Pay Later</strong>
                </label>
                <div class="col-10">
                    <select name="pay_later" id="pay_later" class="form-control">
                        <option value="" selected disabled>Select</option>
                        <option value="{{true}}">Yes</option>
                        <option value="false">No</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="filter-box">
            <div class="mb-3 p-3 col-4">
                <label for="pick_up_date">
                    <strong>Pick Up Date</strong>
                </label>
                <div class="col-10">
                    <input type="date" id="pick_up_date" name="pick_up_date" class="form-control" />
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

<div class="d-flex justify-content-between mb-3">
    <div class="d-flex">
        <div class="create-button">
            <a class="btn create-btn" id="create-transaction">Add Transaction</a>
        </div>
        <div class="create-button">
            <a class="btn create-btn" id="bulk-discount-update">Bulk Discount Update</a>
        </div>
        <div class="create-button">
            <a class="btn create-btn" id="qr-code-generate">Print QrCode</a>
        </div>
    </div>
    <div>
        <div class="create-button d-inline-block">
            <a href="{{url('/import-orders')}}" class="btn btn-dark">Import CSV</a>
        </div>
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
</div>
<!-- Modal (Pop-up card) -->
<div id="popupCard" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title text-center">Bulk Discount Update</h5>
            </div>
            <form action="{{url('/bulk-discount-update')}}" method="POST" class="action-form">
                <!-- Modal Body -->
                <div class="modal-body">

                    @csrf
                    <input type="hidden" name="order_ids" id="discounted_order_ids">
                    <div class="row m-0 mb-3">
                        <label class="col-sm-12">
                            <h5>Discount Type</h5>
                        </label>
                        <div id="tabs-container" class="col-sm-12 d-flex mt-2">
                            <div id="tab-one" class="tabs tab-one d-flex justify-content-center align-items-center text-green">Fixed Fees Discount</div>
                            <div id="tab-two" class="tabs tab-two d-flex justify-content-center align-items-center text-green">Normal Discount</div>
                        </div>
                    </div>
                    <input type="hidden" id="bulk-discount-type" name="bulk-discount-type">
                    <div class="row m-0 mb-3">
                        <label for="amount">
                            <h5>Amount<b>:</b></h5>
                        </label>
                        <div>
                            <input type="text" id="amount" name="amount" class="form-control" />
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" id="pop-up-close-btn" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" id="discount-update-btn" class="btn green" data-dismiss="modal" value="Update">
                </div>
            </form>
        </div>
    </div>
</div>
<div id="popupQrCard" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title text-center">Print Qr</h5>
            </div>
            <form action="">
                <!-- Modal Body -->
                <div class="modal-body">

                    @csrf
                    <input type="hidden" name="qr_order_ids" id="printed_order_ids">
                    <div class="row m-0 mb-3">
                        <div>
                            <h4>Select Template <b>*</b></h4>
                        </div>
                        <div>
                            <select name="template" id="template" class="form-control">
                                <option value="" selected disabled>Select template </option>
                                <option value="roll_1x2">1" x 2" (Roll Format)</option>
                                <option value="roll_2x3">2" x 3" (Roll Format)</option>
                                <option value="roll_3x5_cm">3cm x 5cm (Roll Format)</option>
                                <option value="roll_4x6_cm">4cm x 6cm (Roll Format)</option>
                                <option value="sheet_1x2">1" x 2" (Sheet Format - Portrait)</option>
                                <option value="sheet_3x5cm_portrait">3cm x 5cm (Sheet Format - Portrait)</option>
                                <option value="sheet_3x5cm_landscape">3cm x 5cm (Sheet Format - Landscape)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="qr-pop-up-close-btn" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" id="qr-code-generate-btn" class="btn green" data-dismiss="modal" value="Print">
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a href="#all-orders-display" id="all-orders-tab" class="nav-link active" data-toggle="tab">All Orders</a>
    </li>
    <li class="nav-item">
        <a href="#cancel-request-orders-display" id="cancel-request-orders-tab" class="nav-link" data-toggle="tab">Cancel Request Orders</a>
    </li>
    <li class="nav-item">
        <a href="#rejected-order-display" id="rejected-order-tab" class="nav-link" data-toggle="tab">Rejected Orders</a>
    </li>
    <li class="nav-item">
        <a href="#warehouse-order-display" id="warehouse-order-tab" class="nav-link" data-toggle="tab">Warehouse Orders</a>
    </li>
</ul>

<input type="hidden" id="current_screen" value="all-orders-display">
<div class="tab-content">
    <div id="all-orders-display" class="portlet box green tab-pane active">
        <div class="portlet-title">
            <div class="caption">Order Lists</div>
        </div>
        <div class="portlet-body">
            <table id="all-orders-datatable" class="table table-striped table-hover table-responsive datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th id="first_column"><input type="checkbox" name="check_all" id="checkAll"></th>
                        <th>Paid</th>
                        <th>Total Amount</th>
                        <th>Delivery Fees</th>
                        <!-- <th>Markup Delivery Fees</th> -->
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
                        <th>Branch</th>
                        <th>Pick Up Group Code</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div id="cancel-request-orders-display" class="portlet box green tab-pane">
        <div class="portlet-title">
            <div class="caption">Cancel Request Orders Lists</div>
        </div>
        <div class="portlet-body">
            <table id="cancel-request-orders-datatable" class="table table-striped table-hover table-responsive datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Action</th>
                        <th>Total Amount</th>
                        <th>Delivery Fees</th>
                        <!-- <th>Markup Delivery Fees</th> -->
                        <th>Order Code</th>
                        <th>Shop</th>
                        <th>Rider</th>
                        <th>Customer Name</th>
                        <th>Customer Phone Number</th>
                        <th>Paid</th>
                        <th>Branch</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div id="rejected-order-display" class="portlet box green tab-pane">
        <div class="portlet-title">
            <div class="caption">Rejected Orders Lists</div>
        </div>
        <div class="portlet-body">
            <table id="cancel-orders-datatable" class="table table-striped table-hover table-responsive datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Action</th>
                        <th>Total Amount</th>
                        <th>Delivery Fees</th>
                        <th>Order Code</th>
                        <th>Shop</th>
                        <th>Rider</th>
                        <th>Customer Name</th>
                        <th>Customer Phone Number</th>
                        <th>Paid</th>
                        <th>Branch</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div id="warehouse-order-display" class="portlet box green tab-pane">
        <div class="portlet-title">
            <div class="caption">In Warehouse Orders Lists</div>
        </div>
        <div class="portlet-body">
            <table id="warehouse-orders-datatable" class="table table-striped table-hover table-responsive datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Action</th>
                        <th>Total Amount</th>
                        <th>Delivery Fees</th>
                        <!-- <th>Markup Delivery Fees</th> -->
                        <th>Order Code</th>
                        <th>Shop</th>
                        <th>Rider</th>
                        <th>Customer Name</th>
                        <th>Customer Phone Number</th>
                        <th>Paid</th>
                        <th>Branch</th>
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

        $("#toggleFilter").on("click", function() {
            $(".filter-content").slideToggle(300);
            $('#city').select2([]);
            $('#status').select2();
            $('#township').select2();
            $('#rider').select2();
            $('#shop').select2();
            $('#pay_later').select2();
        });

        $('#bulk-discount-update').click(function() {
            var order_ids = Array.from(new Set(
                $('.order-payment:checked').map(function() {
                    return $(this).data('id');
                }).get()
            ));

            if (order_ids.length > 0) {
                $('#discounted_order_ids').val(order_ids);
                showPopupCard();
                console.log($('#discounted_order_ids').val());
            } else {
                showErrorToast("Please select at least one order.");
            }
        });

        $('#qr-code-generate').click(function() {
            var order_ids = Array.from(new Set(
                $('.order-payment:checked').map(function() {
                    return $(this).data('id');
                }).get()
            ));

            if (order_ids.length > 0) {
                $('#printed_order_ids').val(order_ids);
                // $.ajax({
                //     url: "{{ url('/generate-qrcode') }}",
                //     type: 'POST',
                //     dataType: 'json',
                //     data: {
                //         order_ids: order_ids,
                //         _token: '{{ csrf_token() }}'
                //     },
                //     success: function(data) {
                //         // Handle the AJAX response here
                //         $('#ajaxResult').html(data.result);
                //     },
                //     error: function(xhr, status, error) {
                //         // Handle errors, if any
                //         console.log(xhr.responseText);
                //     }
                // });
                // Create a form element
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ url('/generate-qrcode') }}";
                form.target = '_blank';

                // Create hidden input fields for order_ids and _token
                var orderIdsInput = document.createElement('input');
                orderIdsInput.type = 'hidden';
                orderIdsInput.name = 'order_ids';
                orderIdsInput.value = order_ids;

                var csrfTokenInput = document.createElement('input');
                csrfTokenInput.type = 'hidden';
                csrfTokenInput.name = '_token';
                csrfTokenInput.value = '{{ csrf_token() }}';

                // Append the input fields to the form
                form.appendChild(orderIdsInput);
                form.appendChild(csrfTokenInput);

                // Append the form to the document body and submit it
                document.body.appendChild(form);
                form.submit();

                // showPopupQrCard();
            } else {
                showErrorToast("Please select at least one order.");
            }
        });

        $('#qr-pop-up-close-btn').click(function() {
            hidePopupQrCard();
        });

        $('#pop-up-close-btn').click(function() {
            hidePopupCard();
        });
        $('#tabs-container').on('click', '.tabs', function() {
            var $this = $(this);
            var $tabs = $('.tabs'); // Cache all tabs

            $tabs.not($this).removeClass('bg-cyan text-white clicked'); // Remove classes from other tabs
            $this.addClass('bg-cyan text-white clicked'); // Add classes to the clicked tab

            if ($this.attr('id') == 'tab-one') {
                $('#bulk-discount-type').val('fixed_fees_discount');
            } else {
                $('#bulk-discount-type').val('normal_discount');
            }
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

        function showPopupQrCard() {
            var popupCard = document.getElementById('popupQrCard');
            popupCard.style.display = 'block';
        }

        function hidePopupQrCard() {
            console.log('hided');
            var popupCard = document.getElementById('popupQrCard');
            popupCard.style.display = 'none';
        }

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

        $('.nav-tabs a').click(function() {
            $(this).tab('show');
        });

        get_ajax_dynamic_data(search = '', city = '', rider = '', shop = '', status = '', township = '', pay_later = '', pick_up_date = '');

        function get_ajax_dynamic_data(search, city, rider, shop, status, township, pay_later, pick_up_date) {
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
                    "url": '/ajax-get-orders-data',
                    "type": "GET",
                    "data": function(r) {
                        r.search = search;
                        r.city = city;
                        r.rider = rider;
                        r.shop = shop;
                        r.status = status;
                        r.township = township;
                        r.pay_later = pay_later;
                        r.pick_up_date = pick_up_date;
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
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'delivery_fees',
                        name: 'delivery_fees'
                    },
                    // {
                    //     data: 'markup_delivery_fees',
                    //     name: 'markup_delivery_fees'
                    // },
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
                    {
                        data: 'branch_name',
                        name: 'branch'
                    },
                    {
                        data: 'collection_group_code',
                        name: 'pick_up_group_code'
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
                            if (row.status == 'cancel_request') {
                                return "Cancel Request";
                            }
                            if (row.status == 'cancel') {
                                return "Cancel";
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
                $("#search").val("").trigger("change");
                var status = $('#status').val();
                var township = $('#township').val();
                var search = $('#search').val();
                var rider = $('#rider').val();
                var city = $('#city').val();
                var shop = $('#shop').val();
                var pay_later = $('#pay_later').val();
                var pick_up_date = $('#pick_up_date').val();
                console.log(pick_up_date);

                table.destroy();
                get_ajax_dynamic_data(search, city, rider, shop, status, township, pay_later, pick_up_date);
            });
            $("#reset").click(function() {
                $("#status").val("").trigger("change");
                $("#township").val("").trigger("change");
                $("#search").val("").trigger("change");
                $("#rider").val("").trigger("change");
                $("#city").val("").trigger("change");
                $("#shop").val("").trigger("change");
                $("#pay_later").val("").trigger("change");
                $("#pick_up_date").val("").trigger("change");
                var status = $("#status").val();
                var township = $('#township').val();
                var search = $('#search').val();
                var rider = $('#rider').val();
                var city = $('#city').val();
                var shop = $('#shop').val();
                var pay_later = $('#pay_later').val();
                var pick_up_date = $('#pick_up_date').val();
                table.destroy();
                get_ajax_dynamic_data(search, city, rider, shop, status, township, pay_later, pick_up_date);
            });

            $('#search').keypress(function(e) {
                console.log('keypress');
                if (e.which === 13) { // Check if the pressed key is Enter (key code 13)
                    e.preventDefault();
                    var search = $('#search').val();
                    table.destroy();
                    get_ajax_dynamic_data(search, city = '', rider = '', shop = '', status = '', township = '', pay_later = '', pick_up_date = '');
                }
            });
        };

        get_ajax_dynamic_data_for_cancel_request_table(search = '', city = '', rider = '', shop = '', status = '', township = '');

        function get_ajax_dynamic_data_for_cancel_request_table() {
            var table = $('#cancel-request-orders-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-cancel-request-orders-data',
                    "type": "GET",
                    "data": function(r) {
                        r.search = search;
                        r.city = city;
                        r.rider = rider;
                        r.shop = shop;
                        r.status = 'cancel_request';
                        r.township = township;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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
                    // {
                    //     data: 'markup_delivery_fees',
                    //     name: 'markup_delivery_fees'
                    // },
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
                        data: 'branch_name',
                        name: 'branch'
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
                        "targets": 9
                    },

                ]
            });
        }

        get_ajax_dynamic_data_for_cancel_table(search = '', city = '', rider = '', shop = '', status = '', township = '');

        function get_ajax_dynamic_data_for_cancel_table() {
            var table = $('#cancel-orders-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-cancel-orders-data',
                    "type": "GET",
                    "data": function(r) {
                        r.search = search;
                        r.city = city;
                        r.rider = rider;
                        r.shop = shop;
                        r.status = 'cancel';
                        r.township = township;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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
                        data: 'branch_name',
                        name: 'branch'
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
                        "targets": 9
                    },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.total_amount;
                    //     },
                    //     "targets": 2
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.delivery_fees;
                    //     },
                    //     "targets": 3
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.markup_delivery_fees;
                    //     },
                    //     "targets": 4
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.order_code;
                    //     },
                    //     "targets": 5
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.shop_name;
                    //     },
                    //     "targets": 6
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.rider_name;
                    //     },
                    //     "targets": 7
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.customer_name;
                    //     },
                    //     "targets": 8
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.customer_phone_number;
                    //     },
                    //     "targets": 9
                    // },
                ]
            });
        }

        get_ajax_dynamic_data_for_warehouse_table(search = '', city = '', rider = '', shop = '', status = '', township = '');

        function get_ajax_dynamic_data_for_warehouse_table() {
            var table = $('#warehouse-orders-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-warehouse-orders-data',
                    "type": "GET",
                    "data": function(r) {
                        r.search = search;
                        r.city = city;
                        r.rider = rider;
                        r.shop = shop;
                        r.status = 'in-warehouse';
                        r.township = township;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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
                    // {
                    //     data: 'markup_delivery_fees',
                    //     name: 'markup_delivery_fees'
                    // },
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
                        data: 'branch_name',
                        name: 'branch'
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
                        "targets": 9
                    },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.total_amount;
                    //     },
                    //     "targets": 2
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.delivery_fees;
                    //     },
                    //     "targets": 3
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.markup_delivery_fees;
                    //     },
                    //     "targets": 4
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.order_code;
                    //     },
                    //     "targets": 5
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.shop_name;
                    //     },
                    //     "targets": 6
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.rider_name;
                    //     },
                    //     "targets": 7
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.customer_name;
                    //     },
                    //     "targets": 8
                    // },
                    // {
                    //     "render": function(data, type, row) {
                    //         return row.customer_phone_number;
                    //     },
                    //     "targets": 9
                    // },

                ]
            });
        }
        const current_url = window.location.href;
        var urlArr = current_url.split('#');
        var current_tab = urlArr[1];
        console.log(current_tab);
        console.log('a[href="#' + current_tab + '"]');
        $('a[href="#' + current_tab + '"]').click();

        // Get the form element
        const form = $('#pdf_form');

        // Add event listener to the form submit event
        form.submit(function(event) {
            // Prevent the default form submission behavior
            event.preventDefault();

            var status = $('#status').val();
            var township = $('#township').val();
            var search = $('#search').val();
            var rider = $('#rider').val();
            var city = $('#city').val();
            var shop = $('#shop').val();

            generatePDF(search, city, rider, shop, status, township);
        });

        function generatePDF(search, city, rider, shop, status, township) {
            // Create the download URL with query parameters
            const downloadUrl = `/generate-pdf?search=${encodeURIComponent(search)}&city=${encodeURIComponent(city)}&rider=${encodeURIComponent(rider)}&shop=${encodeURIComponent(shop)}&status=${encodeURIComponent(status)}&township=${encodeURIComponent(township)}`;
            // Navigate to the download URL
            window.location.href = downloadUrl;
        }
    });
</script>
@endsection