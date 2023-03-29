@extends('admin.layouts.master')

@section('content')

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