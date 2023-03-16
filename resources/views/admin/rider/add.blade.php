@extends('admin.layouts.master')

@section('content')
        <div class="card">
            <div class="card-body">
                <h2 class="ps-1">Add new rider</h2>
                <form action="{{route('riders.store')}}" method="post">
                    @csrf
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Name <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            <input type="text" name="name" class="form-control"/>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Phone Number <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            <input type="text" name="name" class="form-control"/>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Email <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            <input type="email" name="name" class="form-control"/>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Password <b>:</b></h4>
                        </div>
                        <div class="col-10">
                            <input type="password" name="name" class="form-control"/>
                        </div>
                    </div>
                    <input type="submit" value="Create" class="btn btn-success float-end">
                </form>
            </div>
        </div>
        
@endsection