@extends('admin.layouts.master')
@section('title','Collections')
@section('sub-title','Pick Up Group Create')
@section('content')
<style>
    /* Assuming .plus-btn is the direct child of .generat_sku */
    .generat_sku>.plus-btn {
        background-color: #000000;
        padding: 10px;
        color: white;
        border-radius: 20px;
        font-size: 23px;
        float: right;
    }

    #assign-container .action-form-card label h4 {
        font-size: 16px;
        font-weight: bold;
    }

    .footer-button-wrapper {
        clear: both;
    }

    .footer-button {
        display: block;
    }
</style>
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Pick Up Group</strong>
        </h2>
        <form action="{{route('collection-groups.store')}}" method="POST" class="action-form" id="collectionForm">
            @csrf
            <div class="row m-0 mb-3">
                <label for="collection_group_code" class="col-2">
                    <h4>Pick Up Group Code <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="collection_group_code" name="collection_group_code" class="form-control" readonly />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount" class="form-control" />
                    @if ($errors->has('total_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="rider_id" class="col-2">
                    <h4>Rider <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="rider_id" id="rider_id" class="form-control">
                        <option value="" selected disabled>Select the Rider for This Collection</option>
                        @foreach ( $riders as $rider)
                        <option value="{{$rider->id}}" @if($rider->id == old('rider_id')) selected
                            @endif
                            >{{$rider->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('rider_id'))
                    <span class="text-danger"><strong>{{ $errors->first('rider_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="assigned_date" class="col-2">
                    <h4>Assigned Date <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="date" id="assigned_date" name="assigned_date" class="form-control" />
                    @if ($errors->has('assigned_date'))
                    <span class="text-danger"><strong>{{ $errors->first('assigned_date') }}</strong></span>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<div id="assign-container">
    <div>
        <div class="card card-container shop-card-container action-form-card">
            <div class="card-body">
                <div class="row m-0 mb-3">
                    <div class="col">
                        <label for="shop_id">
                            <h4>Shop</h4>
                        </label>
                        <div>
                            <select name="shop_id[]" id="shop_id" class="form-control shop-dropdown">
                                <option value="" selected disabled>Select Shop</option>
                                @foreach($shops as $shop)
                                <option value="{{$shop->id}}">{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <label for="total_amount">
                            <h4>Total Amount</h4>
                        </label>
                        <div>
                            <input type="text" name="total_amount[]" id="total_amount" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row m-0 mb-3">
                    <div class="col">
                        <label for="description">
                            <h4>Description</h4>
                        </label>
                        <div>
                            <textarea name="description[]" id="description" class="form-control" style="height: 200px;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="card card-container action-form-card">
    <div class="card-body">
        <div class="row">
            <div class="generat_sku margin10" style="cursor: pointer;"><i id="addMoreCategory" class="fal fa-plus plus-btn" style="cursor: pointer;"></i></div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <label for="shop">
                    <strong>Shop</strong>
                </label>
                <div class="col-10">
                    <select name="shop" id="shop" class="form-control shop-dropdown">
                        <option value="" selected disabled>Select</option>
                        @foreach($shops as $shop)
                        <option value="{{$shop->id}}">{{$shop->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <label><strong>Amount</strong></label>
            <input type="text" id="total_amount" name="total_amount" class="form-control" />
            </div>
        </div>
        <div id="extraHtml">
        </div>
    </div>
</div> -->

<button type="button" id="add-card-btn" class="btn green rounded-pill float-end mb-5">+</button>

<div class="footer-button-wrapper">
    <div class="footer-button float-end">
        <a href="{{url()->previous() }}" class="btn btn-light">Cancel</a>
        <input type="button" class="btn btn-success submit-button" onclick="getCheckedValues()" value="Submit">
    </div>
</div>


@endsection
@section('javascript')
<script type="text/javascript">
    $(function() {
        $('#rider_id').select2();
        $('#shop_id').select2();
        $('#collection_id').select2({
            placeholder: 'Select Collections',
            allowClear: true
        });

        //get collection group code
        $.ajax({
            url: '/get-collection-group-code',
            type: "GET",
            success: function(res) {
                if (res) {
                    $("#collection_group_code").val(res.data);
                }
            }
        });


    });

    var appendCategory = () => {
        const newIndex = $("#assign-container .shop-card-container").length + 1;

        return `<div id="shop-card-${newIndex}"
        <div class="card card-container shop-card-container action-form-card">
            <div class="card-body">
                <div class="row m-0 mb-3">
                    <div class="col">
                        <label for="shop_id_${newIndex}">
                            <h4>Shop</h4>
                        </label>
                        <div>
                            <select name="shop_id[]" id="shop_id_${newIndex}" class="form-control shop-dropdown">
                                <option value="" selected disabled>Select Shop</option>
                                @foreach($shops as $shop)
                                <option value="{{$shop->id}}">{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <label for="total_amount_${newIndex}">
                            <h4>Total Amount</h4>
                        </label>
                        <div>
                            <input type="text" name="total_amount[]" id="total_amount_${newIndex}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row m-0 mb-3">
                    <div class="col">
                        <label for="description_${newIndex}">
                            <h4>Description</h4>
                        </label>
                        <div>
                            <textarea name="description" id="description_${newIndex}" class="form-control" style="height: 200px;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`
    };

    var addMoreCategory = () => {
        $("#add-card-btn").click(function() {
            console.log("add more cards");
            $("#assign-container").append(appendCategory());

            // Initialize select2 for the newly cloned card
            const newIndex = $("#assign-container .shop-card-container").length; // Get the index of the last cloned card
            $(`#shop_id_${newIndex}`).select2();
        });
    }
    addMoreCategory();

    $(document).ready(function() {
        var shopDropdownValues = [];
        var checkValues = [];
        $('body').on('change', '.shop-dropdown', function(e) {
            var selectedValue = $(this).val();
            var dropdownIndex = $('.shop-dropdown').index(this);
            console.log(`dropdownIndex ${dropdownIndex}`);
            if (dropdownIndex == 0) {
                var shopDiv = $('div#assign-container > div:eq(0)');
                if (shopDiv.attr('id') == null) {
                    shopDiv.attr('id', `shop-card-1`);
                }
            }
            const index = $("#assign-container .shop-card-container").length;
            shopDropdownValues[dropdownIndex] = selectedValue;

            collectionIndexForCheck = dropdownIndex + 1;
            shop_id_to_check = $(`#collections-for-${collectionIndexForCheck} #check-shop-id`).val();

            if (shop_id_to_check != selectedValue) {
                $(`#collections-for-${collectionIndexForCheck}`).remove();
            }
            data = {
                shop_id: selectedValue,
                new_index: index
            };
            console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: '/get-collections-by-shop',
                type: "GET",
                data: data,
                success: function(datas) {
                    if (datas) {
                        $("#assign-container").append(datas);
                    } else {
                        $("#assign-container").html("");
                    }
                }
            });

            if (dropdownIndex == 0) {
                $('#description').attr('id', 'description_1');
            }

            var descriptionIndex = dropdownIndex + 1;

            $.ajax({
                url: '/get-description-for-shop',
                type: 'GET',
                data: {
                    shop_id: selectedValue
                },
                success: function(res) {
                    if (res.data != '') {
                        $(`#description_${descriptionIndex}`).val(res.data);
                    } else {
                        $(`#description_${descriptionIndex}`).val('');
                    }
                }
            })
        });
    });

    function getAssignContainerData() {
        var assignContainerData = [];
        $('.shop-card-container').each(function(index, card) {
            var shop_id = $(card).find('select.shop-dropdown').val();
            var total_amount = $(card).find('input[name="total_amount[]"]').val();
            var description = $(card).find('textarea[name="description"]').val();

            var cardData = {
                shop_id: shop_id,
                total_amount: total_amount,
                description: description
            };

            assignContainerData.push(cardData);
        });

        return assignContainerData;
    }

    function getCheckedValues() {
        // Get the checked collection_checkbox values
        var shopCollectionCheckboxes = document.getElementsByName("collection_checkbox[]");
        var checkedShopCollectionValues = [];
        for (var i = 0; i < shopCollectionCheckboxes.length; i++) {
            if (shopCollectionCheckboxes[i].checked) {
                checkedShopCollectionValues.push(shopCollectionCheckboxes[i].value);
            }
        }
        checkedShopCollectionValues = [...new Set(checkedShopCollectionValues)];
        console.log(checkedShopCollectionValues);

        //Get the checked customer_collection_checkbox values
        var customerCollectionCheckboxes = document.getElementsByName("customer_collection_checkbox[]");
        var checkedCustomerCollectionValues = [];
        for (var j = 0; j < customerCollectionCheckboxes.length; j++) {
            if (customerCollectionCheckboxes[j].checked) {
                checkedCustomerCollectionValues.push(customerCollectionCheckboxes[j].value);
            }
        }
        checkedCustomerCollectionValues = [...new Set(checkedCustomerCollectionValues)];


        // // Add the checked values to the form as hidden fields before submitting
        var form = document.getElementById("collectionForm");
        var shopCollectionInput = document.createElement("input");
        shopCollectionInput.type = "hidden";
        shopCollectionInput.name = "checked_shop_collections";
        shopCollectionInput.value = JSON.stringify(checkedShopCollectionValues);
        form.appendChild(shopCollectionInput);


        var customerCollectionInput = document.createElement("input");
        customerCollectionInput.type = "hidden";
        customerCollectionInput.name = "checked_customer_collections";
        customerCollectionInput.value = JSON.stringify(checkedCustomerCollectionValues);
        form.appendChild(customerCollectionInput);

        var assignContainerData = getAssignContainerData();
        var assignContainerInput = document.createElement("input");
        assignContainerInput.type = "hidden";
        assignContainerInput.name = "create-collections-data";
        assignContainerInput.value = JSON.stringify(assignContainerData);
        form.appendChild(assignContainerInput);
        
        // // Submit the form
        form.submit();
    }
</script>
@endsection