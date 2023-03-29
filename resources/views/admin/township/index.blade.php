@extends('admin.layouts.master')

@section('content')

<div class="card m-3">
<div class="row tdFilter">
    <div class="col-md-12 col-sm-12 m-3"> 
        <h2>Filter</h2>
    </div>
    </div>
    <div class="row">
    <div class="filter-box">
        <div class="mb-3 p-3 col-4">
            <label for="township_name">
                <strong>Township Name</strong>
            </label>
            <div class="col-10">
                <input type="text" id="township_name" name="township_name" class="form-control"/>
            </div>
        </div>
        <div class="mb-3 p-3 col-4">
            <label for="city">
                <strong>City</strong>
            </label>
            <div class="col-10">
                <select name="city" id="city" class="form-control">
                    <option value="" selected disabled>Select</option>
                    @foreach($cities as $city)
                        <option value="{{$city->id}}">{{$city->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
        
        </div>
    <div class="d-flex flex-row-reverse pb-3">
    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 btncenter margin-btn">
        <button class="btn btn-primary search_filter">Filter</button>

        <button class="btn btn-secondary" id="reset">Reset</button>
        </div>
    </div>
</div>

<div class="create-button">
    <a href="{{route('townships.create')}}" class="btn btn-success">Add Township</a>
</div>

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
                    <th>City</th>
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

    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#city').select2();

    get_ajax_dynamic_data(township_name='',city='');
    function get_ajax_dynamic_data(township_name,city) {
        var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url": '/ajax-get-townships-data',
            "type": "GET",
            "data" : function( r ) {
                r.township_name = township_name;
                r.city = city;
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'city_name', name: 'city'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
    });
    $('.search_filter').click(function(){
        var township_name = $('#township_name').val();
        var city = $('#city').val();
        table.destroy();
        get_ajax_dynamic_data(township_name,city);
    });
    $("#reset").click(function(){
        $("#township_name").val("").trigger("change");
        $("#city").val("").trigger("change");
        var township_name = $('#township_name').val();
        var city = $('#city').val();
        table.destroy();
        get_ajax_dynamic_data(township_name,city);
    })
    };
  });
</script>
@endsection