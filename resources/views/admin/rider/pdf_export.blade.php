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
    </style>
</head>

<body>
    <h1>TCP Rider Today Order List</h1>
    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <th>Order Code</th>
                <th>Customer Name</th>
                <th>Township</th>
                <th>Rider</th>
                <th>Shop</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>Deli</th>
                <th>OS Deli</th>
                <th>Deli Add</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalAmountSum = 0;
            $deliveryFeesSum = 0;
            $markupDeliveryFeesSum = 0;
            $OSDeliSum = 0;
            @endphp
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->township->name }}</td>
                <td>{{ $order->rider->name }}</td>
                <td>{{ $order->shop->name }}</td>
                @php
                $quantityArray = explode(',', $order->quantity);
                $totalQuantity = array_sum($quantityArray);
                if($order->payment_method == 'cash_on_delivery') {
                    $totalAmountSum += $order->total_amount;
                }
                $deliveryFeesSum += $order->delivery_fees;
                $markupDeliveryFeesSum += $order->markup_delivery_fees;
                $OSDeliSum += ($order->delivery_fees + $order->markup_delivery_fees);
                @endphp
                <td>{{ $totalQuantity }}</td>
                <td>
                    @if($order->payment_method == 'cash_on_delivery') 
                        {{ $order->total_amount }} 
                    @endif
                </td>
                <td>{{ $order->delivery_fees }}</td>
                @if($order->markup_delivery_fees == 0) 
                <td></td>
                <td></td>
                @else
                <td>{{ $order->delivery_fees + $order->markup_delivery_fees }}</td>
                <td>{{ $order->markup_delivery_fees }}</td>
                @endif
                <td>{{ $order->remark }}</td>
            </tr>
           
            @endforeach
            <tr class="last-row">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $totalAmountSum }}</td>
                <td>{{ $deliveryFeesSum }}</td>
                <td>{{ $OSDeliSum }}</td>
                <td>{{ $markupDeliveryFeesSum }}</td>
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
                <td>{{ $totalAmountSum }}</td>
            </tr>
            <tr>
                <td>Total Delivery Fees</td>
                <td>{{ $deliveryFeesSum }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>