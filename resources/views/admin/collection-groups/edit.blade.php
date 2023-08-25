@extends('admin.layouts.master')
@section('title','Collections')
@section('sub-title','Pick Up Group Editing')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Pick Up Group</strong>
        </h2>
        <form action="{{route('collection-groups.update', $collectionGroup->id)}}"
            method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount"
                        value="{{$collectionGroup->total_amount}}" class="form-control" />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="rider_id" class="col-2">
                    <h4>Rider <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="rider_id" id="rider_id" class="form-control">
                        <option value="" selected disabled>Select the Rider for This Collection</option>
                        @foreach ( $riders as $rider)
                        <option value="{{$rider->id}}" @if($collectionGroup->rider_id == $rider->id)
                            {{'selected'}} @endif>{{$rider->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="assigned_date" class="col-2">
                    <h4>Assigned Date <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="date" id="assigned_date" name="assigned_date"
                        value="{{$assigndate}}" class="form-control" />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="collection_id" class="col-2">
                    <h4>Pick Ups <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="collection_id[]" id="collection_id" class="form-control" multiple>
                        @foreach($collections as $collection)
                        <option value="{{$collection->id}}"
                        @foreach($collection_ids_by_group as $collection_id_by_group)
                            @if($collection_id_by_group->id == $collection->id) {{'selected'}} @endif
                            @endforeach>{{$collection->collection_code}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="customer_collection_id" class="col-2">
                    <h4>Customer Exchange <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="customer_collection_id[]" id="customer_collection_id" class="form-control" multiple>
                        @foreach($customer_collections as $customer_collection)
                        <option value="{{$customer_collection->id}}"
                            @if($customer_collection->collection_group_id == $collectionGroup->id) {{'selected'}} @endif
                            >{{$customer_collection->customer_collection_code}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{url()->previous() }}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script type="text/javascript">
    $(function() {
        $('#rider_id').select2();
        $('#collection_id').select2({
            placeholder: 'Select Pick Up',
            allowClear: true
        });
        $('#customer_collection_id').select2({
            placeholder: 'Select',
            allowClear: true
        });
    });
</script>
@endsection