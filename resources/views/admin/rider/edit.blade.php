@extends('admin.layouts.master')

@section('content')
        <div class="card card-container">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Update Rider</strong>
                </h2>
                <form action="{{route('riders.update', $rider->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row m-0 mb-3">
                        <label for="name" class="col-2">
                            <h4>Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="name" name="name" value="{{$rider->name}}" class="form-control"/>
                            @if ($errors->has('name'))
                                <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="phone_number" class="col-2">
                            <h4>Phone Number <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="phone_number" name="phone_number" value="{{$rider->phone_number}}" class="form-control"/>
                            @if ($errors->has('phone_number'))
                                <span class="text-danger"><strong>{{ $errors->first('phone_number') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="email" class="col-2">
                            <h4>Email <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="email" id="email" name="email" value="{{$rider->email}}" class="form-control"/>
                            @if ($errors->has('email'))
                                <span class="text-danger"><strong>{{ $errors->first('email') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="password" class="col-2">
                            <h4>Password <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Leave empty to keep the same"/>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="device_id" class="col-2">
                            <h4>Device ID <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="device_id" name="device_id" value="{{$rider->device_id}}" class="form-control"/>
                            @if ($errors->has('device_id'))
                                <span class="text-danger"><strong>{{ $errors->first('device_id') }}</strong></span>
                            @endif
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