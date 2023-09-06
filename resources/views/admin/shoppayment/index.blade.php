@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Payment From Shop Listing')
@section('content')

<div class="d-flex justify-content-between mb-3">
    <div class="create-button">
        <a href="{{route('shoppayments.create')}}" class="btn create-btn">Create Payment From Shop</a>
    </div>
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
                <label for="item_type">
                    <strong>Item Type</strong>
                </label>
                <div class="col-10">
                    <select name="item_type" id="item_type" class="form-control">
                        <option value="" selected disabled>Select</option>
                        <option value="delivery_payment">Delivery Payment</option>
                        <option value="remaining_payment">Remaining Payment</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 p-3 col-4">
                <label for="shop_name">
                    <strong>Shop Name</strong>
                </label>
                <div class="col-10">
                    <select name="shop_name" id="shop_name" class="form-control">
                        <option value="" selected disabled>Select</option>
                        @foreach($shops as $shop)
                        <option value="{{$shop->id}}">{{$shop->name}}</option>
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
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 btncenter margin-btn filter-footer">
            <button class="btn btn-primary search_filter">Filter</button>

            <button class="btn btn-secondary" id="reset">Reset</button>
        </div>
    </div>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Payment From Shop Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Shop Name</th>
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

@endsection
@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        function formatWithNumberingSystem(number, decimal_place = 2) {
            return parseFloat(number).toFixed(decimal_place).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
        $("#toggleFilter").on("click", function() {
            $(".filter-content").slideToggle(300);
            $('#item_type').select2({width: '100%'});
            $('#shop_name').select2({width: '100%'});
        });
       
        get_ajax_dynamic_data(item_type = '', shop_name = '', amount = '');

        function get_ajax_dynamic_data(item_type, shop_name, amount) {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-shop-payment-data',
                    "type": "GET",
                    "data": function(r) {
                        r.item_type = item_type;
                        r.shop_name = shop_name;
                        r.amount = amount;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'shop_name',
                        name: 'shop_name'
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
                columnDefs: [
                    // link with shop
                    {
                        "render": function(data, type, row) {
                            return '<a href="/shops/' + row.shop_id + '">'
                                + row.shop_name + '</a>';
                            },
                        "targets": 1
                    },
                    {
                        "render": function(data, type, row) {
                            return formatWithNumberingSystem(row.amount);
                        },
                        "targets": 2
                    },
                    {
                    "render": function(data, type, row) {
                        if (row.type == 'delivery_payment') {
                            return "Delivery Payment";
                        }
                        if (row.type == 'remaining_payment') {
                            return "Remaining Payment";
                        }
                    },
                    "targets": 3
                },]
            });

            $('.search_filter').click(function() {
                var item_type = $('#item_type').val();
                var shop_name = $('#shop_name').val();
                var amount = $('#amount').val();
                table.destroy();
                get_ajax_dynamic_data(item_type, shop_name, amount);
            })
            $("#reset").click(function() {
                $("#item_type").val("").trigger("change");
                $("#shop_name").val("").trigger("change");
                $("#amount").val("").trigger("change");
                var item_type = $('#item_type').val();
                var shop_name = $('#shop_name').val();
                var amount = $('#amount').val();
                table.destroy();
                get_ajax_dynamic_data(item_type, shop_name, amount);
            });
        };
    });
</script>
@endsection