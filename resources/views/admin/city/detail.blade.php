@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','City Detail')
@section('content')
<div class="card card-container detail-card">
    <div class="card-body">
        <div id="city-id" data-city-id="{{ $city->id }}"></div>
        <h2 class="ps-1 card-header-title">
            <strong>City Detail</strong>
        </h2>

        <div class="card-toolbar">
            <div class="create-button">
                <a href="{{route('cities.edit' , $city->id)}}" class="btn btn-light">Edit</a>
            </div>
            <form action="{{route('cities.destroy', $city->id)}}" method="post" onclick="return confirm(`Are you sure you want to delete this city?`);">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete" class="btn btn-danger float-end">
            </form>
        </div>
        <div class="detail-infos">
            <div class="row m-0 mb-3">
                <div class="col-2">
                    <h4>Name <b>:</b></h4>
                </div>
                <div class="col-10">
                    {{ $city->name }}
                </div>
            </div>
        </div>
        <hr>
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">Township Lists</div>
            </div>
            <div class="portlet-body">
                <table id="datatable" class="table table-striped table-hover table-responsive datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
    $(function() {
        var city_id = document.getElementById('city-id').getAttribute('data-city-id');
        var table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "/cities/" + city_id + "/townships",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
            ],

        });
    });
</script>
@endsection