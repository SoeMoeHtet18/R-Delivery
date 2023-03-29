@extends('admin.layouts.master')

@section('content')

<div class="create-button">
    <a href="{{route('riders.create')}}" class="btn btn-success">Add Rider</a>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Rider Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
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
            ajax: "{{route('riders.index')}}",
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'phone_number', name: 'phone_number'},
                {data: 'email', name: 'email'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
    });
    </script>
@endsection