<style>
    /* .card-wrapper {
        display: flex;
        margin-bottom: 10px;
        border-radius: 20px;
    } */

    .card-checkbox {
        margin: 0 15px;
    }

    .card {
        /* flex: 1; Remove this line */
        display: flex;
        border: none;
        padding: 5px;
        border-radius: 20px;
        margin-right: 10px;
    }

    .collection-name {
        margin-bottom: 20px;
    }

    .collection-name:not(:first-child) {
        margin-top: 50px;
    }

    .card-wrapper {
        flex: 1;
        /* Add this line */
        display: flex;
        /* Add this line */
        flex-direction: column;
        /* Add this line if you want the card-wrapper to stack its children vertically */
    }

    .collection-name h5 {
        font-weight: bold;
    }
    
</style>

<div id='collections-for-{{$new_index}}'>
    <input type="hidden" id="check-shop-id" value="{{$shop->id}}" />
    <div class="card card-container action-form-card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    @if(isset($shop_collections))
                    <div class="collection-name">
                        <h5>Pick Ups For {{$shop->name}}</h5>
                    </div>
                    @foreach($shop_collections as $shop_collection)
                    <div class="d-flex align-items-center mb-4">
                        <input type="checkbox" class="card-checkbox" name="collection_checkbox[]" value="{{$shop_collection->id}}">
                        <div class="card-container card-wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row m-0 mb-3">
                                        <div class="col-4">
                                            <label><strong>Total Quantity</strong><b>:</b></label>
                                        </div>
                                        <div class="col-8">
                                            {{ $shop_collection->total_quantity }}
                                        </div>
                                    </div>
                                    <div class="row m-0 mb-3">
                                        <div class="col-4">
                                            <label><strong>Paid Amount To Shop</strong><b>:</b></label>
                                        </div>
                                        <div class="col-8">
                                            {{ $shop_collection->paid_amount }}
                                        </div>
                                    </div>
                                    <div class="row m-0">
                                        <div class="col-4">
                                            <label><strong>Note</strong><b>:</b></label>
                                        </div>
                                        <div class="col-8">
                                            {{ $shop_collection->note }}
                                        </div>
                                    </div>
                                    <!-- Add other card information here if needed -->
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
                <div class="col">
                    @if(isset($customer_collections))
                    <div class="collection-name">
                        <h5>Customer Exchanges For {{$shop->name}}</h5>
                    </div>
                    @foreach($customer_collections as $customer_collection)
                    <div class="d-flex align-items-center mb-4">
                        <input type="checkbox" class="card-checkbox" name="customer_collection_checkbox[]" value="{{$customer_collection->id}}">
                        <div class="card-container card-wrapper">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row m-0 mb-3">
                                        <div class="col-4">
                                            <label><strong>Items</strong><b>:</b></label>
                                        </div>
                                        <div class="col-8">
                                            {{ $customer_collection->items }}
                                        </div>
                                    </div>
                                    <div class="row m-0 mb-3">
                                        <div class="col-4">
                                            <label><strong>Paid Amount To Customer</strong><b>:</b></label>
                                        </div>
                                        <div class="col-8">
                                            {{ $customer_collection->paid_amount }}
                                        </div>
                                    </div>
                                    <div class="row m-0">
                                        <div class="col-4">
                                            <label><strong>Note</strong><b>:</b></label>
                                        </div>
                                        <div class="col-8">
                                            {{ $customer_collection->note }}
                                        </div>
                                    </div>
                                    <!-- Add other card information here if needed -->
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>