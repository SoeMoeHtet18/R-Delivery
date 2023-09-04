@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Rider Editing')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Update Rider</strong>
        </h2>
        <form action="{{route('riders.update', $rider->id)}}" method="POST" class="action-form">
            @csrf
            @method('PUT')
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" value="{{$rider->name}}" class="form-control" />
                    @if ($errors->has('name'))
                    <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="phone_number" class="col-2">
                    <h4>Phone Number <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="phone_number" name="phone_number" value="{{$rider->phone_number}}" class="form-control" />
                    @if ($errors->has('phone_number'))
                    <span class="text-danger"><strong>{{ $errors->first('phone_number') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="email" class="col-2">
                    <h4>Email <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="email" id="email" name="email" value="{{$rider->email}}" class="form-control" />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="email" class="col-2">
                    <h4>Salary Type <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="salary_type" id="salary_type" class="form-control">
                        <option value="" selected disabled>Select the Salary Type for This Rider</option>
                        <option value="daily" @if($rider->salary_type == 'daily' ) selected @endif>Daily</option>
                        <option value="monthly" @if($rider->salary_type == 'monthly' ) selected @endif>Monthly</option>
                    </select>
                    @if ($errors->has('salary_type'))
                    <span class="text-danger"><strong>{{ $errors->first('salary_type') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="base_salary" class="col-2">
                    <h4>Base Salary <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="base_salary" name="base_salary" value="{{$rider->base_salary == 0 ? null : $rider->base_salary}}" class="form-control"/>
                    @if ($errors->has('base_salary'))
                    <span class="text-danger"><strong>{{ $errors->first('base_salary') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="password" class="col-2">
                    <h4>Password <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" />
                    @if ($errors->has('password'))
                    <span class="text-danger"><strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="password-confirm" class="col-2 text-nowrap">
                    <h4>Confirm Password <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="password" id="password-confirm" name="password_confirmation" class="form-control" autocomplete="new-password" />

                </div>
            </div>
            <input type="hidden" id="id" name="id" value="{{$rider->id}}" class="form-control" />
            <div class="footer-button float-end">
                <a href="{{url()->previous() }}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>

        </form>
    </div>
</div>

@endsection
@section('javascript')
<script type="text/javascript">
    $(function() {
        $('#salary_type').select2();
        $(".select2-selection").on("focus", function () {
            $(this).parent().parent().prev().select2("open");
        });
    });
</script>
@endsection