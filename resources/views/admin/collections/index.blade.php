@extends('admin.layouts.master')
@section('title','Collections')
@section('sub-title','Pick Up Listing')
@section('content')

<div class="create-button">
    <a class="btn create-btn" href="{{route('collections.create')}}">Add Pick Up</a>
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
        <div class="caption">Pick Up Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pick Up Code</th>
                    <th>Pick Up Group</th>
                    <th>Total Quantity of Pick Up</th>
                    <th>Total Amount of Pick Up</th>
                    <th>Paid Amount By Rider</th>
                    <th>Rider</th>
                    <th>Shop</th>
                    <th>Scheduled At</th>
                    <th>Collected At</th>
                    <th>Note</th>
                    <th>Status</th>
                    <th>Is Payable</th>
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
                        data: 'collection_code',
                        name: "pick_up_code"
                    }, 
                    {
                        data: 'collection_group_code',
                        name: "pick_up_group"
                    },    
                    {
                        data: 'total_quantity',
                        name: 'total_quantity_of_pick_up'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount_of_pick_up',
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount_by_rider',
                    },
                    {
                        data: 'rider_name',
                        name: 'rider',
                    },
                    {
                        data: 'shop_name',
                        name: 'shop',
                    },
                    {
                        data: 'assigned_at',
                        name: 'scheduled_at',
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    {
                        "render": function(data, type, row) {
                            if (row.status == 'pending') {
                                return "Pending";
                            }
                            if (row.status == 'complete') {
                                return "Completed";
                            }
                            if (row.status == 'in-warehouse') {
                                return "In Warehouse";
                            }
                        },
                        "targets": 11   
                    },
                    {
                        "render": function(data, type, row) {
                            if (row.is_payable == 0) {
                                return "No";
                            }
                            if (row.payment_flag == 1) {
                                return "Yes";
                            }
                        },
                        "targets": 12
                    },
                ],
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