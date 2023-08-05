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
        thead tr {
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
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->township->name }}</td>
                <td>{{ $order->shop->name }}</td>
                <td>{{ $order->rider->name }}</td>
                @php
                $quantityArray = explode(',', $order->quantity);
                $totalQuantity = array_sum($quantityArray);
                @endphp
                <td>{{ $totalQuantity }}</td>
                <td>{{ $order->total_amount }}</td>
                <td>{{ $order->delivery_fees }}</td>
                <td>{{ $order->markup_delivery_fees }}</td>
                @php
                $difference = $order->markup_delivery_fees - $order->delivery_fees;
                @endphp
                @if ($difference > 0)
                <td>{{ $difference }}</td>
                @else
                <td></td>
                @endif
                <td>{{ $order->remark }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>