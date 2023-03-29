@extends('admin.layouts.master')

@section('content')

<div class="create-button">
    <a class="btn btn-success" href="{{route('customer-payments.create')}}">Add Customer Payment</a>
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
            ajax: "{{ route('customer-payments.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'order_code', name: 'order_code'},
                {data: 'amount', name: 'amount'},
                {data: 'type', name: 'type'},
                {data: 'paid_at', name: 'paid_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
    });
    </script>
@endsection