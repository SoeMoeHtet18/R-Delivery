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
            font-size: 18;
        }

        thead th, .last-row td, .color {
            background-color: #d676fc;
        }
    </style>
</head>

<body>
    <h1>TCP Express Purchase Received List ({{ \Carbon\Carbon::today()->format('d.m.Y') }})</h1>
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Township</th>
                <th>Supplier Name</th>
                <th>R</th>
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
            $extraChargesSum = 0;
            @endphp

            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->township->name }}</td>
                <td>{{ $order->shop->name }}</td>
                <td>@if($order->rider != null){{ $order->rider->name }}@endif</td>
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
            </tr>
            @endforeach

            <tr class="last-row">
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
                <td>Way</td>
                <td>{{$orders->count()}}</td>
            </tr>
            <tr class="color">
                <td>Amount</td>
                <td>{{ number_format($totalAmountSum, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td>OS Deli</td>
                <td>{{ number_format($markupDeliveryFeesSum, 2, '.', ',') }}</td>
            </tr>
            <tr class="color">
                <td>Total Amount</td>
                <td>{{ number_format($totalAmountSum - $markupDeliveryFeesSum, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td>Deli Add</td>
                <td>{{ number_format($extraChargesSum, 2, '.', ',') }}</td>
            </tr>
            <tr class="color">
                <td>Net Amount</td>
                <td>{{ number_format($totalAmountSum - $markupDeliveryFeesSum + $extraChargesSum, 2, '.', ',') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>