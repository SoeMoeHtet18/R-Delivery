@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Gate Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Gate Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('gates.edit' , $gate->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('gates.destroy', $gate->id)}}" method="post" onclick="return confirm(`Are you sure you want to Delete this gate?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Gate Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $gate->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>City Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $gate->city->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Townships <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{$townships}}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Address <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $gate->address }}
                </div>
            </div>
            
            
        </div>
    </div>
</div>
@endsection