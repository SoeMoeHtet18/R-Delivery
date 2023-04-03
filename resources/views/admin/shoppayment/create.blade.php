@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Shop Payment Create')
@section('content')
        <div class="card card-container action-form-card">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Add New Shop Payment</strong>
                </h2>
                <form action="{{route('shoppayments.store')}}" method="POST" class="action-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row m-0 mb-3">
                        <label for="shop" class="col-2">
                            <h4>Shop Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="shop_id" id="shop_name" class="form-control">
                                <option value="" selected disabled>Select the Shop of this Payment</option>
                                @foreach($shops as $shop)
                                    <option value="{{$shop->id}}">{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="amount" class="col-2">
                            <h4>Amount <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="amount" name="amount" class="form-control"/>
                            @if ($errors->has('amount'))
                                <span class="text-danger"><strong>{{ $errors->first('amount') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="image" class="col-2">
                            <h4>Image <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="file" id="image" name="image" class="form-control"/>
                            @if ($errors->has('image'))
                            <span class="text-danger"><strong>{{ $errors->first('image') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="type" class="col-2">
                            <h4>Type <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="type" id="type" class="form-control">
                                <option value="" selected disabled>Select the Type for This Payment</option>
                                    <option value="delivery_payment">Delivery Payment</option>
                                    <option value="remaining_payment">Remaining Payment</option>
                                    
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="description" class="col-2">
                            <h4>Description <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <textarea name="description" id="description" class="form-control" style="height: 100px" placeholder="Write description here"></textarea>
                        </div>
                    </div>
                    <div class="footer-button float-end">
                        <a href="{{route('shoppayments.index')}}" class="btn btn-light">Cancel</a>
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
            $('#type').select2();
        });
    </script>
@endsection