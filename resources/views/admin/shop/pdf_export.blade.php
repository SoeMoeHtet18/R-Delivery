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
            background-color: #50A3FF;
            font-weight: bold;
        }

        h1 {
            text-align: center;
            font-size: 18;
        }

        thead th, .last-row td, .color {
            background-color: #50A3FF;
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
                <th>Markup Deli Fees</th>
                <th>Extra Charges</th>
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
                if($order->payment_method != 'all_prepaid') {
                    $deliveryFeesSum += ($order->delivery_fees - $order->discount);
                    $markupDeliveryFeesSum += $order->markup_delivery_fees;
                    $extraChargesSum += $order->extra_charges;
                }
                @endphp
                <td>1</td>
                <td>
                    @if($order->payment_method == 'cash_on_delivery')
                        {{ number_format($order->total_amount, 2, '.', ',') }}
                    @else
                        0.00
                    @endif
                </td>
                @if($order->payment_method != 'all_prepaid')
                    <td>{{ number_format($order->delivery_fees - $order->discount, 2, '.', ',') }}</td>
                    <td>{{ number_format($order->markup_delivery_fees, 2, '.', ',') }}</td>
                    <td>{{ number_format($order->extra_charges, 2, '.', ',') }}</td>
                @else
                    <td>0.00</td>
                    <td>0.00</td>
                    <td>0.00</td>
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
                <td>Item Amount</td>
                <td>{{ number_format($totalAmountSum, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td>Markup Deli Fees</td>
                <td>{{ number_format($markupDeliveryFeesSum, 2, '.', ',') }}</td>
            </tr>
            @php
                $totalAmount = $totalAmountSum + $markupDeliveryFeesSum;
            @endphp
            <tr class="color">
                <td>Total Amount</td>
                <td>{{ number_format($totalAmount, 2, '.', ',') }}</td>
            </tr>
        </tbody>
    </table>
    <table style="width: 50%; margin: auto; margin-top: 50px;">
        <tbody>
            <tr>
                <td>Delivery Fees</td>
                <td>{{ number_format($deliveryFeesSum, 2, '.', ',') }}</td>
            </tr>
            <tr class="color">
                <td>Extra Charges</td>
                <td>{{ number_format($extraChargesSum, 2, '.', ',') }}</td>
            </tr>
            @php
                $totalDeliveryFees = $deliveryFeesSum + $extraChargesSum;
            @endphp
            <tr>
                <td>Total Delivery Fees</td>
                <td>{{ number_format($totalDeliveryFees, 2, '.', ',') }}</td>
            </tr>
        </tbody>
    </table>
    <table style="width: 50%; margin: auto; margin-top: 50px;">
        <tbody>
            @php
                $netAmount = $totalAmount - $totalDeliveryFees;
            @endphp
            <tr>
                <td>Net Amount</td>
                <td>{{ number_format($netAmount, 2, '.', ',') }}</td>
            </tr>
            <tr class="color">
                <td>Paid Amount</td>
                <td>{{ number_format($paidAmount, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td>Left Over Amount</td>
                <td>{{ number_format($netAmount - $paidAmount, 2, '.', ',') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>