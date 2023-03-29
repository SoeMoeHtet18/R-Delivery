@extends('admin.layouts.master')

@section('content')

<div class="create-button">
    <a href="{{route('townships.create')}}" class="btn btn-success">Add Township</a>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Township Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>City</th>
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
            ajax: "{{route('townships.index')}}",
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'city_name', name: 'city'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            
        });
        
    });
    </script>
@endsection