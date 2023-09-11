@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Township Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Township</strong>
        </h2>
        <form action="{{route('townships.store')}}" method="POST" class="action-form">
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
            <div class="row m-0 mb-3">
                <label for="city" class="col-2">
                    <h4>City <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="city" id="city_id" class="form-control">
                        <option value="" selected disabled>Select City for this Township</option>
                        @foreach($cities as $city)
                        <option value="{{$city->id}}" @if($city->id == old('city')) selected @endif>{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Delivery Fees <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="delivery_fees" name="delivery_fees" value="{{old('delivery_fees')}}"
                        class="form-control required" />
                    <span class="text-danger d-none"><strong>Delivery Fees field is required.</strong></span>
                    @if ($errors->has('delivery_fees'))
                    <span class="text-danger"><strong>{{ $errors->first('delivery_fees') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{route('townships.index')}}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        $('#city_id').select2({width: '100%'});
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