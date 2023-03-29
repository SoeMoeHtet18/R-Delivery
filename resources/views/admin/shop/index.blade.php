@extends('admin.layouts.master')

@section('content')
<div class="mb-5">
    <h3>Dashboard</h3>
    <ol>
        <li>R-Delivery</li>
        <li>Dashboard</li>
        <li>Shops</li>
    </ol>
</div>
<div class="create-button">
    <a href="{{route('shops.create')}}" class="btn btn-success">Add Shop</a>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Shop Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
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
            ajax: "{{route('shops.index')}}",
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'address', name: 'address'},
                {data: 'phone_number', name: 'phone_number'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],  
        });
        
    });
    </script>
@endsection