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
    <h1>TCP Express Pick Up Received List ({{ \Carbon\Carbon::today()->format('d.m.Y') }})</h1>
    <table>
        <thead>
            <tr>
                <th>Supplier Name</th>
                <th>Total Quantity</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Deli Name</th>
                <th>Collected At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($collections as $collection)
            <tr>
                <td>{{ $collection->shop->name }}</td>
                <td>{{ $collection->total_quantity }}</td>
                <td>{{ $collection->total_amount }}</td>
                <td>{{ $collection->paid_amount }}</td>
                <td>@if($collection->rider != null){{ $collection->rider->name }}@endif</td>
                <td>@if($collection->collected_at != null){{ $collection->collected_at->format('d.m.Y') }}@endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>