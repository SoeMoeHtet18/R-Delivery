@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Gate Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
    <div id="gate-id" data-gate-id="{{ $gate->id }}"></div>
        <h2 class="ps-1 card-header-title">
            <strong>Gate Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('gates.edit' , $gate->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('gates.destroy', $gate->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this gate?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Gate Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $gate->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>City Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    <a href="/cities/{{ $gate->city_id }}">
                        {{ $gate->city->name }}
                    </a>
                    
                </div>
            </div>
            {{--<div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Townships <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{$townships}}
                </div>
            </div>--}}
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Address <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $gate->address }}
                </div>
            </div>
            <hr>
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
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            
            
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
    $(function() {
        var gate_id = document.getElementById('gate-id').getAttribute('data-gate-id');
        var table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": '/ajax-get-townships-with-associable',
                "type": "GET",
                "data": function(r) {
                    r.type = 'gate';
                    r.id   = gate_id;
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                
            ],
            columnDefs: [
                {
                    "render": function(data, type, row) {
                        return '<a href="/townships/' + row.id + '">' + row.name + '</a>';
                    },
                    "targets": 1
                },
                
            ]
        });
            
       
    });
</script>
@endsection