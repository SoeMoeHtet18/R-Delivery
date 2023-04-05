@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Township Create')
@section('content')
        <div class="card card-container action-form-card">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Add New Township</strong>
                </h2>
                <form action="{{route('townships.store')}}" method="POST" class="action-form">
                    @csrf
                    <div class="row m-0 mb-3">
                        <label for="name" class="col-2">
                            <h4>Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="name" name="name" value="{{old('name')}}" class="form-control"/>
                            @if ($errors->has('name'))
                                <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="city" class="col-2">
                            <h4>City <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="city" id="city_id" class="form-control">
                                <option value="" selected disabled>Select City for this Township</option>
                                @foreach($cities as $city)
                                    <option value="{{$city->id}}" @if($city->id == old('city')) selected @endif>{{$city->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="footer-button float-end">
                        <a href="{{route('townships.index')}}" class="btn btn-light">Cancel</a>
                        <input type="submit" class="btn btn-success ">
                    </div>
                </form>
            </div>
        </div>
        
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            $('#city_id').select2();
        });
    </script>
@endsection