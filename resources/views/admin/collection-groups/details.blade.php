@extends('admin.layouts.master')
@section('title','Collections')
@section('sub-title','Pick Up Group Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Pick Up Group Detail</strong>
        </h2>

        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('collection-groups.edit' , $collectionGroup->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('collection-groups.destroy', $collectionGroup->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this collection?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <input type="hidden" id="collection_group_id" value="{{$collectionGroup->id}}">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collectionGroup->total_amount }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Rider Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collectionGroup->rider->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Assigned Date <b>:</b></h4>
                </div>
                <div class="col-10" id="assignedDate">
                    {{ $collectionGroup->assigned_date }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">Pick Up Lists</div>
                </div>
                <div class="portlet-body">
                    <table id="collection-datatable" class="table table-striped table-hover table-responsive datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pick Up Code</th>
                                <th>Total Quantity of Pick Up</th>
                                <th>Total Amount of Pick Up</th>
                                <th>Paid Amount By Rider</th>
                                <th>Shop</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card col-12">
                <div class="card-body">
                    <h5 class="text-center">Customer Exchange</h5>
                    <table id="customer-collection-datatable" class="table table-striped table-hover table-responsive datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Exchange Code</th>
                                <th>Order Code</th>
                                <th>Customer Name</th>
                                <th>Shop</th>
                                <th>Items</th>
                                <th>Paid Amount To Customer</th>
                                <th>Note</th>
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

<script>
    var collection_group_id = $("#collection_group_id").val();
    console.log(collection_group_id);
    var collectionTable = $('#collection-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": '/ajax-get-collection-data-by-group',
            "type": "GET",

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
                data: 'shop_name',
                name: 'shop',
            },
            {
                data: 'note',
                name: 'note',
            },
        ],
    });

    var customerTable = $('#customer-collection-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": '/ajax-get-customer-collections-data-by-group',
            "type": "GET",
            "data": function(r) {
                        r.collection_group_id = collection_group_id;
                    }
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'id'
            },
            {
                data: 'customer_collection_code',
                name: 'customer_collection_code'
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
                data: 'note',
                name: 'note'
            },
        ],
    });

    document.addEventListener('DOMContentLoaded', function() {
        var dateString = document.getElementById("assignedDate").innerText; // Get the assigned date string from the div

        // Convert the date string to a Date object
        var dateObject = new Date(dateString);

        var formattedDate = dateObject.toLocaleDateString("my-MM", {
            year: "numeric",
            month: "long",
            day: "numeric"
        });

        // Update the content of the div with the formatted assigned date
        document.getElementById("assignedDate").innerText = formattedDate;
    });
</script>

@endsection