@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Rider')
@section('more-sub-title')
<li class="page-sub-title">Assign Branch To Rider</li>
@endsection
@section('style')
<style>
    .action-form-card label h4 {
        font-size: 16px;
    }

    .action-form-card .row {
        align-items: center;
    }
</style>
@endsection
@section('content')
<form action="{{url('/riders/'.$rider->id.'/assign-branch')}}" method="POST" class="action-form">
    @csrf
    @method('PUT')
    <div class="card card-container action-form-card">
        <div class="card-body">
            <h2 class="ps-1 card-header-title">
                <strong>Assign Branch For Rider</strong>
            </h2>

            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" value="{{$rider->name}}" class="form-control" readonly />
                </div>
            </div>
        </div>
    </div>
    <button type="button" id="add-card-btn" class="btn green rounded-pill">+</button>
    <div id="assign-container">
        <div class="card card-container action-form-card">
            <div class="card-body">
                <div class="row m-0 mb-3">
                    <label for="branch_id" class="col-2">
                        <h4>Branch Name <b>:</b></h4>
                    </label>
                    <div class="col-10">
                        <select name="branch_id[]" id="branch_id" class="form-control branch_id required">
                            <option value="" selected disabled>Select the Branch for This Rider</option>
                            @foreach ( $branches as $branch)
                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                            @endforeach
                        </select>
                        <span id="err-txt" class="text-danger d-none">
                            <strong>Branch field is required.</strong>
                        </span>
                    </div>
                </div>
                <div class="row m-0 mb-3">
                    <label for="rider_fees" class="col-2">
                        <h4>Rider Fees <b>:</b></h4>
                    </label>
                    <div class="col-10">
                        <input type="text" name="rider_fees[]" id="rider_fees" class="form-control required">
                        <span id="err-txt" class="text-danger d-none">
                            <strong>Rider Fees is required.</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" id="id" name="id" value="{{$rider->id}}" class="form-control" />
    <div class="footer-button float-end">
        <a href="{{url()->previous() }}" class="btn btn-light">Cancel</a>
        <input type="submit" class="btn btn-success ">
    </div>
</form>

@endsection
@section('javascript')
<script type="text/javascript">
    $(function() {
        // Initialize select2 for the initial card
        $('#branch_id').select2({width: '100%'});
        $(".select2-selection").on("focus", function () {
            $(this).parent().parent().prev().select2("open");
        });
    });

    var clonecard = () => {
        const newIndex = $("#assign-container .card-container").length + 1; // Increment the index for the new card

        return `<div class="card card-container action-form-card">
            <div class="card-body">
                <div class="row m-0 mb-3">
                    <label for="branch_id_${newIndex}" class="col-2">
                        <h4>Branch Name <b>:</b></h4>
                    </label>
                    <div class="col-10">
                        <select name="branch_id[]" id="branch_id_${newIndex}" class="form-control branch_id required">
                            <option value="" selected disabled>Select the Branch for This Rider</option>
                            @foreach ($branches as $branch)
                                <option value="{{$branch->id}}">{{$branch->name}}</option>
                            @endforeach
                        </select>
                        <span id="err-txt" class="text-danger d-none">
                            strong>Branch Field is required.</strong>
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
                            <strong>Rider Fees is required.</strong>
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
            $(`#branch_id_${newIndex}`).select2();
            $(".select2-selection").on("focus", function () {
                $(this).parent().parent().prev().select2("open");
            });
            errFieldHandling();
        });
    }

    addMoreCard();
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