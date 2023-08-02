@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Branch Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Branch Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('branches.edit' , $branch->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('branches.destroy', $branch->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this Branch?`);">
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
                    {{ $branch->city->name }}
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
            
            
        </div>
    </div>
</div>
@endsection