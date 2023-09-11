@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Shop User Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New ShopUser</strong>
        </h2>
        <form action="{{route('shopusers.store')}}" method="POST" class="action-form">
            @csrf
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" value="{{old('name')}}" class="form-control required" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Name is required.</strong>
                    </span>
                    @if ($errors->has('name'))
                    <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="shop" class="col-2">
                    <h4>Shop Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="shop_id" id="shop" class="form-control required">
                        <option value="" selected disabled>Select the shop of this user</option>
                        @foreach($shops as $shop)
                        <option value="{{$shop->id}}" @if($shop->id == old('shop_id')) selected @endif>{{$shop->name}}</option>
                        @endforeach
                    </select>
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Shop is required.</strong>
                    </span>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="phone_number" class="col-2">
                    <h4>Phone Number <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="phone_number" name="phone_number" value="{{old('phone_number')}}" class="form-control required" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Phone Number is required.</strong>
                    </span>
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
                    <input type="email" id="email" name="email" value="{{old('email')}}" class="form-control required" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Email is required.</strong>
                    </span>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="password" class="col-2">
                    <h4>Password <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="password" id="password" name="password" class="form-control required @error('password') is-invalid @enderror" required autocomplete="new-password" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Password is required.</strong>
                    </span>
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
                    <input type="password" id="password-confirm" name="password_confirmation" class="form-control required" required autocomplete="new-password" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Please fill Confirm Password.</strong>
                    </span>
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{route('shopusers.index')}}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        $('#shop').select2({width: '100%'});
        $(".select2-selection").on("focus", function () {
            $(this).parent().parent().prev().select2("open");
        });
    });

    errFieldHandling();

    function errFieldHandling() {
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
    }
</script>
@endsection