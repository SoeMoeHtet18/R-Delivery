<!-- Assuming you have the $shop_collections array available -->

<!-- Add this CSS to style the checkboxes and cards -->
<style>
    .card-wrapper {
        display: flex;
        margin-bottom: 10px;
        max-width: 500px;
        border-radius: 20px;
    }

    .card-checkbox {
        margin: 0 15px;
    }

    .card {
        flex: 1;
        display: flex;
        border: none;
        padding: 5px;
        border-radius: 20px;
        margin-right: 10px;
    }
</style>

<div>
    <h5>Shop Collections</h5>
</div>
@foreach($shop_collections as $shop_collection)
<div class="card-container card-wrapper">
    <input type="checkbox" class="card-checkbox" name="collection_checkbox[]" value="{{$shop_collection->id}}">
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
                    <label><strong>Paid Amount To Customer</strong><b>:</b></label>
                </div>
                <div class="col-8">
                    {{ $shop_collection->paid_amount }}
                </div>
            </div>
            <div class="row m-0 mb-3">
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
@endforeach

<div>
    <h5>Customer Collections</h5>
</div>
@foreach($customer_collections as $customer_collection)
<div class="card-container card-wrapper">
    <input type="checkbox" class="card-checkbox" name="collection_checkbox[]" value="{{$shop_collection->id}}">
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
            <div class="row m-0 mb-3">
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
@endforeach
