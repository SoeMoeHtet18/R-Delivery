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
                <th>Customer Phone Number</th>
                <th>City</th>
                <th>Township</th>
                <th>Rider</th>
                <th>Shop</th>
                <th>Total Amount</th>
                <th>Delivery Fees</th>
                <th>Markup Delivery Fees</th>
                <th>Remark</th>
                <th>Status</th>
                <th>Item Type</th>
                <th>Full Address</th>
                <th>Schedule Date</th>
                <th>Type</th>
                <th>Collection Method</th>
                <th>Payment Flag</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->customer_phone_number }}</td>
                <td>{{ $order->city_name }}</td>
                <td>{{ $order->township_name }}</td>
                <td>{{ $order->rider_name }}</td>
                <td>{{ $order->shop_name }}</td>
                <td>{{ $order->total_amount }}</td>
                <td>{{ $order->delivery_fees }}</td>
                <td>{{ $order->markup_delivery_fees }}</td>
                <td>{{ $order->remark }}</td>
                <td> @if($order->status == 'pending')
                    Pending @elseif($order->status == 'success')
                    Success @elseif($order->status == 'dalay')
                    Delay @elseif($order->status == 'cancel')
                    Cancel @else
                    Cancel Request
                    @endif
                </td>
                <td>{{ $order->item_type }}</td>
                <td>{{ $order->full_address }}</td>
                <td>{{ \Carbon\Carbon::parse($order->schedule_date)->format('d-m-Y') }}</td>
                <td> @if($order->type == 'standard')
                    Standard
                    @elseif($order->type == 'express')
                    Express
                    @else
                    Door To Door
                    @endif
                </td>
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
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>