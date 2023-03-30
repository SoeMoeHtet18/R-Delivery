@extends('admin.layouts.master')

@section('content')
<div class="mb-5">
    <h3>Dashboard</h3>
    <ol class="page-title-box">
        <li class="page-title-items"><b>R-Delivery</b></li>
        <li class="page-title-items"><b>Dashboard</b></li>
        <li class="page-title-items"><b>Shop</b></li>
        <li class="page-title-items"><b>Shop Create</b></li>
    </ol>
</div>
        <div class="card card-container action-form-card">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Add New Shop</strong>
                </h2>
                <form action="{{route('shops.store')}}" method="POST" class="action-form">
                    @csrf
                    <div class="row m-0 mb-3">
                        <label for="name" class="col-2">
                            <h4>Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="name" name="name" class="form-control"/>
                            @if ($errors->has('name'))
                                <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="address" class="col-2">
                            <h4>Address <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="address" name="address" class="form-control"/>
                            @if ($errors->has('address'))
                                <span class="text-danger"><strong>{{ $errors->first('address') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="phone_number" class="col-2">
                            <h4>Phone Number <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="phone_number" name="phone_number" class="form-control"/>
                            @if ($errors->has('phone_number'))
                            <span class="text-danger"><strong>{{ $errors->first('phone_number') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="footer-button float-end">
                        <a href="{{route('shops.index')}}" class="btn btn-light">Cancel</a>
                        <input type="submit" class="btn btn-success ">
                    </div>
                </form>
            </div>
        </div>
        
@endsection