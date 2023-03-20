@extends('admin.layouts.master')

@section('content')
        <div class="card card-container">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Add New Item Type</strong>
                </h2>
                <form action="{{route('itemtypes.store')}}" method="POST">
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
                    <div class="footer-button float-end">
                        <a href="{{route('itemtypes.index')}}" class="btn btn-light">Cancel</a>
                        <input type="submit" class="btn btn-success ">
                    </div>
                </form>
            </div>
        </div>
        
@endsection