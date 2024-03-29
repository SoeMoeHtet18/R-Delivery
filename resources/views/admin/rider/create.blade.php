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
                    <input type="text" id="name" name="name" value="{{old('name')}}" class="form-control required" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Name field is required.</strong>
                    </span>
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
                    <input type="text" id="phone_number" name="phone_number" value="{{old('phone_number')}}" class="form-control required" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Phone Number field is required.</strong>
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
                        <strong>Email field is required.</strong>
                    </span>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="salary_type" class="col-2">
                    <h4>Salary Type <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="salary_type" id="salary_type" class="form-control required">
                        <option value="" selected disabled>Select the Salary Type for This Rider</option>
                        <option value="daily" @if(old('salary_type')=='daily' ) selected @endif>Daily</option>
                        <option value="monthly" @if(old('salary_type')=='monthly' ) selected @endif>Monthly</option>
                    </select>
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Salary Type field is required.</strong>
                    </span>
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
                    <input type="text" id="base_salary" name="base_salary" value="{{old('base_salary')}}" class="form-control required" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Base Salary field is required.</strong>
                    </span>
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
                    <input type="password" id="password" name="password" class="form-control required @error('password') is-invalid @enderror" required autocomplete="new-password" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Password field is required.</strong>
                    </span>
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
                    <input type="password" id="password-confirm" name="password_confirmation" class="form-control required" required autocomplete="new-password" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Pelease Comfirm Your Password</strong>
                    </span>
                </div>
            </div>
            <button type="button" id="add-card-btn" class="btn green rounded-pill">+</button>
            <div id="assign-container">
                <div class="card card-container action-form-card">
                    <div class="card-body">
                        <div class="row m-0 mb-3">
                            <label for="township_id" class="col-2">
                                <h4>Township Name <b>:</b></h4>
                            </label>
                            <div class="col-10">
                                <select name="township_id[]" id="township_id" class="form-control township_id required">
                                    <option value="" selected disabled>Select the Township for This Order</option>
                                    @foreach ( $townships as $township)
                                    <option value="{{$township->id}}">{{$township->name}}</option>
                                    @endforeach
                                </select>
                                <span id="err-txt" class="text-danger d-none">
                                    <strong>Township field is required.</strong>
                                </span>
                                @if ($errors->has('township_id'))
                                    <span class="text-danger"><strong>{{ $errors->first('township_id') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="row m-0 mb-3">
                            <label for="rider_fees" class="col-2">
                                <h4>Rider Fees <b>:</b></h4>
                            </label>
                            <div class="col-10">
                                <input type="text" name="rider_fees[]" id="rider_fees" class="form-control required">
                                <span id="err-txt" class="text-danger d-none">
                                    <strong>Rider Fees field is required.</strong>
                                </span>
                                @if ($errors->has('rider_fees'))
                                    <span class="text-danger"><strong>{{ $errors->first('rider_fees') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>
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
        $('#salary_type').select2({width: '100%'});
        $('#township_id').select2({width: '100%'});
        $(".select2-selection").on("focus", function () {
            $(this).parent().parent().prev().select2("open");
        });
    });

    var clonecard = () => {
        const newIndex = $("#assign-container .card-container").length + 1; // Increment the index for the new card

        return `<div class="card card-container action-form-card">
            <div class="card-body">
                <div class="row m-0 mb-3">
                    <label for="township_id_${newIndex}" class="col-2">
                        <h4>Township Name <b>:</b></h4>
                    </label>
                    <div class="col-10">
                        <select name="township_id[]" id="township_id_${newIndex}" class="form-control township_id required">
                            <option value="" selected disabled>Select the Township for This Order</option>
                            @foreach ($townships as $township)
                                <option value="{{$township->id}}">{{$township->name}}</option>
                            @endforeach
                        </select>
                        <span id="err-txt" class="text-danger d-none">
                            <strong>Township field is required.</strong>
                        </span>
                    </div>
                </div>
                <div class="row m-0 mb-3">
                    <label for="rider_fees_${newIndex}" class="col-2">
                        <h4>Rider Fees <b>:</b></h4>
                    </label>
                    <div class="col-10">
                        <input type="text" name="rider_fees[]" id="rider_fees_${newIndex}" class="form-control required">
                        <span id="err-txt" class="text-danger d-none">
                            <strong>Rider Fees field is required.</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>`;
    };

    var addMoreCard = () => {
        $("#add-card-btn").click(function() {
            console.log("add more cards");
            $("#assign-container").append(clonecard());

            // Initialize select2 for the newly cloned card
            const newIndex = $("#assign-container .card-container").length; // Get the index of the last cloned card
            $(`#township_id_${newIndex}`).select2({width: '100%'});
            $(".select2-selection").on("focus", function () {
                $(this).parent().parent().prev().select2("open");
            });
        });
    }

    addMoreCard();

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
</script>
@endsection