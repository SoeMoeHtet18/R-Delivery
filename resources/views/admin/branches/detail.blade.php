@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Branch Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
    <div id="branch-id" data-branch-id="{{ $branch->id }}"></div>
        <h2 class="ps-1 card-header-title">
            <strong>Branch Detail</strong>
        </h2>
        <div class="card-toolbar">
            <a href="{{url('/branches/'.$branch->id.'/assign-township')}}" class="btn btn-secondary me-3">Assign Township</a>
            <div class="create-button">
                <a href="{{route('branches.edit' , $branch->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('branches.destroy', $branch->id)}}" method="post" onclick="return confirm(`Are you sure you want to delete this branch?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Branch Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $branch->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>City Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    <a href="/cities/{{ $branch->city_id }}">
                        {{ $branch->city->name }}
                    </a>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Phone Number <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $branch->phone_number }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Address <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $branch->address}}
                </div>
            </div>
            {{--<div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Township <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if(count($townships) > 0)
                        @php
                            $branchAssignedTownships = [];
                            foreach($townships as $township){
                                $branchAssignedTownships[] = $township->name;
                            }
                            $townshipName = implode(', ',$branchAssignedTownships);
                        @endphp
                        {{$townshipName}}
                    @else
                        N/A
                    @endif
                </div>
            </div>--}}
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
        var branch_id = document.getElementById('branch-id').getAttribute('data-branch-id');
        var table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": '/ajax-get-townships-with-associable',
                "type": "GET",
                "data": function(r) {
                    r.type = 'branch';
                    r.id   = branch_id;
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