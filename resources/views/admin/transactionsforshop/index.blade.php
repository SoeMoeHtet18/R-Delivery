@extends('admin.layouts.master')

@section('content')
<style>
    .create-button { 
        width: 100px;
        height: 30px;
        margin-bottom: 10px;
    }
</style>

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
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
  $(function () {
      
    var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{route('transactions-for-shop.index')}}",
        columns: [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'shop_name', name: 'shop_name'},
            {data: 'amount', name: 'amount'},
            {data: 'type', name: 'type'},
            {data: 'paid_by', name: 'paid_by'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
      
  });
</script>
@endsection