@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Branch')
@section('content')


<div class="create-button" style="margin-bottom: 50px">
    <a href="{{route('branches.create')}}" class="btn create-btn">Add New Branch</a>
</div>



<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Branch Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Branch Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@endsection
@section('javascript')

@endsection