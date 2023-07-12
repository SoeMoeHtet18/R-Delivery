@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Delivery Type Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add Delivery Type</strong>
        </h2>
        <form action="{{route('delivery-types.store')}}" method="POST" class="action-form">
            @csrf
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" class="form-control" />
                    @if ($errors->has('name'))
                    <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="notified_on" class="col-2">
                    <h4>Notified On <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="notified_on" name="notified_on" class="form-control" />
                    @if ($errors->has('notified_on'))
                    <span class="text-danger"><strong>{{ $errors->first('notified_on') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{route('cities.index')}}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection