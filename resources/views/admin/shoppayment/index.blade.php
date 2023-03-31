@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Shop Payment Listing')
@section('content')


<div class="create-button">
    <a href="{{route('shoppayments.create')}}" class="btn btn-success">Add Shop Payment</a>
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
                    <input type="text" id="amount" name="amount" class="form-control"/>
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
        <div class="caption">Shop Payment Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Shop Name</th>
                    <th>Amount</th>
                    <th>Type</th>
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
    $('#shop_name').select2();
    get_ajax_dynamic_data(item_type='',shop_name='',amount='');
    function get_ajax_dynamic_data(item_type,shop_name,amount) {
        var table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": '/ajax-get-shop-payment-data',
                "type": "GET",
                "data" : function( r ) {
                    r.item_type = item_type;
                    r.shop_name = shop_name;
                    r.amount    = amount;
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'shop_name', name: 'shop_name'},
                {data: 'amount', name: 'amount'},
                {data: 'type', name: 'type'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
        $('.search_filter').click(function(){
            var item_type = $('#item_type').val();
            var shop_name = $('#shop_name').val();
            var amount    = $('#amount').val();
            table.destroy();
            get_ajax_dynamic_data(item_type,shop_name,amount);
        })
        $("#reset").click(function(){
            $("#item_type").val("").trigger("change");
            $("#shop_name").val("").trigger("change");
            $("#amount").val("").trigger("change");
            var item_type = $('#item_type').val();
            var shop_name = $('#shop_name').val();
            var amount    = $('#amount').val();
            table.destroy();
            get_ajax_dynamic_data(item_type,shop_name,amount);
        });
    };
});
</script>
@endsection