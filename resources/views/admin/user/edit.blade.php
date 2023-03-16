@extends('admin.layouts.master')

@section('content')
        <div class="card card-container">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Update User</strong>
                </h2>
                <form action="{{route('users.update', $user->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Name <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            <input type="text" name="name" value="{{$user->name}}" class="form-control"/>
                            @if ($errors->has('name'))
                                <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Phone Number <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            <input type="text" name="phone_number" value="{{$user->phone_number}}" class="form-control"/>
                            @if ($errors->has('phone_number'))
                                <span class="text-danger"><strong>{{ $errors->first('phone_number') }}</strong></span>
                            @endif
                        </div>
                        
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Email <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            <input type="email" name="email" value="{{$user->email}}" class="form-control"/>
                            @if ($errors->has('email'))
                                <span class="text-danger"><strong>{{ $errors->first('email') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Password <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            <input type="password" name="password" class="form-control" placeholder="Leave empty to keep the same"/>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Device ID <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            <input type="text" name="device_id" value="{{$user->device_id}}" class="form-control"/>
                            @if ($errors->has('device_id'))
                                <span class="text-danger"><strong>{{ $errors->first('device_id') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <input type="submit" class="btn btn-success float-end">
                </form>
            </div>
        </div>
        
@endsection