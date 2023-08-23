<html>

<head>
    <style>
        /* CSS styles for the table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        h1 {
            text-align: center;
            font-size: 22;
        }

        .content {
            font-size: 12px;
        }

        thead th, .last-row td, .color {
            background-color: #d676fc;
        }
    </style>
</head>

<body>
    <h1>Orders</h1>
   
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Order Code</th>
                <th>Customer Name</th>
                <!-- <th>Customer Phone Number</th>
                <th>City</th> -->
                <th>Township</th>
                <th>Rider</th>
                <th>Shop</th>
                <th>R</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Delivery Fees</th>
                <th>Markup Delivery Fees</th>
                <th>Extra Charges</th>
                <th>Remark</th>
                <!-- <th>Status</th>
                <th>Item Type</th>
                <th>Full Address</th>
                <th>Schedule Date</th>
                <th>Type</th>
                <th>Collection Method</th>
                <th>Payment Flag</th>
                <th>Discount</th> -->
            </tr>
        </thead>
        <tbody>
            
            @php
            $id = 1;
            $totalAmountSum = 0;
            $deliveryFeesSum = 0;
            $markupDeliveryFeesSum = 0;
            $extraChargesSum = 0;
            @endphp
            @foreach ($orders as $order)
            <tr>
                <td>{{ $id++ }}</td>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->customer_name }}</td>
                <!-- <td>{{ $order->customer_phone_number }}</td>
                <td>{{ $order->city_name }}</td> -->
                <td>{{ $order->township_name }}</td>
                <td>{{ $order->rider_name }}</td>
                <td>{{ $order->shop_name }}</td>
                <td></td>
                @php
                $quantityArray = explode(',', $order->quantity);
                $totalQuantity = array_sum($quantityArray);
                if($order->payment_method == 'cash_on_delivery') {
                    $totalAmountSum += $order->total_amount;
                }
                $deliveryFeesSum += ($order->delivery_fees - $order->discount);
                $markupDeliveryFeesSum += $order->markup_delivery_fees;
                $extraChargesSum += $order->extra_charges;
                @endphp
                <td>{{ $totalQuantity }}</td>
                <td>
                    @if($order->payment_method == 'cash_on_delivery')
                        {{ number_format($order->total_amount, 2, '.', ',') }}
                    @endif
                </td>
                <td>{{ number_format($order->delivery_fees - $order->discount, 2, '.', ',') }}</td>
                <td>{{ number_format($order->markup_delivery_fees, 2, '.', ',') }}</td>
                <td>{{ number_format($order->extra_charges, 2, '.', ',') }}</td>
                <td>{{ $order->remark }}</td>
                <!-- <td> @if($order->status == 'pending')
                    Pending @elseif($order->status == 'picking-up')
                    Picking Up @elseif($order->status == 'warehouse')
                    In Warehouse @elseif($order->status == 'delivering')
                    Delivering @elseif($order->status == 'success')
                    Delivered @elseif($order->status == 'dalay')
                    Delay @elseif($order->status == 'cancel')
                    Cancel @else
                    Cancel Request
                    @endif
                </td>
                <td>{{ $order->item_type_name }}</td>
                <td>{{ $order->full_address }}</td>
                <td>{{ \Carbon\Carbon::parse($order->schedule_date)->format('d-m-Y') }}</td>
                <td>{{$order->delivery_type_name}}</td>
                <td>@if($order->collection_method == 'dropoff')
                    Drop Off @else
                    Pick Up
                    @endif
                </td>
                <td> @if ($order->payment_flag == 0)
                    Unpaid
                    @elseif($order->payment_flag == 1)
                    Paid
                    @endif
                </td>
              
                <td>{{$order->discount}}</td> -->
            </tr>
            @endforeach
            <tr class="last-row">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ number_format($totalAmountSum, 2, '.', ',') }}</td>
                <td>{{ number_format($deliveryFeesSum, 2, '.', ',') }}</td>
                <td>{{ number_format($markupDeliveryFeesSum, 2, '.', ',') }}</td>
                <td>{{ number_format($extraChargesSum, 2, '.', ',') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table style="width: 50%; margin: auto; margin-top: 50px;">
        <tbody>
            <tr>
                <td>Total Way</td>
                <td>{{$orders->count()}}</td>
            </tr>
            <tr class="color">
                <td>Total Amount</td>
                <td>{{ number_format($totalAmountSum, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td>Total Delivery Fees</td>
                <td>{{ number_format($deliveryFeesSum, 2, '.', ',') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>