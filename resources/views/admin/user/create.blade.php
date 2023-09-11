@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','User Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New User</strong>
        </h2>
        <form action="{{route('users.store')}}" method="POST" class="action-form">
            @csrf
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" value="{{old('name')}}" class="form-control required" />
                    <span class="text-danger d-none"><strong>Name field is required.</strong></span>
                    @if ($errors->has('name'))
                    <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            @if(auth()->user()->id == 1)
            <div class="row m-0 mb-3">
                <label for="branch_id" class="col-2">
                    <h4>Branch Name <b>:</b></h4>
                </label>
                <div class=" col-10">
                    <select name="branch_id" id="branch_id" class="form-control">
                        <option value="" selected disabled>Select the Branch for This User</option>
                        @foreach ( $branches as $branch)
                        <option value="{{$branch->id}}" @if($branch->id == old('branch_id')) selected @endif>{{$branch->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('branch_id'))
                    <span class="text-danger"><strong>{{ $errors->first('branch_id') }}</strong></span>
                    @endif
                </div>
            </div>
            @endif
            <div class="row m-0 mb-3">
                <label for="phone_number" class="col-2">
                    <h4>Phone Number <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="phone_number" name="phone_number" value="{{old('phone_number')}}"
                        class="form-control required" />
                    <span class="text-danger d-none"><strong>Phone Number field is required.</strong></span>
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
                <label for="password" class="col-2">
                    <h4>Password <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror required" required
                        autocomplete="new-password" />
                    <span class="text-danger d-none"><strong>Please Enter Your Password.</strong></span>
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
                    <input type="password" id="password-confirm" name="password_confirmation"
                        class="form-control required" required autocomplete="new-password" />
                    <span class="text-danger d-none"><strong>Please Confirm Your Password.</strong></span>
                </div>
            </div>

            <div class="footer-button float-end">
                <a href="{{route('users.index')}}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        $('#branch_id').select2({width: '100%'});
        $(".select2-selection").on("focus", function () {
            $(this).parent().parent().prev().select2("open");
        });

        // Get all input and select elements on the page, including input elements with type="date"
        var inputAndSelectElements = document.querySelectorAll('input, select, input[type="date"]');

        inputAndSelectElements.forEach(function(element) {
            element.addEventListener('keydown', function (event) {
                if (event.key === 'Tab') {

                    // handling the Tab key press on this element
                    var currentIndex = Array.from(inputAndSelectElements).indexOf(document.activeElement);
                    var nextIndex = currentIndex + 1;

                    // Make sure the next index is within bounds
                    if (nextIndex < inputAndSelectElements.length) {
                        // Check if the input is required or not
                        if(inputAndSelectElements[currentIndex].classList.contains('required')) {
                                // Get the value of the checked input
                                $checkForvalue = inputAndSelectElements[currentIndex].value;
                                if($checkForvalue == '') {
                                    // if value is null, show err msg
                                    inputAndSelectElements[currentIndex].nextElementSibling.classList.remove('d-none');
                                } else {
                                    // if value is not null, hide err msg
                                    inputAndSelectElements[currentIndex].nextElementSibling.classList.add('d-none');
                                }
                        }
                    }
                }
            });
        });
    });
</script>
@endsection