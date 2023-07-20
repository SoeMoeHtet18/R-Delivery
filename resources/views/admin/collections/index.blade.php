@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Collections Listing')
@section('content')

<div class="create-button">
    <a class="btn create-btn" href="{{route('collections.create')}}">Add Collection</a>
</div>
<div class="card m-3">
    <div class="row tdFilter">
        <div class="col-md-12 col-sm-12 m-3">
            <h2>Filter</h2>
        </div>
    </div>
    <div class="row">
        <div class="filter-box">
            <div class="mb-3 p-3 col-4">
                <label for="search">
                    <strong>Search</strong>
                </label>
                <div class="col-10">
                    <input type="text" id="search" name="search" class="form-control" />
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex flex-row-reverse pb-3">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 btncenter margin-btn">
            <button class="btn btn-primary search_filter">Filter</button>

            <button class="btn btn-secondary" id="reset">Reset</button>
        </div>
    </div>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Collections Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Total Quantity</th>
                    <th>Total Amount</th>
                    <th>Paid Amount</th>
                    <th>Collection Group Id</th>
                    <th>Rider Id</th>
                    <th>Shop Id</th>
                    <th>Assigned At</th>
                    <th>Collected At</th>
                    <th>Note</th>
                    <th>Status</th>
                    <th>Is payable</th>
                    <th>Created At</th>
                    <th>Updated At</th>
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
        get_ajax_dynamic_data(search = '');

        function get_ajax_dynamic_data(search) {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-collection-data',
                    "type": "GET",
                    "data": function(r) {
                        r.search = search;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'total_quantity',
                        name: 'total_quantity'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount',
                    },
                    {
                        data: 'collection_group_id',
                        name: 'collection_group_id',
                    },
                    {
                        data: 'rider_id',
                        name: 'rider_id',
                    },
                    {
                        data: 'shop_id',
                        name: 'shop_id',
                    },
                    {
                        data: 'assigned_at',
                        name: 'assigned_at',
                    },
                    {
                        data: 'collected_at',
                        name: 'collected_at',
                    },
                    {
                        data: 'note',
                        name: 'note',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'is_payable',
                        name: 'is_payable',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('.search_filter').click(function() {
                var search = $('#search').val();
                var name = $('#name').val();
                table.destroy();
                get_ajax_dynamic_data(search, name);
            })
            $("#reset").click(function() {
                $("#search").val("").trigger("change");
                $("#name").val("").trigger("change");
                var search = $('#search').val();
                var name = $('#name').val();
                table.destroy();
                get_ajax_dynamic_data(search, name);
            });
        };
    });
</script>
@endsection