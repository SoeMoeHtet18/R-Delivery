@extends('admin.layouts.master')
@section('sub-title','Collection Group Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Collections Detail</strong>
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
                    <h4>Rider Id <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collectionGroup->rider_id }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Assigned Date <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $collectionGroup->assigned_date }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection