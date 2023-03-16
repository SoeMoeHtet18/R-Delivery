@extends('admin.layouts.master')

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
        <div class="card">
            <div class="card-body">
                    <h2 class="ps-1">
                        <strong>Rider Detail</strong>
                    </h2>
                    <div class="card-toolbar">
                    <div class="create-button">
                    <a href="{{route('riders.edit' , $rider->id)}}" class="btn btn-light">Edit</a>
                    </div>
                    <form action="{{route('riders.destroy', $rider->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger float-end">
                    </form>
                </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Name <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $rider->name }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Phone Number <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $rider->phone_number }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Email <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $rider->email }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Device ID <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $rider->device_id }}
                        </div>
                    </div>
            </div>
        </div>
@endsection