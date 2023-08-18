@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Rider')
@section('more-sub-title')
<li class="page-sub-title">Assign Third Party Vendor To Rider</li>
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
<form action="{{url('/riders/'.$rider->id.'/assign-third-party-vendor')}}" method="POST" class="action-form">
    @csrf
    @method('PUT')
    <div class="card card-container action-form-card">
        <div class="card-body">
            <h2 class="ps-1 card-header-title">
                <strong>Assign Third Party Vendor For Rider</strong>
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
                    <label for="third_party_vendor_id" class="col-2">
                        <h4>Third Party Vendor Name <b>:</b></h4>
                    </label>
                    <div class="col-10">
                        <select name="third_party_vendor_id[]" id="third_party_vendor_id" class="form-control third_party_vendor_id">
                            <option value="" selected disabled>Select Third Party Vendor for This Rider</option>
                            @foreach ( $thirdPartyVendors as $thirdPartyVendor)
                            <option value="{{$thirdPartyVendor->id}}">{{$thirdPartyVendor->name}}</option>
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
        // Initialize select2 for the initial card
        $('#third_party_vendor_id').select2();
    });

    var clonecard = () => {
        const newIndex = $("#assign-container .card-container").length + 1; // Increment the index for the new card

        return `<div class="card card-container action-form-card">
            <div class="card-body">
                <div class="row m-0 mb-3">
                    <label for="third_party_vendor_id_${newIndex}" class="col-2">
                        <h4>Third Party Vendor Name <b>:</b></h4>
                    </label>
                    <div class="col-10">
                        <select name="third_party_vendor_id[]" id="third_party_vendor_id_${newIndex}" class="form-control third_party_vendor_id">
                            <option value="" selected disabled>Select Third Party Vendor for This Rider</option>
                            @foreach ($thirdPartyVendors as $thirdPartyVendor)
                                <option value="{{$thirdPartyVendor->id}}">{{$thirdPartyVendor->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row m-0 mb-3">
                    <label for="rider_fees_${newIndex}" class="col-2">
                        <h4>Rider Fees <b>:</b></h4>
                    </label>
                    <div class="col-10">
                        <input type="text" name="rider_fees[]" id="rider_fees_${newIndex}" class="form-control">
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
            $(`#third_party_vendor_id_${newIndex}`).select2();
        });
    }

    addMoreCard();
</script>

@endsection