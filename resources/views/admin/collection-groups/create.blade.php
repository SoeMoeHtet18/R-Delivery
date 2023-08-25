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

    #add-card-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 24px;
        border: none;
        cursor: pointer;
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
                    <input type="text" id="collection_group_code" name="collection_group_code"
                        class="form-control" readonly />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount" class="form-control"
                    value="{{old('total_amount')}}"/>
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
                    <?php
                        // Get tomorrow's date
                        $today = date('Y-m-d');
                        // Set the default value to tomorrow's date
                        $defaultDate = old('schedule_date') ?? $today;
                        ?>
                        <input type="date" id="assigned_date" name="assigned_date"
                            value="<?php echo $defaultDate; ?>" class="form-control" />
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
                                <option value="{{$shop->id}}" @if($shop->id == old('shop_id')) selected
                                @endif>{{$shop->name}}</option>
                                @endforeach
                                @if ($errors->has('shop_id'))
                                <span class="text-danger"><strong>{{ $errors->first('shop_id') }}</strong></span>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <label for="total_amount">
                            <h4>Total Amount</h4>
                        </label>
                        <div>
                            <input type="text" name="total_amount[]" id="total_amount" class="form-control">
                            @if ($errors->has('total_amount'))
                            <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row m-0 mb-3">
                    <div class="col">
                        <div class="d-flex align-items-center mb-3">
                            <label for="description">
                                <h4>Description</h4>
                            </label>
                            <div style="position: relative; margin-left: 10px;">
                                <input type="text" name="datefilter" value="" class="form-control
                                    description-date-filter" id="description_filter_1"/>
                                <span class="fa fa-calendar calendar_1"
                                    style="position: absolute; top: 12px; right: 8px;"></span>
                            </div>
                        </div>
                       
                        <div>
                            <textarea name="description[]" id="description" class="form-control" style="height: 200px;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
        $('#description_filter_1').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        $(".calendar_1").on("click", function() {
            $('#description_filter_1').trigger("click");
        });
        $('#description_filter_1').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
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
                                <option value="{{$shop->id}}" @if($shop->id == old('shop_id')) selected
                                @endif>{{$shop->name}}</option>
                                @endforeach
                                @if ($errors->has('shop_id'))
                                <span class="text-danger"><strong>{{ $errors->first('shop_id') }}</strong></span>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <label for="total_amount_${newIndex}">
                            <h4>Total Amount</h4>
                        </label>
                        <div>
                            <input type="text" name="total_amount[]" id="total_amount_${newIndex}" class="form-control">
                            @if ($errors->has('total_amount'))
                            <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row m-0 mb-3">
                    <div class="col">
                    <div class="d-flex align-items-center mb-3">
                            <label for="description_${newIndex}">
                                <h4>Description</h4>
                            </label>
                            <div style="position: relative; margin-left: 10px;">
                                <input type="text" name="datefilter" value="" class="form-control
                                    description-date-filter" id="description_filter_${newIndex}"/>
                                <span class="fa fa-calendar calendar_${newIndex}"
                                    style="position: absolute; top: 12px; right: 8px;"></span>
                            </div>
                        </div>
                        <div>
                            <textarea name="description[]" id="description_${newIndex}"
                                class="form-control" style="height: 200px;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`
    };

    var addMoreCategory = () => {
        $("#add-card-btn").click(function() {
            $("#assign-container").append(appendCategory());

            // Initialize select2 for the newly cloned card
            // Get the index of the last cloned card

            const newIndex = $("#assign-container .shop-card-container").length;
            $(`#shop_id_${newIndex}`).select2();
            $(`#description_filter_${newIndex}`).daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
            $(`.calendar_${newIndex}`).on("click", function() {
                $(`#description_filter_${newIndex}`).trigger("click");
            });
            $(`#description_filter_${newIndex}`).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    }
    addMoreCategory();

    $(document).ready(function() {
        var shopDropdownValues = [];
        var checkValues = [];
        $('body').on('change', '.shop-dropdown', function(e) {
            var selectedValue = $(this).val();
            var dropdownIndex = $('.shop-dropdown').index(this);

            if (dropdownIndex == 0) {
                var shopDiv = $('div#assign-container > div:eq(0)');
                if (shopDiv.attr('id') == null) {
                    shopDiv.attr('id', `shop-card-1`);
                }
            }
            const index = $("#assign-container .shop-card-container").length;
            // shopDropdownValues[dropdownIndex] = selectedValue;

            descriptionIndex = collectionIndexForCheck = dropdownIndex + 1;
            $(`#description_filter_${descriptionIndex}`).val('');
            shop_id_to_check = $(`#collections-for-${collectionIndexForCheck} #check-shop-id`).val();

            if (shop_id_to_check != selectedValue) {
                $(`#collections-for-${collectionIndexForCheck}`).empty();
            }
            
            data = {
                shop_id: selectedValue,
                new_index: index
            };
            
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
                        if (shop_id_to_check == null) {
                            $("#assign-container").append(datas);
                        }
                        else if (shop_id_to_check != selectedValue) {
                            $(`#collections-for-${collectionIndexForCheck}`).append(datas);
                        } else {
                            $("#assign-container").append(datas);
                        }
                    } else {
                        $("#assign-container").html("");
                    }
                }
            });

            if (dropdownIndex == 0) {
                $('#description').attr('id', 'description_1');
            }

            // Create a new Date object representing the current date and time
            const currentDate = new Date();

            // Extract day, month, and year components
            const day = currentDate.getDate();
            const month = currentDate.getMonth() + 1; // Add 1 to the month index
            const year = currentDate.getFullYear();

            // Create a formatted date string
            const formattedDate = `${day}/${month}/${year}`;

            $.ajax({
                url: '/get-description-for-shop',
                type: 'GET',
                data: {
                    shop_id: selectedValue,
                    from_date: formattedDate,
                    to_date: formattedDate
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
            var description = $(card).find('textarea[name="description[]"]').val();

            if(shop_id) {
                var cardData = {
                    shop_id: shop_id,
                    total_amount: total_amount,
                    description: description
                };
                assignContainerData.push(cardData);
            }
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
        // for date range picker
        $('body').on('click', '.description-date-filter', function(e) {
            var dropdownIndex = $('.description-date-filter').index(this);
            var descriptionIndex = dropdownIndex + 1;

            $(`#description_filter_${descriptionIndex}`).on('apply.daterangepicker', function(ev, picker) {
                if(dropdownIndex == 0) {
                    var selectedShopValue = $('#shop_id').val();
                } else {
                    var selectedShopValue = $(`#shop_id_${descriptionIndex}`).val();
                }

                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
               
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/get-description-for-shop',
                    type: 'GET',
                    data: {
                        shop_id: selectedShopValue,
                        from_date: picker.startDate.format('DD/MM/YYYY'),
                        to_date: picker.endDate.format('DD/MM/YYYY')
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.data != '') {
                            $(`#description_${descriptionIndex}`).val(res.data);
                        } else {
                            $(`#description_${descriptionIndex}`).val('');
                        }
                    }
                })
            });
        });
        
</script>
@endsection