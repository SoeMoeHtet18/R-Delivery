@extends('admin.layouts.master')
@section('title','Adimn Tools')
@section('sub-title','Gate Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Gate</strong>
        </h2>
        <form action="{{route('gates.store')}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" class="form-control required"
                        value="@isset($name){{ $name }}@else{{ old('name') }}@endisset" />
                    <span class="text-danger d-none"><strong>Name field is required.</strong></span>
                    @if($errors->has('name'))
                    <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="city_id" class="col-2">
                    <h4>City Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="city_id" id="city_id" class="form-control">
                        <option value="" selected disabled>Select the City for This Gate</option>
                        @foreach ( $cities as $city)
                        <option value="{{$city->id}}" @if($city->id == old('city_id')) selected @endif>{{$city->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('city_id'))
                    <span class="text-danger"><strong>{{ $errors->first('city_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3 selectsearch">
                <label for="township_id" class="col-2">
                    <h4>Township Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="township_id[]" id="township_id" class="form-control" multiple>
                        @foreach ($townships as $township)
                        <option value="{{$township->id}}" @if($township->id == old('township_id')) selected @endif>
                            {{$township->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('township_id'))
                    <span class="text-danger"><strong>{{ $errors->first('township_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="address" class="col-2">
                    <h4>Address <b>:</b></h4>
                </label>
                <div class="col-10">
                    <textarea id="address" name="address" class="form-control required"
                        style="height: 100px">{{old('address')}}</textarea>
                    <span class="text-danger d-none"><strong>Address field is required.</strong></span>
                    @if($errors->has('address'))
                    <span class="text-danger"><strong>{{ $errors->first('address') }}</strong></span>
                    @endif
                </div>
            </div>
            
            <div class="footer-button float-end">
                <a href="{{route('gates.index')}}" class="btn btn-light">Cancel</a>
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
        $('#township_id').select2({multiple: true, width: '100%'});
        $(".select2-selection").on("focus", function () {
            if (!$(this).parent().parent().prev().prop("multiple")) {
                $(this).parent().parent().prev().select2("open");
            }
        });
        
        // Get all input and select elements on the page, including input elements with type="date"
        var inputAndSelectElements = document.querySelectorAll('input, select, textarea, input[type="date"]');

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