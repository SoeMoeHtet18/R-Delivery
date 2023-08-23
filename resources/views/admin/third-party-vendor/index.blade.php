@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Third Party Vendor')
@section('content')


<div class="create-button" style="margin-bottom: 50px;">
    <a href="/third-party-vendor/create" class="btn create-btn">Add New Third Party Vendor</a>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Third Party Vendor Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Twonships</th>
                    <th>Address</th>
                    <th>Type</th>
                    <th>action</th>
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
    $(document).ready(function() {

        get_ajax_dynamic_data();

        function get_ajax_dynamic_data() {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-third-party-vendor-data',
                    "type": "GET",
                    "data": function(r) {
                        // r.city = city;
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
                    {
                        data: 'townships',
                        name: 'townships'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    {
                        "render": function(data, type, row) {
                            return '<a href="/third-party-vendor/' + row.id + '">'
                                + row.name + '</a>';
                            },
                        "targets": 1
                    },
                    {
                        "render": function(data, type, row) {
                            var assignedTownships = '';
                            if(row.townships.length > 0 ){
                            for(var i = 0; i < row.townships.length; i++){
                                assignedTownships += '<a href="/townships/' + row.townships[i].id + '">'
                                + row.townships[i].name + ' , </a>';
                            }
                            
                            } else {
                            return "N/A"
                            }
                            return assignedTownships.replace(/,\s*$/, "");
                        },
                        "targets": 2
                    },
                    {
                        "render": function(data, type, row) {
                            if(row.type == 'pickup' ){
                                return "Pick Up"
                            } 
                            if(row.type == 'dropoff') {
                            return "Drop Off"
                            }
                            return "N/A";
                        },
                        "targets": 4
                    },
                ]
            });
            
        }
    })
</script>
@endsection