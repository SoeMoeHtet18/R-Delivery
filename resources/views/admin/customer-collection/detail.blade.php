@extends('admin.layouts.master')
@section('title','Collections')
@section('sub-title','Customer Exchange Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Customer Exchange Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('customer-collections.edit' , $customer_collection->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('customer-collections.destroy', $customer_collection->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this customer payment?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Customer Exchange Code<b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $customer_collection->customer_collection_code }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Pick Up Group<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->collection_group)
                        {{ $customer_collection->collection_group->collection_group_code }}
                    @else N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Order Code<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->order)
                    <a href="/orders/{{ $customer_collection->order_id }}">
                        {{ $customer_collection->order->order_code }}</a>
                    @else N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Customer Name<b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $customer_collection->customer_name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Customer Phone Number<b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $customer_collection->customer_phone_number }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Shop<b>:</b></h4>
                </div>
                <div class="col-10">
                    <a href="/shops/{{ $customer_collection->shop_id }}"> {{$customer_collection->shop->name }}</a>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Rider<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->rider)
                        <a href="/riders/{{ $customer_collection->rider_id }}">
                            {{ $customer_collection->rider->name }}</a>
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>City<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->city)
                        <a href="/cities/{{ $customer_collection->city_id }}">
                            {{ $customer_collection->city->name }}</a>
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Township<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->township)
                        <a href="/townships/{{ $customer_collection->township_id }}">
                            {{ $customer_collection->township->name }}</a>
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Address<b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $customer_collection->address ?? 'N/A'}}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Schedule Date<b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $customer_collection->schedule_date ? \Carbon\Carbon::parse($customer_collection->schedule_date)->format('j F, Y') : 'N/A'}}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Items<b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $customer_collection->items }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Paid Amount To Customer<b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $customer_collection->paid_amount }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Is Way Fees Payable<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->is_way_fees_payable == 0) No @else Yes @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Status<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->status == 'pending')
                    Pending
                    @elseif($customer_collection->status == 'in-warehouse')
                    In Warehouse
                    @else
                    Completed
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Note<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->note != null) {{ $customer_collection->note }}
                    @else N/A
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Item Image<b>:</b></h4>
                </div>
                <div class="col-10">
                    @if($customer_collection->item_image != null)
                    <img src="{{asset('/storage/customer_collection/' . $customer_collection->item_image)}}" alt="" style="width: 200px;">
                    @else N/A
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection