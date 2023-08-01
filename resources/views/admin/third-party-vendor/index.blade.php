@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Third Party Vendor')
@section('content')


<div class="create-button" style="margin-bottom: 50px;">
    <a href="#" class="btn create-btn">Add New Third Party Vendor</a>
</div>



<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Third Party Vendor Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Cities</th>
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