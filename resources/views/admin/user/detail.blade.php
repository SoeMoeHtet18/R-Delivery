@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','User Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>User Detail</strong>
        </h2>
        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('users.edit' , $user->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('users.destroy', $user->id)}}" method="post" onclick="return confirm(`Are you sure you want to delete this user?`);">
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
                    {{ $user->name }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Phone Number <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $user->phone_number }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Email <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if(!$user->email)
                    N/A
                    @endif
                    {{ $user->email }}
                </div>
            </div>
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Device ID <b>:</b></h4>
                </div>
                <div class="col-10">
                    @if(!$user->device_id)
                    N/A
                    @endif
                    {{ $user->device_id }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection