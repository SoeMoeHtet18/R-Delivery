@extends('admin.layouts.master')
@section('title','Payment')
@section('sub-title','Payment From Company Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Create New Payment From Company</strong>
        </h2>
        <form action="{{route('transactions-for-shop.store')}}" method="POST"
            class="action-form" enctype="multipart/form-data">
            @csrf
            <div class="row m-0 mb-3">
                <label for="shop" class="col-2">
                    <h4>Shop Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="shop_id" id="shop_name" class="form-control">
                        <option value="" selected disabled>Select the shop of this payment</option>
                        @foreach($shops as $shop)
                        <option value="{{$shop->id}}" @isset($shop_id) @if($shop->id == $shop_id) selected
                            @endif
                            @else
                            @if($shop->id == old('shop_id')) selected
                            @endif
                            @endisset>{{$shop->name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('shop_id'))
                    <span class="text-danger"><strong>{{ $errors->first('shop_id') }}</strong></span>
                    @endif
                </div>
            </div>
            @isset($order_ids)
            @foreach($order_ids as $orderId)
                <input type="hidden" name="order_ids[]" value="{{ $orderId }}">
            @endforeach
            @endisset
            <div class="row m-0 mb-3">
                <label for="amount" class="col-2">
                    <h4>Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="amount" name="amount" value="@isset($actual_amount)
                        {{ $actual_amount }}@else{{ old('amount') }}@endisset" class="form-control required" />
                    <span id="err-txt" class="text-danger d-none">
                        <strong>Amount is required.</strong>
                    </span>
                    @if($errors->has('amount'))
                    <span class="text-danger"><strong>{{ $errors->first('amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="image" class="col-2">
                    <h4>Image <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="file" id="image" name="image" class="form-control" />
                    @if ($errors->has('image'))
                    <span class="text-danger"><strong>{{ $errors->first('image') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="type" class="col-2">
                    <h4>Type <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="type" id="type" class="form-control">
                        <option value="" selected disabled>Select the Type for This Payment</option>
                        <option value="fully_payment" @if(old('type')=='fully_payment' ) selected @endif>Fully Payment</option>
                        <option value="loan_payment" @if(old('type')=='loan_payment' ) selected @endif>Loan Payment</option>
                    </select>
                    @if ($errors->has('type'))
                    <span class="text-danger"><strong>{{ $errors->first('type') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="paid_by" class="col-2">
                    <h4>Paid By <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="paid_by" id="paid_by" class="form-control">
                        <option value="" selected disabled>Select the user of this payment</option>
                        @foreach($users as $user)
                        <option value="{{$user->id}}" @if($user->id == old('paid_by')) selected @endif>{{$user->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('paid_by'))
                    <span class="text-danger"><strong>{{ $errors->first('paid_by') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="description" class="col-2">
                    <h4>Description <b>:</b></h4>
                </label>
                <div class="col-10">
                    <textarea name="description" id="description" class="form-control"
                        style="height: 100px" placeholder="Write description here">{{old('description')}}</textarea>
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{url()->previous()}}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        $('#shop_name').select2({width: '100%'});
        $('#type').select2({width: '100%'});
        $('#paid_by').select2({width: '100%'});
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