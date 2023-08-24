@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Third Party Vendor Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
    <div id="third-party-vendor-id" data-third-party-vendor-id="{{ $thirdPartyVendor->id }}"></div>
        <h2 class="ps-1 card-header-title">
            <strong>Third Party Vendor Detail</strong>
        </h2>
        <div class="card-toolbar">
            <a href="{{url('/third-party-vendor/'.$thirdPartyVendor->id.'/assign-township')}}" class="btn btn-secondary me-3">Assign Township</a>
            <div class="create-button">
                <a href="{{route('third-party-vendor.edit' , $thirdPartyVendor->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('third-party-vendor.destroy', $thirdPartyVendor->id)}}" method="post" onclick="return confirm(`Are you sure you want to delete this third party vendor?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Third Party Vendor Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $thirdPartyVendor->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Address <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $thirdPartyVendor->address}}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Type <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($thirdPartyVendor->type == 'pickup')
                       Pick Up
                    @elseif($thirdPartyVendor->type == 'dropoff')
                       Drop Off
                    @endif
                </div>
            </div>
            {{--<div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Township <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if(count($townships) > 0)
                        @php
                            $assignedTownships = [];
                            foreach($townships as $township){
                                $assignedTownships[] = $township->name;
                            }
                            $townshipName = implode(', ',$assignedTownships);
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
        var thirdPartyVendorId = document.getElementById('third-party-vendor-id').getAttribute('data-third-party-vendor-id');
        var table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": '/ajax-get-townships-with-associable',
                "type": "GET",
                "data": function(r) {
                    r.type = 'third-party-vendor';
                    r.id   = thirdPartyVendorId;
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