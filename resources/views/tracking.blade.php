@extends('layouts.app')
@yield('style')
<style>
    .main-page .title {
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 600;
        font-size: 20px;
        line-height: 30px;
        text-transform: uppercase;
        color: #505050;
    }

    .dotted-container {
        box-sizing: border-box;
        width: 443px;
        height: 143px;
        border: 2px dashed #224466;
        border-radius: 10px;
    }

    .sub-title {
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        letter-spacing: 0.01em;
        text-decoration-line: underline;
        text-transform: capitalize;
        color: #505050;
    }

    .info-text {
        display: block;
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 400;
        font-size: 13px;
        line-height: 18px;
        color: #224466;
    }

    .info-text.id {
        color: #278AF9;
    }

    .status-monitor .preview-box {
        box-sizing: border-box;
        width: 45px;
        height: 45px;
        border: 2px solid #505050;
    }

    .pending-preview,
    .preview-status.pending {
        position: relative;
    }

    .shipping-preview,
    .preview-status.shipping {
        position: relative;
    }

    .delivered-preview,
    .preview-status.delivered {
        position: relative;
    }

    .preview-svg {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .preview-status {
        width: 53px;
        font-family: 'Poppins';
        font-style: normal;
        font-weight: 400;
        font-size: 13px;
        line-height: 18px;

        color: #000000;
    }

    .timeline-container {
        display: flex;
    }

    .timeline-box {
        width: 30px;
        height: 30px;
        background-color: #505050;
    }

    .timeline-date-time {
        margin-bottom: 83px !important;
    }

    #created-timeline-date-time {
        margin-bottom: 0 !important;
    }

    .line {
        margin-top: 25px;
        width: 100px;
        height: 2px;
        background-color: black;
    }

    .vertical-line {
        width: 1px;
        height: 76px;
        background-color: black;
    }
