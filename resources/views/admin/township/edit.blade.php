@extends('admin.layouts.master')

@section('content')
        <div class="card card-container action-form-card">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Update Township</strong>
                </h2>
                <form action="{{route('townships.update', $township->id)}}" method="POST" class="action-form">
                    @csrf
                    @method('PUT')
                    <div class="row m-0 mb-3">
                        <label for="name" class="col-2">
                            <h4>Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" id="name" name="name" value="{{$township->name}}" class="form-control"/>
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
                            <select name="city" id="city" class="form-control">
                                <option value="" selected disabled>Select City for This Township</option>
                                @foreach ( $cities as $city)
                                    <option value="{{$city->id}}" @if($township->city_id == $city->id) {{'selected'}} @endif>{{$city->name}}</option>
                                @endforeach
                            </select>
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
@section('javascript')
    <script>
        $(document).ready(function() {
            $('#city').select2();
        });

    </script>
@endsection   