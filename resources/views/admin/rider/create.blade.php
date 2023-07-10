@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Rider Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>
                Add New Rider
            </strong>
        </h2>
        <form action="{{route('riders.store')}}" method="post" class="action-form">
            @csrf
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" value="{{old('name')}}" class="form-control" />
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
                    <input type="text" id="phone_number" name="phone_number" value="{{old('phone_number')}}" class="form-control" />
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
                    <input type="email" id="email" name="email" value="{{old('email')}}" class="form-control" />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="salary_type" class="col-2">
                    <h4>Salary Type <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="salary_type" id="salary_type" class="form-control">
                        <option value="" selected disabled>Select the Salary Type for This Rider</option>
                        <option value="daily" @if(old('salary_type')=='daily' ) selected @endif>Daily</option>
                        <option value="monthly" @if(old('salary_type')=='monthly' ) selected @endif>Monthly</option>
                    </select>
                    @if ($errors->has('salary_type'))
                    <span class="text-danger"><strong>{{ $errors->first('salary_type') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="password" class="col-2">
                    <h4>Password <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password" />
                    @if ($errors->has('password'))
                    <span class="text-danger"><strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>
            </div>

            <div class="row m-0 mb-3">
                <label for="password-confirm" class="col-2">
                    <h4>Confirm Password <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="password" id="password-confirm" name="password_confirmation" class="form-control" required autocomplete="new-password" />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="township_id" class="col-2">
                    <h4>Township Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="township_id[]" id="township_id" class="form-control" multiple>
                        @foreach($townships as $township)
                        <option value="{{$township->id}}" @if(in_array($township->id, old('township_id', []))) selected @endif>{{$township->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{route('riders.index')}}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
    $(function() {
        $('#township_id').select2({
            placeholder: 'Select Townships',
            allowClear: true
        });
    });
</script>
@endsection