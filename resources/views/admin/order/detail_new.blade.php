@extends('admin.layouts.master_new')
@section('content')
<style>
    .page-content,
    .page-content::before {
        background: white !important;
    }

    .left-content {
        flex: 1;
    }

    .right-content {
        flex: 2;
        margin-left: 40px;
    }

    .order-detail-card {
        position: relative;
        border-radius: 10px !important;
        background: #F1F5F5;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    }

    .yes-btn {
        background-color: green;
        color: white;
    }

    .no-btn {
        background-color: red;
        color: white;
    }

    .dropdown > .dropdown-menu:before {
        border-bottom: none;
        border-left: none;
    }

    .dropdown > .dropdown-menu:after {
        border-bottom: none;
    }

    .edit-info {
        position: absolute;
        right: 10px;
        top: 10px;
    }

    .save-info {
        position: absolute;
        right: 16px;
        top: 10px;
    }

    .cancel-info {
        position: absolute;
        right: 37px;
        top: 10px;
    }

    .status-card {
        text-align: center;
        color: white;
        padding: 10px 0 5px 0;
    }

    #edit-status {
        font-size: 12px;
        right: 7px;
        top: 4px;
    }
</style>
<div>
    <div class="d-flex align-items-center">
        <h4 class="m-0 me-3">
            <strong>Order ID: {{$order->order_code}}</strong>
        </h4>
        <div class="rounded-pill btn btn-{{$order->statusLabelColor}} status-box text-white">
            {{ $order->statusLabel }}
        </div>
    </div>
    <hr>
    
    <div class="card-toolbar">
        <div class="dropdown">
            <!-- <a href="{{url('/tracking?order_id='.$order->order_code)}}" target="_blank" class="me-2"> -->
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"
            id="order-tracking" class="me-2">
                <g id="share">
                    <rect id="Rectangle 74" width="30" height="30" rx="5" fill="#116A5B"/>
                    <path id="Vector" d="M20.8284 17.8543C20.2727 17.8541 19.7225 17.9496 19.2106 18.1349C18.6986 18.3203 18.2353 18.5919 17.8479 18.9336L13.0471 16.2884C13.4247 15.4583 13.4247 14.5368 13.0471 13.7066L17.8479 11.0615C18.5691 11.6947 19.5388 12.0758 20.5691 12.131C21.5994 12.1861 22.6169 11.9114 23.4243 11.36C24.2317 10.8085 24.7715 10.0198 24.9391 9.14656C25.1066 8.27336 24.89 7.37792 24.3311 6.63387C23.7723 5.88982 22.911 5.35016 21.9144 5.11952C20.9178 4.88887 19.8567 4.98368 18.937 5.38555C18.0173 5.78743 17.3044 6.46774 16.9365 7.2946C16.5687 8.12145 16.5721 9.03594 16.9461 9.86076L12.1452 12.5059C11.5671 11.9971 10.8251 11.6477 10.0141 11.5023C9.20314 11.3568 8.35998 11.422 7.59244 11.6895C6.8249 11.957 6.16784 12.4146 5.70526 13.0039C5.24269 13.5931 4.99561 14.2873 4.99561 14.9975C4.99561 15.7078 5.24269 16.4019 5.70526 16.9912C6.16784 17.5804 6.8249 18.038 7.59244 18.3055C8.35998 18.573 9.20314 18.6382 10.0141 18.4928C10.8251 18.3474 11.5671 17.9979 12.1452 17.4891L16.9461 20.1343C16.6245 20.8454 16.5769 21.626 16.8103 22.362C17.0437 23.0979 17.5458 23.7505 18.2432 24.2241C18.9405 24.6977 19.7964 24.9675 20.6855 24.9939C21.5747 25.0204 22.4503 24.8021 23.1843 24.3711C23.9183 23.94 24.4719 23.3189 24.7643 22.5986C25.0566 21.8783 25.0723 21.0966 24.8089 20.3682C24.5456 19.6397 24.0171 19.0026 23.3008 18.5502C22.5845 18.0979 21.7181 17.854 20.8284 17.8543ZM20.8284 6.42732C21.3228 6.42732 21.806 6.55298 22.217 6.78841C22.628 7.02383 22.9484 7.35845 23.1376 7.74995C23.3267 8.14145 23.3762 8.57225 23.2798 8.98786C23.1833 9.40348 22.9453 9.78524 22.5958 10.0849C22.2462 10.3845 21.8009 10.5886 21.316 10.6713C20.8312 10.7539 20.3287 10.7115 19.872 10.5493C19.4153 10.3872 19.0249 10.1125 18.7503 9.76021C18.4757 9.40787 18.3291 8.99363 18.3291 8.56987C18.3291 8.00163 18.5924 7.45667 19.0611 7.05486C19.5298 6.65305 20.1656 6.42732 20.8284 6.42732ZM9.16472 17.1401C8.6704 17.1401 8.18717 17.0144 7.77615 16.779C7.36513 16.5436 7.04478 16.2089 6.85561 15.8174C6.66644 15.4259 6.61694 14.9951 6.71338 14.5795C6.80982 14.1639 7.04786 13.7822 7.3974 13.4825C7.74695 13.1829 8.19229 12.9788 8.67712 12.8961C9.16195 12.8135 9.66449 12.8559 10.1212 13.0181C10.5779 13.1802 10.9682 13.4548 11.2429 13.8072C11.5175 14.1595 11.6641 14.5738 11.6641 14.9975C11.6641 15.5658 11.4008 16.1107 10.932 16.5125C10.4633 16.9143 9.8276 17.1401 9.16472 17.1401ZM20.8284 23.5677C20.3341 23.5677 19.8509 23.4421 19.4399 23.2066C19.0288 22.9712 18.7085 22.6366 18.5193 22.2451C18.3302 21.8536 18.2807 21.4228 18.3771 21.0072C18.4735 20.5916 18.7116 20.2098 19.0611 19.9102C19.4107 19.6105 19.856 19.4065 20.3408 19.3238C20.8257 19.2411 21.3282 19.2836 21.7849 19.4457C22.2416 19.6079 22.632 19.8825 22.9066 20.2348C23.1812 20.5872 23.3278 21.0014 23.3278 21.4252C23.3278 21.9934 23.0645 22.5384 22.5958 22.9402C22.127 23.342 21.4913 23.5677 20.8284 23.5677Z" fill="white"/>
                </g>
            </svg>
            <!-- </a> -->

            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"
            id="qr-code-generate" class="me-2">
                <g id="QR">
                    <rect id="Rectangle 71" width="30" height="30" rx="5" fill="#116A5B"/>
                    <path id="Vector" d="M12.2727 5H6.81818C6.33597 5 5.87351 5.19156 5.53253 5.53253C5.19156 5.87351 5 6.33597 5 6.81818V12.2727C5 12.7549 5.19156 13.2174 5.53253 13.5584C5.87351 13.8994 6.33597 14.0909 6.81818 14.0909H12.2727C12.7549 14.0909 13.2174 13.8994 13.5584 13.5584C13.8994 13.2174 14.0909 12.7549 14.0909 12.2727V6.81818C14.0909 6.33597 13.8994 5.87351 13.5584 5.53253C13.2174 5.19156 12.7549 5 12.2727 5ZM12.2727 12.2727H6.81818V6.81818H12.2727V12.2727ZM12.2727 15.9091H6.81818C6.33597 15.9091 5.87351 16.1006 5.53253 16.4416C5.19156 16.7826 5 17.2451 5 17.7273V23.1818C5 23.664 5.19156 24.1265 5.53253 24.4675C5.87351 24.8084 6.33597 25 6.81818 25H12.2727C12.7549 25 13.2174 24.8084 13.5584 24.4675C13.8994 24.1265 14.0909 23.664 14.0909 23.1818V17.7273C14.0909 17.2451 13.8994 16.7826 13.5584 16.4416C13.2174 16.1006 12.7549 15.9091 12.2727 15.9091ZM12.2727 23.1818H6.81818V17.7273H12.2727V23.1818ZM23.1818 5H17.7273C17.2451 5 16.7826 5.19156 16.4416 5.53253C16.1006 5.87351 15.9091 6.33597 15.9091 6.81818V12.2727C15.9091 12.7549 16.1006 13.2174 16.4416 13.5584C16.7826 13.8994 17.2451 14.0909 17.7273 14.0909H23.1818C23.664 14.0909 24.1265 13.8994 24.4675 13.5584C24.8084 13.2174 25 12.7549 25 12.2727V6.81818C25 6.33597 24.8084 5.87351 24.4675 5.53253C24.1265 5.19156 23.664 5 23.1818 5ZM23.1818 12.2727H17.7273V6.81818H23.1818V12.2727ZM15.9091 20.4545V16.8182C15.9091 16.5771 16.0049 16.3458 16.1754 16.1754C16.3458 16.0049 16.5771 15.9091 16.8182 15.9091C17.0593 15.9091 17.2905 16.0049 17.461 16.1754C17.6315 16.3458 17.7273 16.5771 17.7273 16.8182V20.4545C17.7273 20.6957 17.6315 20.9269 17.461 21.0974C17.2905 21.2679 17.0593 21.3636 16.8182 21.3636C16.5771 21.3636 16.3458 21.2679 16.1754 21.0974C16.0049 20.9269 15.9091 20.6957 15.9091 20.4545ZM25 18.6364C25 18.8775 24.9042 19.1087 24.7337 19.2792C24.5632 19.4497 24.332 19.5455 24.0909 19.5455H21.3636V24.0909C21.3636 24.332 21.2679 24.5632 21.0974 24.7337C20.9269 24.9042 20.6957 25 20.4545 25H16.8182C16.5771 25 16.3458 24.9042 16.1754 24.7337C16.0049 24.5632 15.9091 24.332 15.9091 24.0909C15.9091 23.8498 16.0049 23.6186 16.1754 23.4481C16.3458 23.2776 16.5771 23.1818 16.8182 23.1818H19.5455V16.8182C19.5455 16.5771 19.6412 16.3458 19.8117 16.1754C19.9822 16.0049 20.2134 15.9091 20.4545 15.9091C20.6957 15.9091 20.9269 16.0049 21.0974 16.1754C21.2679 16.3458 21.3636 16.5771 21.3636 16.8182V17.7273H24.0909C24.332 17.7273 24.5632 17.8231 24.7337 17.9935C24.9042 18.164 25 18.3953 25 18.6364ZM25 22.2727V24.0909C25 24.332 24.9042 24.5632 24.7337 24.7337C24.5632 24.9042 24.332 25 24.0909 25C23.8498 25 23.6186 24.9042 23.4481 24.7337C23.2776 24.5632 23.1818 24.332 23.1818 24.0909V22.2727C23.1818 22.0316 23.2776 21.8004 23.4481 21.6299C23.6186 21.4594 23.8498 21.3636 24.0909 21.3636C24.332 21.3636 24.5632 21.4594 24.7337 21.6299C24.9042 21.8004 25 22.0316 25 22.2727Z" fill="white"/>
                </g>
            </svg>
            
            <button class="btn btn-secondary green dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Actions
            </button>
            <ul class="dropdown-menu ">
            @if($order->status == 'cancel_request')
            <li>
            <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post" onclick="return confirm(`Are you sure you want to cancel this order?`);">
                @csrf
                @method('POST')
                <input type="hidden" value="cancel" name="status">
                <input type="submit" value="Approve Cancel" class="dropdown-item">
                </form>
            </li>
            <li>
                <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post">
                    @csrf
                    @method('POST')
                    <input type="hidden" value="delay" name="status">
                    <input type="submit" value="Reject Cancel" class="dropdown-item">
                </form>
            </li>
            @endif
                @if($order->status == 'pending')
                <li>
                    <a class="dropdown-item" id="assign-collection-group">Assign To Pick Up Group</a>
                </li>
                @endif
                @if($order->status == 'delay')
                <li>
                    <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post">
                        @csrf
                        @method('POST')
                        <input type="hidden" value="success" name="status">
                        <input type="submit" value="Delivered" class="btn btn-dark dropdown-item">
                    </form>
                </li>
                <li>
                    <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post">
                        @csrf
                        @method('POST')
                        <input type="hidden" value="warehouse" name="status">
                        <input type="submit" value="Assign To Warehouse" class="btn btn-secondary dropdown-item">
                    </form>
                </li>
                @endif
                @if($order->status == 'warehouse' || $order->status == 'success')
                <li>
                    <a href="{{url('/customer-collections/create?order_id='.$order->id)}}" class="dropdown-item">Create Customer Collection</a>
                </li>
                @endif
                @if($order->payment_channel == 'shop_online_payment' && $order->is_payment_channel_confirm == false)
                <li>
                    <a href="{{url('/payment-channel-confirm/'.$order->id)}}" class="btn btn-secondary dropdown-item">Confirm Shop Payment</a>
                </li>
                @endif
                @if($order->payment_channel == 'company_online_payment' && $order->is_payment_channel_confirm == false)
                <li>
                    <a href="{{url('/payment-channel-confirm/'.$order->id)}}" class="btn btn-secondary dropdown-item">Confirm Company Payment</a>
                </li>
                @endif
                @if($order->status == 'pending' || $order->status == 'picking-up' || $order->status == 'cancel')
                <li>
                <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="post">
                        @csrf
                        @method('POST')
                        <input type="hidden" value="warehouse" name="status">
                        <input type="submit" value="Assign To Warehouse" class="btn btn-dark dropdown-item">
                    </form>
                </li>
                @endif
                @if($order->status == 'pending' || $order->status == 'warehouse')
                <li>
                    <a href="{{url('/orders/'.$order->id.'/assign-rider')}}" class="btn btn-secondary dropdown-item">Assign Rider</a>
                </li>
                @endif

                <li><a href="{{route('orders.edit' , $order->id)}}" class="btn btn-light dropdown-item">Edit</a></li>
                <li> 
                    <form action="{{route('orders.destroy', $order->id)}}" method="post" onclick="return confirm(`Are you sure you want to delete this order?`);">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger dropdown-item">
                    </form>
                </li>
        
            </ul>
        </div>
    </div>
    <input type="hidden" name="order_id" value="{{$order->id}}" id="order_id">
    <input type="hidden" name="order_code" value="{{$order->order_code}}" id="order_code">
        
    <div class="d-flex">
        <div class="left-content">
            <div id="order-status-box">
                <div class="card order-detail-card bg-{{$order->statusLabelColor}} status-card">
                    <h4>{{ $order->statusLabel }}</h4>
                    <i class="fa-solid fa-pen d-none edit-info" id="edit-status"></i>
                </div>
                <form action="{{url('/orders/' . $order->id . '/change-status')}}" method="POST" class="d-none"
                    id="status-form">
                    @csrf
                    @method('POST')
                    <select name="status" id="status" class="form-control">
                        <option value="" selected disabled>Select Status</option>
                        <option value="pending" @if($order->status == "pending") {{'selected'}} @endif>Pending</option>
                        <option value="picking-up" @if($order->status == "picking-up") {{'selected'}} @endif>Picking Up</option>
                        <option value="warehouse" @if($order->status == "warehouse") {{'selected'}} @endif>In Warehouse</option>
                        <option value="delivering" @if($order->status == "delivering") {{'selected'}} @endif>Delivering</option>
                        <option value="success" @if($order->status == "success") {{'selected'}} @endif>Delivered</option>
                        <option value="delay" @if($order->status == "delay") {{'selected'}} @endif>Delay</option>
                        <option value="cancel" @if($order->status == "cancel") {{'selected'}} @endif>Cancel</option>
                    </select>
                </form>
            </div>
            <div class="card order-detail-card">
                <div class="card-body ">
                    <div class="text-center">
                        <h5>Cash To Collect</h5>
                        <b>
                        @php
                        $cashToCollect = 0;
                        if ($order->payment_method === 'cash_on_delivery'){
                           $cashToCollect = $order->total_amount + ($order->delivery_fees + $order->markup_delivery_fees + $order->extra_charges) - $order->discount;
                        }
                        if ($order->payment_method === 'item_prepaid'){
                            $cashToCollect = ($order->delivery_fees + $order->markup_delivery_fees + $order->extra_charges) - $order->discount;
                        } 
                        @endphp
                        {{ number_format($cashToCollect, 2, '.', ',') }} MMK
                        </b>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <b>ORDER AMOUNT</b><b>{{ number_format($order->total_amount, 2, '.', ',') }} MMK</b>
                    </div>
                    <div class="d-flex justify-content-between">
                        <b>DELIVERY FEES</b><b> @if($order->discount == null || $order->discount == 0)
                            {{ number_format($order->delivery_fees, 2, '.', ',') }}
                            @else
                            {{ number_format(($order->delivery_fees - $order->discount), 2, '.', ',') }}
                            @endif
                            MMK</b>
                    </div>
                    <div class="d-flex justify-content-between">
                        <b>MARKUP DELIVERY FEES</b><b>{{ number_format($order->markup_delivery_fees, 2, '.', ',') }} MMK</b>
                    </div>
                    <div class="d-flex justify-content-between">
                        <b>EXTRA CHARGES</b><b> @if($order->extra_charges == null || $order->extra_charges == 0)
                            0.00
                            @else
                            {{ number_format($order->extra_charges, 2, '.', ',') }}
                            @endif
                            MMK</b>
                    </div>

                </div>
            </div>
            <div class="card order-detail-card date-card">
                <div class="card-body">
                    <i class="fa-solid fa-pen edit-info d-none" id="edit-date"></i>
                    <i class="fa-regular fa-floppy-disk save-info d-none" id="save-date"></i>
                    <i class="fa-solid fa-xmark cancel-info d-none" id="cancel-date"></i>
                    <div class="text-center">
                        <h5>Schedule Date</h5>
                        <hr>
                        <b id="date-info">
                            {{ $order->schedule_date ?
                                    \Carbon\Carbon::parse($order->schedule_date)->format('j F, Y')
                                    : 'N/A'
                            }}
                        </b>
                        <form action="{{url('/orders/update-schedule-date')}}" method="POST"
                            id="date-form" class="d-none">
                            @csrf
                            @method('POST')
                            <div style="position: relative; margin-left: 10px;" id="edit-date">
                                <input type="hidden" name="order_id" value="{{$order->id}}">
                                <input type="text" name="schedule_date" value="" class="form-control"
                                    id="schedule_date"/>
                                <span class="fa fa-calendar calendar"
                                    style="position: absolute; top: 12px; right: 8px;"></span>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <div class="card order-detail-card rider-card">
                <div class="card-body">
                    <i class="fa-solid fa-pen d-none edit-info" id="edit-rider"></i>
                    <i class="fa-regular fa-floppy-disk save-info d-none" id="save-rider"></i>
                    <i class="fa-solid fa-xmark cancel-info d-none" id="cancel-rider"></i>
                    <div class="text-center">
                        <h5>Rider</h5>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-around" id="rider-info">
                        <b>Name</b><b>@if($order->rider)
                            <a href="/riders/{{ $order->rider_id }}">  {{ $order->rider->name }}</a>
                            @else
                            N/A
                            @endif
                        </b>
                    </div>
                    <form action="{{url('/orders/update-rider')}}" id="rider-form" class="d-none" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="order_id" value="{{$order->id}}">
                        <select name="rider_id" id="rider_id" class="form-control"></select>
                    </form>
                </div>
            </div>

            @if(in_array($order->status, ['cancel', 'cancel-request', 'delay']))
            <div class="card order-detail-card">
                <div class="card-body">
                    <div class="text-center">
                        <h5>
                            {{ $order->statusLabel}} Reason
                        </h5>
                    </div>
                    <hr>
                    <div class="text-center">
                        <b>{{ $order->note ? $order->note : 'N/A'}}</b>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="right-content">
            <div class="card order-detail-card">
                <div class="card-body">
                    <div class="text-start">
                        <h5>Customer</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 ms-5">
                            <b>NAME</b>
                            <br>
                            <b>PHONE NUMBER</b>
                            <br>
                            <b>CITY</b>
                            <br>
                            <b>TOWNSHIP</b>
                            <br>
                            <b>ADDRESS</b>
                        </div>
                        <div class="col-md-6">
                            <b>{{$order->customer_name}}</b>
                            <br>
                            <b>{{$order->customer_phone_number}}</b>
                            <br>
                            <b><a href="/cities/{{ $order->city_id }}">  {{$order->city->name}}</a></b>
                            <br>
                            <b>@if($order->township)
                            <a href="/townships/{{ $order->township_id }}">  {{ $order->township->name }}</a>
                            <div id="township-id" data-township-id="{{ $order->township_id }}"></div>
                            @else
                            N/A
                            @endif</b>
                            <br>
                            <b>{{ $order->full_address}}</b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card order-detail-card">
                <div class="card-body">
                    <div class="text-start">
                        <h5>Shop</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 ms-5">
                            <b>NAME</b>
                        </div>
                        <div class="col-md-6">
                            <b><a href="/shops/{{ $order->shop_id }}">  {{$order->shop->name}}</a></b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card order-detail-card">
                <div class="card-body">
                <div class="text-start">
                        <h5>General</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 ms-5">
                            <b>ITEM TYPE</b>
                            <br>
                            <b>ORDER TYPE</b>
                            <br>
                            <b>COLLECTION METHOD</b>
                            <br>
                            <b>PAYMENT TYPE</b>
                            <br>
                            <b>PAYMENT CHANNEL</b>
                            <br>
                            <b>REMARK</b>
                        </div>
                        <div class="col-md-6">
                            <b>{{$order->itemType ? $order->itemType->name : 'N/A'}}</b>
                            <br>
                            <b>{{$order->delivery_type ? $order->delivery_type->name : 'N/A'}}</b>
                            <br>
                            <b>
                                @if($order->collection_method == 'dropoff')
                                    Drop Off
                                @else
                                    Pick Up
                                @endif
                            </b>
                            <br>
                            <b>
                                @if($order->payment_method == 'cash_on_delivery')
                                    Cash On Delivery
                                @elseif($order->payment_method == 'item_prepaid')
                                    Item Prepaid
                                @elseif($order->payment_method == 'all_prepaid')
                                    All Prepaid
                                @endif
                            </b>
                            <br>
                            <b>
                                @if($order->payment_channel == 'company_online_payment')
                                    Online Payment (Company)
                                @elseif($order->payment_channel == 'shop_online_payment')
                                    Online Payment (Shop)
                                @elseif($order->payment_channel == 'cash')
                                    Cash
                                @endif
                            </b>
                            <br>
                            <b>{{ $order->remark ? $order->remark : 'N/A'}}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        var order_code = $('#order_code').val();
        var township_id = document.getElementById('township-id').getAttribute('data-township-id');

        $('#order-tracking').click(function() {
            window.open("/tracking?order_id=" + order_code, "_blank");
        });

        $('#qr-code-generate').click(function() {
            var order_ids = [];
            var id = $('#order_id').val();
            order_ids.push(id);

            // Create a form element
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ url('/generate-qrcode') }}";
            form.target = '_blank';

            // Create hidden input fields for order_ids and _token
            var orderIdsInput = document.createElement('input');
            orderIdsInput.type = 'hidden';
            orderIdsInput.name = 'order_ids';
            orderIdsInput.value = order_ids;

            var csrfTokenInput = document.createElement('input');
            csrfTokenInput.type = 'hidden';
            csrfTokenInput.name = '_token';
            csrfTokenInput.value = '{{ csrf_token() }}';

            // Append the input fields to the form
            form.appendChild(orderIdsInput);
            form.appendChild(csrfTokenInput);

            // Append the form to the document body and submit it
            document.body.appendChild(form);
            form.submit();
        });

        function handleHover(element, target, checkmate) {
            element.hover(
                function() {
                    if(checkmate.hasClass('d-none')) {
                        target.removeClass('d-none');
                    }
                },
                function() {
                    target.addClass('d-none');
                }
            );
        }

        // Function to handle edit button click
        function handleEditClick(editButton, saveButton, cancelButton, infoElement, 
            formElement, selectElement, type) {
            editButton.click(function() {
                editButton.toggleClass('d-none');
                saveButton.toggleClass('d-none');
                cancelButton.toggleClass('d-none');
                infoElement.toggleClass('d-none');
                formElement.toggleClass('d-none');

                // Initialize data for the relevant select element
                if(type !== 'date') {
                    if(type === 'rider') {
                        getRidersByTownship(township_id);
                    }
                    selectElement.select2({ width: '100%' });
                } else {
                    selectElement.daterangepicker({
                        singleDatePicker: true,
                        showDropdowns: true,
                        minDate: moment(),
                        maxYear: parseInt(moment().format('YYYY'), 10)
                    });

                    // Handle calendar click to trigger date picker
                    $(".calendar").on("click", function() {
                        $('#schedule_date').trigger("click");
                    });

                    // Clear the date picker when canceled
                    selectElement.on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                    });
                }
            });

            cancelButton.click(function() {
                editButton.toggleClass('d-none');
                saveButton.toggleClass('d-none');
                cancelButton.toggleClass('d-none');
                infoElement.toggleClass('d-none');
                formElement.toggleClass('d-none');
            });

            saveButton.click(function() {
                formElement.submit();
            });
        }

        // Use the functions to handle different elements
        handleHover($(".status-card"), $("#edit-status"), $("#status-form"));
        handleEditClick($("#edit-status"), $("#save-status"), $("#cancel-status"),
            $('.status-card'), $('#status-form'), $("#status"), 'status');

        // Function to submit status form on change
        $("#status").on("change", function() {
            $("#status-form").submit();
        });

        handleHover($(".date-card"), $("#edit-date"), $("#save-date"));
        handleEditClick($("#edit-date"), $("#save-date"), $("#cancel-date"),
            $('#date-info'), $('#date-form'), $("#schedule_date"), 'date');

        handleHover($(".rider-card"), $("#edit-rider"), $("#save-rider"));
        handleEditClick($("#edit-rider"), $("#save-rider"), $("#cancel-rider"),
            $('#rider-info'), $('#rider-form'), $("#rider_id"), 'rider');

        function getRidersByTownship(township_id) {
            $.ajax({
                url: '/api/riders-get-by-township',
                type: 'POST',
                dataType: 'json',
                data: {
                    township_id: township_id
                },
                success: function(response) {
                    var rider_id = $('#rider_id').val();
                    var riders = '<option value="" selected disabled>Select the Rider for This Order</option>';
                    if (response.data) {
                        for (let i = 0; i < response.data.length; i++) {
                            if(response.data[0]) {
                                riders += '<option value="' + response.data[i].id + '" selected>' + response.data[i].name + '</option>';
                            } else if (rider_id == response.data[i].id) {
                                riders += '<option value="' + response.data[i].id + '" selected>' + response.data[i].name + '</option>';
                            } else {
                                riders += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>';
                            }
                        }
                    }
                    $('#rider_id').html(riders);
                },
            });
        };
    })
</script>
@endsection
