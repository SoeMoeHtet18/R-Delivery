@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Shop User Editing')
@section('content')
        <div class="card card-container action-form-card">
            <div class="card-body">
                <h2 class="ps-1 card-header-title ">
                    <strong>Update ShopUser</strong>
                </h2>
                <form action="{{route('shopusers.update', $shop_user->id)}}" method="POST" class="action-form">
                    @csrf
                    @method('PUT')
                    <div class="row m-0 mb-3">
                        <label for="name" class="col-2">
                            <h4>Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="name" name="name" value="{{$shop_user->name}}" class="form-control"/>
                            @if ($errors->has('name'))
                                <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="shop" class="col-2">
                            <h4>Shop Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="shop_id" id="shop_name" class="form-control">
                                <option value="" disabled>Select the Shop of this user</option>
                                @foreach($shops as $shop)
                                    <option value="{{$shop->id}}" @if($shop->id == $shop_user->shop_id) {{'selected'}} @endif>{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="phone_number" class="col-2">
                            <h4>Phone Number <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="phone_number" name="phone_number" value="{{$shop_user->phone_number}}" class="form-control"/>
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
                            <input type="email" id="email" name="email" value="{{$shop_user->email}}" class="form-control"/>
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
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password"/>
                            @if ($errors->has('password'))
                                <span class="text-danger"><strong>{{ $errors->first('password') }}</strong></span>
                            @endif
                        </div>
                    </div>

                    <div class="row m-0 mb-3">
                        <label for="password-confirm" class="col-2">
                            <h4>Confirm Password <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="password" id="password-confirm" name="password_confirmation" class="form-control" autocomplete="new-password"/>
                            
                        </div>
                    </div>
                    <input type="hidden" id="id" name="id" value="{{$shop_user->id}}" class="form-control"/>
                    <div class="footer-button float-end">
                        <a href="{{url()->previous() }}" class="btn btn-light">Cancel</a>
                        <input type="submit" class="btn btn-success ">
                    </div>
                </form>
            </div>
        </div>
        
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            $('#shop_name').select2();
        });
    </script>
@endsection   