</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 pb-5">
            <div class="main-page py-5 ps-5">
                <h1 class="title">Tracking Details</h1>
                <div class="dotted-container ms-4 px-3 py-4">
                    <div class="row">
                        <div class="col-7">
                            <h2 class="sub-title">Order Info</h2>
                            <span class="info-text mb-1">Delivery partner : R-Delivery</span>
                            <span class="info-text from mb-1">From : {{$order->shop_name}} </span>
                            @if(isset($order->rider_id))
                            <span class="info-text mt-1 phone-number text-no-wrap">Delivery Phone No : {{$order->rider_phone_number}}</span>
                            @endif
                        </div>
                        <div class="col-5">
                            <h2 class="sub-title order-id">Order ID</h2>
                            <span class="info-text id">#{{$order->order_code}}</span>
                        </div>
                    </div>
                </div>
                <div class="row my-5 pt-5 justify-content-center">
                    <div class="col-md-8 status-monitor d-flex justify-content-center p-0">
                        <div class="d-flex flex-column align-items-center m-0 p-0">
                            <div class="pending-preview preview-box rounded-pill @if($order->status == 'pending' && $order->rider_id == null) bg-black @endif">
                                @if($order->status == 'pending' && $order->rider_id == null)
                                <svg class="preview-svg" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.4211 25C16.6009 25 15.0491 24.4194 13.7658 23.2583C12.4825 22.0972 11.8412 20.6937 11.8421 19.0476C11.8421 17.4008 12.4838 15.9968 13.7671 14.8357C15.0504 13.6746 16.6018 13.0944 18.4211 13.0952C20.2412 13.0952 21.793 13.6758 23.0763 14.8369C24.3596 15.998 25.0009 17.4016 25 19.0476C25 20.6944 24.3583 22.0984 23.075 23.2595C21.7917 24.4206 20.2404 25.0008 18.4211 25ZM20.625 21.875L21.5461 21.0417L19.0789 18.8095V15.4762H17.7632V19.2857L20.625 21.875ZM0 23.8095V2.38095H8.125C8.36623 1.68651 8.83772 1.11588 9.53947 0.66905C10.2412 0.222224 11.0088 -0.00079154 11.8421 2.11077e-06C12.7193 2.11077e-06 13.5035 0.223415 14.1947 0.67024C14.886 1.11707 15.3518 1.6873 15.5921 2.38095H23.6842V12.2024C23.2895 11.9444 22.8728 11.7262 22.4342 11.5476C21.9956 11.369 21.5351 11.2103 21.0526 11.0714V4.76191H18.4211V8.33333H5.26316V4.76191H2.63158V21.4286H9.60526C9.75877 21.8651 9.93421 22.2817 10.1316 22.6786C10.3289 23.0754 10.5702 23.4524 10.8553 23.8095H0ZM11.8421 4.76191C12.2149 4.76191 12.5276 4.64762 12.7803 4.41905C13.0329 4.19048 13.1588 3.90794 13.1579 3.57143C13.1579 3.23413 13.0316 2.95119 12.7789 2.72262C12.5263 2.49405 12.214 2.38016 11.8421 2.38095C11.4693 2.38095 11.1566 2.49524 10.9039 2.72381C10.6513 2.95238 10.5254 3.23492 10.5263 3.57143C10.5263 3.90873 10.6526 4.19167 10.9053 4.42024C11.1579 4.64881 11.4702 4.7627 11.8421 4.76191Z" fill="white" />
                                </svg>
                                @else
                                <svg class="preview-svg" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.4211 25C16.6009 25 15.0491 24.4194 13.7658 23.2583C12.4825 22.0972 11.8412 20.6937 11.8421 19.0476C11.8421 17.4008 12.4838 15.9968 13.7671 14.8357C15.0504 13.6746 16.6018 13.0944 18.4211 13.0952C20.2412 13.0952 21.793 13.6758 23.0763 14.8369C24.3596 15.998 25.0009 17.4016 25 19.0476C25 20.6944 24.3583 22.0984 23.075 23.2595C21.7917 24.4206 20.2404 25.0008 18.4211 25ZM20.625 21.875L21.5461 21.0417L19.0789 18.8095V15.4762H17.7632V19.2857L20.625 21.875ZM0 23.8095V2.38095H8.125C8.36623 1.68651 8.83772 1.11588 9.53947 0.66905C10.2412 0.222224 11.0088 -0.00079154 11.8421 2.11077e-06C12.7193 2.11077e-06 13.5035 0.223415 14.1947 0.67024C14.886 1.11707 15.3518 1.6873 15.5921 2.38095H23.6842V12.2024C23.2895 11.9444 22.8728 11.7262 22.4342 11.5476C21.9956 11.369 21.5351 11.2103 21.0526 11.0714V4.76191H18.4211V8.33333H5.26316V4.76191H2.63158V21.4286H9.60526C9.75877 21.8651 9.93421 22.2817 10.1316 22.6786C10.3289 23.0754 10.5702 23.4524 10.8553 23.8095H0ZM11.8421 4.76191C12.2149 4.76191 12.5276 4.64762 12.7803 4.41905C13.0329 4.19048 13.1588 3.90794 13.1579 3.57143C13.1579 3.23413 13.0316 2.95119 12.7789 2.72262C12.5263 2.49405 12.214 2.38016 11.8421 2.38095C11.4693 2.38095 11.1566 2.49524 10.9039 2.72381C10.6513 2.95238 10.5254 3.23492 10.5263 3.57143C10.5263 3.90873 10.6526 4.19167 10.9053 4.42024C11.1579 4.64881 11.4702 4.7627 11.8421 4.76191Z" fill="#505050" />
                                </svg>

                                @endif
                            </div>
                            <span class="preview-status mt-2 pending">Pending</span>
                        </div>
                        <div class="line"></div>
                        <div class="d-flex flex-column align-items-center m-0 p-0">
                            <div class="shipping-preview preview-box rounded-pill @if($order->status != 'success' && $order->rider_id != null) bg-black @endif">
                                @if($order->status != 'success' && $order->rider_id != null)
                                <svg class="preview-svg" width="27" height="22" viewBox="0 0 27 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.94739 18.1428C4.94739 18.9006 5.22464 19.6273 5.71816 20.1632C6.21168 20.699 6.88103 21 7.57897 21C8.2769 21 8.94626 20.699 9.43977 20.1632C9.93329 19.6273 10.2105 18.9006 10.2105 18.1428C10.2105 17.3851 9.93329 16.6584 9.43977 16.1225C8.94626 15.5867 8.2769 15.2857 7.57897 15.2857C6.88103 15.2857 6.21168 15.5867 5.71816 16.1225C5.22464 16.6584 4.94739 17.3851 4.94739 18.1428ZM18.1053 18.1428C18.1053 18.9006 18.3825 19.6273 18.8761 20.1632C19.3696 20.699 20.0389 21 20.7369 21C21.4348 21 22.1042 20.699 22.5977 20.1632C23.0912 19.6273 23.3684 18.9006 23.3684 18.1428C23.3684 17.3851 23.0912 16.6584 22.5977 16.1225C22.1042 15.5867 21.4348 15.2857 20.7369 15.2857C20.0389 15.2857 19.3696 15.5867 18.8761 16.1225C18.3825 16.6584 18.1053 17.3851 18.1053 18.1428Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M4.94737 18.1429H2.31579V12.4286M1 1H15.4737V18.1429M10.2105 18.1429H18.1053M23.3684 18.1429H26V9.57143M26 9.57143H15.4737M26 9.57143L22.0526 2.42857H15.4737M2.31579 6.71429H7.57895" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                @else
                                <svg id="shipping-preview-svg" class="preview-svg" width="27" height="22" viewBox="0 0 27 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.94739 18.1428C4.94739 18.9006 5.22464 19.6273 5.71816 20.1632C6.21168 20.699 6.88103 21 7.57897 21C8.2769 21 8.94626 20.699 9.43977 20.1632C9.93329 19.6273 10.2105 18.9006 10.2105 18.1428C10.2105 17.3851 9.93329 16.6584 9.43977 16.1225C8.94626 15.5867 8.2769 15.2857 7.57897 15.2857C6.88103 15.2857 6.21168 15.5867 5.71816 16.1225C5.22464 16.6584 4.94739 17.3851 4.94739 18.1428ZM18.1053 18.1428C18.1053 18.9006 18.3825 19.6273 18.8761 20.1632C19.3696 20.699 20.0389 21 20.7369 21C21.4348 21 22.1042 20.699 22.5977 20.1632C23.0912 19.6273 23.3684 18.9006 23.3684 18.1428C23.3684 17.3851 23.0912 16.6584 22.5977 16.1225C22.1042 15.5867 21.4348 15.2857 20.7369 15.2857C20.0389 15.2857 19.3696 15.5867 18.8761 16.1225C18.3825 16.6584 18.1053 17.3851 18.1053 18.1428Z" stroke="#505050" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M4.94737 18.1429H2.31579V12.4286M1 1H15.4737V18.1429M10.2105 18.1429H18.1053M23.3684 18.1429H26V9.57143M26 9.57143H15.4737M26 9.57143L22.0526 2.42857H15.4737M2.31579 6.71429H7.57895" stroke="#505050" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                @endif
                            </div>
                            <span class="preview-status mt-2 shipping">Shipping</span>
                        </div>
                        <div class="line"></div>
                        <div class="d-flex flex-column align-items-center m-0 p-0">
                            <div class="delivered-preview preview-box rounded-pill @if($order->status == 'success') bg-black @endif">
                                @if($order->status == 'success')
                                <svg class="preview-svg" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.4796 0C11.2245 0 10.9694 0.125 10.7143 0.25L0.637755 5.75C0.255102 6 0 6.375 0 6.875V18.125C0 18.625 0.255102 19 0.637755 19.25L10.7143 24.75C10.9694 24.875 11.2245 25 11.4796 25C11.7347 25 11.9898 24.875 12.2449 24.75L13.3929 24.125C13.0102 23.375 12.8827 22.5 12.7551 21.625V13.25L20.4082 9V13.75C21.301 13.75 22.1939 13.875 22.9592 14.125V6.875C22.9592 6.375 22.7041 6 22.3214 5.75L12.2449 0.25C11.9898 0.125 11.7347 0 11.4796 0ZM11.4796 2.75L19.1327 6.875L16.5816 8.25L9.05612 4L11.4796 2.75ZM6.5051 5.375L14.0306 9.75L11.4796 11.125L3.82653 6.875L6.5051 5.375ZM2.55102 9L10.2041 13.25V21.625L2.55102 17.375V9ZM23.3418 17.25L18.75 21.75L16.7092 19.75L15.3061 21.25L18.8776 25L25 19L23.3418 17.25Z" fill="white" />
                                </svg>
                                @else
                                <svg id="delivered-preview-svg" class="preview-svg" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.4796 0C11.2245 0 10.9694 0.125 10.7143 0.25L0.637755 5.75C0.255102 6 0 6.375 0 6.875V18.125C0 18.625 0.255102 19 0.637755 19.25L10.7143 24.75C10.9694 24.875 11.2245 25 11.4796 25C11.7347 25 11.9898 24.875 12.2449 24.75L13.3929 24.125C13.0102 23.375 12.8827 22.5 12.7551 21.625V13.25L20.4082 9V13.75C21.301 13.75 22.1939 13.875 22.9592 14.125V6.875C22.9592 6.375 22.7041 6 22.3214 5.75L12.2449 0.25C11.9898 0.125 11.7347 0 11.4796 0ZM11.4796 2.75L19.1327 6.875L16.5816 8.25L9.05612 4L11.4796 2.75ZM6.5051 5.375L14.0306 9.75L11.4796 11.125L3.82653 6.875L6.5051 5.375ZM2.55102 9L10.2041 13.25V21.625L2.55102 17.375V9ZM23.3418 17.25L18.75 21.75L16.7092 19.75L15.3061 21.25L18.8776 25L25 19L23.3418 17.25Z" fill="#505050" />
                                </svg>
                                @endif
                            </div>
                            <span class="preview-status mt-2 delivered">Delivered</span>
                        </div>
                    </div>
                </div>
                <div class="timeline-container row pt-4">
                    <div class="col-md-12">
                        <div class="row w-100">
                            <div class="col-2">
                                @if($order->status == 'success')
                                <span id="delivered-timeline-date-time" class="timeline-date-time d-block">{{$order->order_data['delivered_at']}}</span>
                                @endif
                                @if($order->status == 'cancel')
                                <span id="canceled-timeline-date-time" class="timeline-date-time d-block">{{$order->order_data['canceled_at']}}</span>
                                @endif
                                @if(isset($order->order_data['delayed_at']))
                                <span id="delayed-timeline-date-time" class="timeline-date-time d-block">{{$order->order_data['delayed_at']}}</span>
                                @endif
                                @if($order->rider_id != null)
                                <span id="shipping-timeline-date-time" class="timeline-date-time d-block">{{$order->order_data['picked_at']}}</span>
                                @endif
                                @if($order->rider_id != null)
                                <span id="picked-timeline-date-time" class="timeline-date-time d-block">{{$order->order_data['picked_at']}}</span>
                                @endif
                                @if(isset($order->created_at))
                                <span id="created-timeline-date-time" class="timeline-date-time d-block">{{$order->created_at}}</span>
                                @endif
                            </div>
                            <div class="col-1">
                                @if($order->status == 'success')
                                <div id="delivered-timeline-box" class="timeline-box mx-auto rounded-pill d-flex justify-content-center align-items-center" style="background-color: #278AF9;">
                                    <i class="fa-solid fa-check text-white"></i>
                                </div>
                                <div class="vertical-line mx-auto"></div>
                                @endif
                                @if($order->status == 'cancel')
                                <div id="canceled-timeline-box" class="timeline-box mx-auto rounded-pill d-flex justify-content-center align-items-center" style="background-color: #278AF9;">
                                    <i class="fa-solid fa-check text-white"></i>
                                </div>
                                <div class="vertical-line mx-auto"></div>                                
                                @endif
                                @if(isset($order->order_data['delayed_at']) && $order->status == 'delay')
                                <div id="delay-timeline-box" class="timeline-box mx-auto rounded-pill d-flex justify-content-center align-items-center" style="background-color: #278AF9;">
                                    <i class="fa-solid fa-check text-white"></i>
                                </div>
                                <div class="vertical-line mx-auto"></div>
                                @elseif(isset($order->order_data['delayed_at']))
                                <div id="delay-timeline-box" class="timeline-box mx-auto rounded-pill d-flex justify-content-center align-items-center">
                                </div>
                                <div class="vertical-line mx-auto"></div>
                                @else
                                @endif
                                @if($order->rider_id != null && $order->status == 'pending')
                                <div id="shipping-timeline-box" class="timeline-box mx-auto rounded-pill d-flex justify-content-center align-items-center" style="background-color: #278AF9;">
                                    <i class="fa-solid fa-check text-white"></i>
                                </div>
                                <div class="vertical-line mx-auto"></div>
                                <div id="picked-timeline-box" class="timeline-box mx-auto rounded-pill d-flex justify-content-center align-items-center" style="background-color: #278AF9;">
                                    <i class="fa-solid fa-check text-white"></i>
                                </div>
                                <div class="vertical-line mx-auto"></div>
                                @elseif($order->rider_id == null)
                                @else
                                <div id="shipping-timeline-box" class="timeline-box mx-auto rounded-pill d-flex justify-content-center align-items-center">
                                </div>
                                <div class="vertical-line mx-auto"></div>
                                <div id="picked-timeline-box" class="timeline-box mx-auto rounded-pill d-flex justify-content-center align-items-center">
                                </div>
                                <div class="vertical-line mx-auto"></div>
                                @endif
                                @if(isset($order->created_at) && $order->rider_id == null && $order->status == 'pending')
                                <div id="created-timeline-box" class="timeline-box mx-auto rounded-pill d-flex justify-content-center align-items-center" style="background-color: #278AF9;">
                                    <i class="fa-solid fa-check text-white"></i>
                                </div>
                                @else
                                <div id="created-timeline-box" class="timeline-box mx-auto rounded-pill d-flex justify-content-center align-items-center">
                                </div>
                                @endif
                            </div>
                            <div class="col-9">
                                @if($order->status == 'success')
                                <div class="mb-5">
                                    <h5 class="delivered-timeline-status">Delivered</h5>
                                    <span class="delivered-timeline-status sub-status">Your order has been delivered. </span>
                                </div>
                                @endif
                                @if($order->status == 'cancel')
                                <div class="mb-5">
                                    <h5 class="canceled-timeline-status">Canceled</h5>
                                    <span class="canceled-timeline-status sub-status">Your order has been canceled. </span>
                                </div>
                                @endif
                                @if(isset($order->order_data['delayed_at']))
                                <div class="mb-5">
                                    <h5 class="delay-timeline-status">Delay</h5>
                                    <span class="delay-timeline-status sub-status">Your order has been delayed due to traffic. We will try to deliver again to you tomorrow. </span>
                                </div>
                                @endif
                                @if($order->rider_id != null)
                                <div class="mb-5">
                                    <h5 class="shipping-timeline-status">Out For Delivery</h5>
                                    <span class="shipping-timeline-status sub-status">R-Delivery will attempt to deliver your order today. </span>

                                </div>
                                <div class="mb-5">
                                    <h5 class="picked-timeline-status">Order Successfully Picked Up</h5>
                                    <span class="picked-timeline-status sub-status">Your order has been picked up by R-Delivery. </span>
                                </div>
                                @endif
                                @if(isset($order->created_at))
                                <div>
                                    <h5 class="created-timeline-status">Packed by Online Shop</h5>
                                    <span class="picked-timeline-status sub-status">Your order is packed and will be handed over to our delivery partner. </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deliveredElement = document.getElementById('delivered-timeline-date-time');
        const canceledElement = document.getElementById('canceled-timeline-date-time');
        const delayedElement = document.getElementById('delayed-timeline-date-time');
        const shippingElement = document.getElementById('shipping-timeline-date-time');
        const pickedElement = document.getElementById('picked-timeline-date-time');
        const createdElement = document.getElementById('created-timeline-date-time');

        const formatAndSetTimestamp = (element) => {
            const timestamp = element.textContent;

            if (timestamp) {
                const dateObj = new Date(timestamp);

                const dateFormatter = new Intl.DateTimeFormat('my-MM', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                var formattedDate = dateFormatter.format(dateObj);
                console.log(formattedDate);

                const [month, day, year] = formattedDate.split(' ');
                const formattedDay = day.replace(',', '');
                formattedDate = `${formattedDay} ${month} ${year}`;

                const timeFormatter = new Intl.DateTimeFormat('my-MM', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                });
                const formattedTime = timeFormatter.format(dateObj);
                const formattedDateTime = `${formattedDate} at ${formattedTime}`;

                element.textContent = formattedDateTime;
            }
        };
        if (deliveredElement) {
            formatAndSetTimestamp(deliveredElement);
        }
        if (canceledElement) {
            formatAndSetTimestamp(canceledElement);
        }
        if (delayedElement) {
            formatAndSetTimestamp(delayedElement);
        }
        if (shippingElement) {
            formatAndSetTimestamp(shippingElement);
        }
        if (pickedElement) {
            formatAndSetTimestamp(pickedElement);
        }
        formatAndSetTimestamp(createdElement);
    });
</script>
@endSection