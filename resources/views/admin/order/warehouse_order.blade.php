<style>
    /* .card-wrapper {
        display: flex;
        margin-bottom: 10px;
        border-radius: 20px;
    } */

    .card-checkbox {
        margin: 0 15px;
    }

    .warehouse_order_card {
        /* flex: 1; Remove this line */
        display: flex;
        border: none;
        padding: 5px;
        border-radius: 20px;
        /* margin: auto !important; */
        max-width: 50%;
    }

    .collection-name {
        margin-bottom: 20px;
    }

    .collection-name:not(:first-child) {
        margin-top: 50px;
    }

    .card-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .collection-name h5 {
        font-weight: bold;
    }

    .card-header {
        display: flex;
    }

    .action-button {
        display: flex;
    }
    
</style>
    <div class="card card-container action-form-card warehouse_order_card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="card-header">
                        <h5 class="mb-3 me-5"><strong>Order ID {{$order->order_code}}</strong></h5>
                        <div class="action-button ms-5">
                            <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post">
                                @csrf
                                @method('POST')
                                <input type="hidden" value="delay" name="status">
                                <input type="submit" value="Cancel" class="btn btn-danger p-2">
                            </form>
                            <a href="{{url('/orders/'.$order->id.'/assign-rider')}}" class="btn btn-secondary ms-3">Assign Rider</a>
                        </div>
                    </div>
                    <div class="row m-0 mb-3 mt-3">
                        <div class="col-4">
                            <label><strong>Total Amount</strong><b>:</b></label>
                        </div>
                        <div class="col-8">
                            {{ $order->total_amount }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-4">
                            <label><strong>Delivery Fees</strong><b>:</b></label>
                        </div>
                        <div class="col-8">
                            {{ $order->delivery_fees }}
                        </div>
                    </div>                    
                    <div class="row m-0 mb-3">
                        <div class="col-4">
                            <label><strong>Markup Delivery Fees</strong><b>:</b></label>
                        </div>
                        <div class="col-8">
                            {{ $order->markup_delivery_fees }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-4">
                            <label><strong>Shop Name</strong><b>:</b></label>
                        </div>
                        <div class="col-8">
                            {{ $order->shop->name }}
                        </div>
                    </div>                    
                    <div class="row m-0 mb-3">
                        <div class="col-4">
                            <label><strong>Rider Name</strong><b>:</b></label>
                        </div>
                        <div class="col-8">
                            {{ $order->rider->name }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-4">
                            <label><strong>Customer Name</strong><b>:</b></label>
                        </div>
                        <div class="col-8">
                            {{ $order->customer_name }}
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>