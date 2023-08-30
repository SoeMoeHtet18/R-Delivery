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
        thead th {
            background-color: #d676fc;
        }
    </style>
</head>

<body>
    <h1>Shops List</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Township Name</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Total Ways</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shops as $shop)
            <tr>
                <td>{{ $shop->name  }}</td>
                <td>{{ $shop->township->name ?? '' }}</td>
                <td>{{ $shop->address }}</td>
                <td>{{ $shop->phone_number }}</td>
                <td>{{ number_format($shop->orders->count(), 0, '.', ',') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>