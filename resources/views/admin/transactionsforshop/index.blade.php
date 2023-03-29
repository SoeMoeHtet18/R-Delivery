@extends('admin.layouts.master')

@section('content')

<div class="card m-3">
<div class="row tdFilter">
    <div class="col-md-12 col-sm-12 m-3"> 
        <h2>Filter</h2>
    </div>
    </div>
    <div class="row">
    <div class="filter-box">
        <div class="mb-3 p-3 col-4">
            <label for="shop_name">
                <strong>Shop</strong>
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
        <div class="mb-3 p-3 col-4">
            <label for="type">
                <strong>Type</strong>
            </label>
            <div class="col-10">
                <select name="type" id="type" class="form-control">
                    <option value="" selected disabled>Select</option>
                    <option value="fully_payment">Fully Payment</option>
                    <option value="loan_payment">Loan Payment</option>
                </select>
            </div>
        </div>
    </div>
    <div class="filter-box">
        <div class="mb-3 p-3 col-4">
            <label for="paid_by">
                <strong>Paid By</strong>
            </label>
            <div class="col-10">
                <select name="paid_by" id="paid_by" class="form-control">
                    <option value="" selected disabled>Select</option>
                    @foreach($paid_users as $paid_user)
                        <option value="{{$paid_user->id}}">{{$paid_user->name}}</option>
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

<div class="create-button">
    <a href="{{route('transactions-for-shop.create')}}" class="btn btn-success">Add New Transaction</a>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Transaction For Shop Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Shop Name</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Paid By</th>
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

    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#shop_name').select2();
    $('#type').select2();
    $('#paid_by').select2();

    get_ajax_dynamic_data(shop_name='',amount='',type='',paid_by='');
    function get_ajax_dynamic_data(shop_name,amount,type,paid_by) {
        var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": '/ajax-get-transactions-data',
            "type": "GET",
            "data" : function( r ) {
                r.shop_name =shop_name;
                r.amount = amount;
                r.type = type;
                r.paid_by = paid_by;
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'shop_name', name: 'shop_name'},
            {data: 'amount', name: 'amount'},
            {data: 'type', name: 'type'},
            {data: 'paid_by', name: 'paid_by'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    $('.search_filter').click(function(){
            var shop_name = $('#shop_name').val();
            var amount = $('#amount').val();
            var type = $('#type').val();
            var paid_by = $('#paid_by').val();
            table.destroy();
            get_ajax_dynamic_data(shop_name,amount,type,paid_by);
        });
        $("#reset").click(function(){
            $("#shop_name").val("").trigger("change");
            $("#amount").val("").trigger("change");
            $("#type").val("").trigger("change");
            $("#paid_by").val("").trigger("change");
            var shop_name = $('#shop_name').val();
            var amount = $('#amount').val();
            var type = $('#type').val();
            var paid_by = $('#paid_by').val();
            table.destroy();
            get_ajax_dynamic_data(shop_name,amount,type,paid_by);
        });
    }
  })
  
</script>
@endsection