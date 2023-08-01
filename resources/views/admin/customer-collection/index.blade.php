@extends('admin.layouts.master')
@section('title','Collection')
@section('sub-title','Customer Collection Listing')
@section('content')

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Customer Collection Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer Collection Code</th>
                    <th>Collection Group</th>
                    <th>Order Code</th>
                    <th>Customer Name</th>
                    <th>Shop</th>
                    <th>Items</th>
                    <th>Paid Amount To Customer</th>
                    <th>Is Way Fees Payable</th>
                    <th>Status</th>
                    <th>Note</th>
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
        get_ajax_dynamic_data();

        function get_ajax_dynamic_data() {
            console.log('called');
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/ajax-get-customer-collections-data',
                    "type": "GET",  
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'customer_collection_code',
                        name: 'customer_collection_code'
                    },
                    {
                        data: 'collection_group',
                        name: 'collection_group'
                    },
                    {
                        data: 'order_code',
                        name: 'order_code'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'shop_name',
                        name: 'shop'
                    },
                    {
                        data: 'items',
                        name: 'items'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount_to_customer'
                    },
                    {
                        data: 'is_way_fees_payable',
                        name: 'is_way_fees_payable'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'note',
                        name: 'note'
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
                            if (row.is_way_fees_payable == 0) {
                                return "No";
                            }
                            if (row.is_way_fees_payable == 1) {
                                return "Yes";
                            }
                        },
                        "targets": 8
                    },
                    {
                        "render": function(data, type, row) {
                            if (row.status == 'pending') {
                                return "Pending";
                            }
                            if (row.status == 'in-warehouse') {
                                return "In Warehouse";
                            }
                            if (row.status == 'complete') {
                                return "Completed";
                            }
                        },
                        "targets": 9
                    },
                    
                ]
            });

            $('.search_filter').click(function() {
                
                table.destroy();
                get_ajax_dynamic_data();
            })
            $("#reset").click(function() {
              
                table.destroy();
                get_ajax_dynamic_data();
            });
        };
    });
</script>
@endsection