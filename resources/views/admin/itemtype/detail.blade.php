@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Item Type Detail')
@section('content')
<style>
    .card-toolbar{
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
    }
    .create-button { 
        width: 70px;
        height: 30px;
        margin-bottom: 10px;
    }
</style>
        <div class="card card-container detail-card">
            <div class="card-body">
                    <h2 class="ps-1 card-header-title">
                        <strong>Item Type Detail</strong>
                    </h2>
                    <div class="card-toolbar">
                    <div class="create-button">
                    <a href="{{route('itemtypes.edit' , $itemtype->id)}}" class="btn btn-light">Edit</a>
                    </div>
                    <form action="{{route('itemtypes.destroy', $itemtype->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="Delete" class="btn btn-danger float-end">
                    </form>
                </div>
                <div class="detail-infos">
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Name <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            {{ $itemtype->name }}
                        </div>
                    </div>
                </div>
                   
            </div>
        </div>
@endsection