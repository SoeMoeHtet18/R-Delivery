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
    <h1>Orders</h1>
    <span class="content">Total Amount : {{$orders->sum('total_amount')}}</span>
    <br>
    <span class="content">Total Way : {{$orders->count()}}</span>
    <br>
    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <th>Order Code</th>
                <th>Customer Name</th>
                <th>Township</th>
                <th>Rider</th>
                <th>Shop</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Delivery Fees</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
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
                @endphp
                <td>{{ $totalQuantity }}</td>
                <td>{{ $order->total_amount }}</td>
                <td>{{ $order->delivery_fees }}</td>
                <td>{{ $order->remark }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>