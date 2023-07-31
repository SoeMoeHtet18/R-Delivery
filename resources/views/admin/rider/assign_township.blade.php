@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Rider')
@section('more-sub-title')
<li class="page-sub-title">Assign Township</li>
@endsection
@section('content')
<form action="{{url('/riders/'.$rider->id.'/assign-township')}}" method="POST" class="action-form">
    @csrf
    @method('PUT')
    <div class="card card-container action-form-card">
        <div class="card-body">
            <h2 class="ps-1 card-header-title">
                <strong>Assign Township For Rider</strong>
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
    <button type="button" id="add-card-btn" class="btn btn-primary">Add New Card</button>
    <div id="assign-container">
        <div class="card card-container action-form-card">
            <div class="card-body">
                <div class="row m-0 mb-3">
                    <label for="township_id" class="col-2">
                        <h4>Township Name <b>:</b></h4>
                    </label>
                    <div class="col-10">
                        <select name="township_id[]" id="township_id" class="form-control township_id">
                            <option value="" selected disabled>Select the Township for This Order</option>
                            @foreach ( $townships as $township)
                            <option value="{{$township->id}}">{{$township->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row m-0 mb-3">
                    <label for="rider_fees" class="col-2">
                        <h4>Rider Fees <b>:</b></h4>
                    </label>
                    <div class="col-10">
                        <input type="text" name="rider_fees[]" id="rider_fees" class="form-control">
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
        $('.township_id').select2();
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Get the button and container elements
        const addButton = document.getElementById("add-card-btn");
        const assignContainer = document.getElementById("assign-container");

        // Function to clone the card and update attributes
        function cloneCard() {
            // Clone the card element
            const clonedCard = assignContainer.firstElementChild.cloneNode(true);

            // Increment the index for the new card
            const newIndex = assignContainer.childElementCount + 1;

            // Update the IDs and names of the cloned elements
            clonedCard.querySelectorAll("[id], [name]").forEach(element => {
                const currentId = element.getAttribute("id");
                const currentName = element.getAttribute("name");
                if (currentId) {
                    element.setAttribute("id", currentId + "-" + newIndex);
                }
                if (currentName) {
                    element.setAttribute("name", currentName + "[]");
                }
            });

            // Append the cloned card to the container
            assignContainer.appendChild(clonedCard);
        }

        // Add event listener to the button
        addButton.addEventListener("click", cloneCard);
    });
</script>
@endsection