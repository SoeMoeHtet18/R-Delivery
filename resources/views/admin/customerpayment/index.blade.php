@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Customer Payment Listing')
@section('content')


<div class="create-button">
    <a class="btn create-btn" href="{{route('customer-payments.create')}}">Add Customer Payment</a>
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
                <label for="item_type">
                    <strong>Item Type</strong>
                </label>
                <div class="col-10">
                    <select name="item_type" id="item_type" class="form-control">
                        <option value="" selected disabled>Select</option>
                        <option value="fully_paid">Fully Paid</option>
                        <option value="delivery_fees_only">Delivery Fees Only</option>
                        <option value="remaining_amount">Remaining Amount</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 p-3 col-4">
                <label for="order_code">
                    <strong>Order Code</strong>
                </label>
                <div class="col-10">
                    <select name="order_code" id="order_code" class="form-control">
                        <option value="" selected disabled>Select</option>
                        @foreach($orders as $order)
                        <option value="{{$order->id}}">{{$order->order_code}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 p-3 col-4">
                <label for="amount">
                    <strong>Amount</strong>
                </label>
                <div class="col-10">
                    <input type="text" id="amount" name="amount" class="form-control" />
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

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Customer Payment Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Code</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Paid At</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            </tbody>
        </table>
    </div>
</div>

@endsection
@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        $('#item_type').select2();
        $('#order_code').select2();
        get_ajax_dynamic_data(item_type = '', order_code = '', amount = '');

        function get_ajax_dynamic_data(item_type, order_code, amount) {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-customer-payment-data',
                    "type": "GET",
                    "data": function(r) {
                        r.item_type = item_type;
                        r.order_code = order_code;
                        r.amount = amount;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'order_code',
                        name: 'order_code'
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
                        data: 'paid_at',
                        name: 'paid_at'
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
                            if (row.type == 'fully_paid') {
                                return "Fully Paid";
                            }
                            if (row.type == 'delivery_fees_only') {
                                return "Delivery Fees Only";
                            }
                            if (row.type == 'remaining_amount') {
                                return "Remaining Amount";
                            }
                        },
                        "targets": 3
                    },
                    {
                        "render": function(data, type, row) {
                            if (row.paid_at === null) {
                                return '';
                            } else {
                                var date = new Date(row.paid_at);
                                var formattedDate = date.toLocaleDateString('my-MM', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });
                                return formattedDate;
                            }

                        },
                        "targets": 4
                    },
                ]
            });

            $('.search_filter').click(function() {
                var item_type = $('#item_type').val();
                var order_code = $('#order_code').val();
                var amount = $('#amount').val();
                table.destroy();
                get_ajax_dynamic_data(item_type, order_code, amount);
            })
            $("#reset").click(function() {
                $("#item_type").val("").trigger("change");
                $("#order_code").val("").trigger("change");
                $("#amount").val("").trigger("change");
                var item_type = $('#item_type').val();
                var order_code = $('#order_code').val();
                var amount = $('#amount').val();
                table.destroy();
                get_ajax_dynamic_data(item_type, order_code, amount);
            });
        };
    });
</script>
@endsection