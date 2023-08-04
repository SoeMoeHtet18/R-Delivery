<!-- resources/views/pdf/orders.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Orders PDF</title>
    <style>
        /* Define your styles here */
        .container {
            margin: 4rem 0rem;
        }

        .dashed_container {
            border: 1px solid black;
            padding: 1rem;
            z-index: 1;
        }

        .left img {
            width: 170px;
        }

        .maincontent{
            position: relative;
            padding:20px;
            width:435px;
            padding-right:40px;
        }

        .maincontent img {
            position: absolute;
            top:-8px;
            right:0px;
            width:120px;
            z-index: -1;
        }

        .bot {
            margin-top: 1rem;
            text-align: center;
            font-size:14px;
            padding-top: 1rem;
        }

        .content {
            font-weight: bold;
            margin-right:5px;
        }

        .font {
            font-family: Arial, sans-serif;
        }
        .mr-3{
            margin-right:30px;
        }
    </style>
</head>

<body>
    @foreach ($orders as $order)
    <div class="container">
        <div class="dashed_container">
            <table>
                <tr>
                    <td class="left">
                        <img src="data:image/png;base64, {{ base64_encode(QrCode::size(150)->generate($order->id)) }}">
                    </td>
                    <td class="maincontent">
                        <img src="data:image/png;base64, {{$logoImageData}}" alt="{{ config('app.name') }}">
                        <table>
                            <tr>
                                <td><span class="content">Customer Name:</span></td>
                                <td><span class="font"> {{ $order->customer_name }}</span></td>
                            </tr>
                            <tr>
                                <td><span class="content">Phone Number:</span></td>
                                <td><span class="font"> {{ $order->customer_phone_number }}</span></td>
                            </tr>
                            <tr>
                                <td><span class="content">Township:</span></td>
                                <td><span class="font"> {{ $order->township->name }}</span></td>
                            </tr>
                            <tr>
                                <td><span class="content">Supplier:</span></td>
                                <td><span class="font"> {{ $order->shop->name }}</span></td>
                            </tr>
                            <tr>
                                <td><span class="content">Cash To Collect:</span></td>
                                <td><span class="font">
                                        @if ($order->payment_method === 'cash_on_delivery')
                                            {{ $order->total_amount + ($order->delivery_fees + $order->extra_charges) - $order->discount }}
                                        @elseif ($order->payment_method === 'item_prepaid')
                                            {{ ($order->delivery_fees + $order->extra_charges) - $order->discount }}
                                        @else
                                            0
                                        @endif
                                    MMK </span></td>
                            </tr>
                            <tr>
                                <td><span class="content">Remark:</span></td>
                                <td><span class="font"> {{ $order->remark }}</span></td>
                            </tr>     
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="bot">
                        <span class="content">Hotline:</span><span class="font mr-3"> 09740814035</span>
                        <span class="content">Viber:</span><span class="font"> 09740814036</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @endforeach
</body>

</html>
