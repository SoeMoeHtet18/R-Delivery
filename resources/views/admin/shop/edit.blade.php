@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Shop Editing')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Shop</strong>
        </h2>
        <form action="{{route('shops.update', $shop->id)}}" method="POST" class="action-form">
            @csrf
            @method('PUT')
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" value="{{$shop->name}}" class="form-control" />
                    @if ($errors->has('name'))
                    <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="township_id" class="col-2">
                    <h4>Township Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="township_id" id="township_id" class="form-control">
                        <option value="" selected disabled>Select the Township for This Shop</option>
                        @foreach ( $townships as $township)
                        <option value="{{$township->id}}"
                        @if($shop->township_id == $township->id) {{'selected'}} @endif
                        >{{$township->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="address" class="col-2">
                    <h4>Address <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="address" name="address" value="{{$shop->address}}" class="form-control" />
                    @if ($errors->has('address'))
                    <span class="text-danger"><strong>{{ $errors->first('address') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="phone_number" class="col-2">
                    <h4>Phone Number <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="phone_number" name="phone_number" value="{{$shop->phone_number}}" class="form-control" />
                    @if ($errors->has('phone_number'))
                    <span class="text-danger"><strong>{{ $errors->first('phone_number') }}</strong></span>
                    @endif
                </div>
            </div>
            <input type="hidden" id="id" name="id" value="{{$shop->id}}" class="form-control" />
            <div class="footer-button float-end">
                <a href="{{url()->previous() }}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success float-end">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script type="text/javascript">
    $(function() {
        $('#township_id').select2({width: '100%'});
        $(".select2-selection").on("focus", function () {
            $(this).parent().parent().prev().select2("open");
        });
    });

</script>
@endsection