<!-- resources/views/pdf/orders.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Orders PDF</title>
    <style>
        /* Define your styles here */
        body{
            margin: 0;
        }
        .container {
            /* margin: 4rem 0rem; */
            width: 100%;
        }

        .dashed_container {
            border: 1px solid black;
            padding: 1rem;
            z-index: 1;
        }

        .left img {
            width: 350px;
        }

        .maincontent{
            position: relative;
            /* padding:20px; */
            /* width:435px; */
            height: 87%;
            width: 100%;
            padding-left: 20px;
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
            /* margin-top: 1rem; */
            text-align: center;
            font-size:14px;
            width: 100px;
            /* padding-top: 1rem; */
        }
        .bot img{
            width: 130px;
        }

        .content {
            font-weight: bold;
            font-size: 2em;
        }
        .phone_number {
            font-weight: bold;
            font-size: 2em;
        }


        .font {
            font-family: Arial, sans-serif;
            font-size: 2em;
            font-weight: bold;
            padding-left: 0.5em;
            width: 100%;
        }
        .label {
            font-family: Arial, sans-serif;
            font-size: 2em;
            font-weight: bold;
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
                    <td  class="bot">
                        <img src="data:image/png;base64, {{$logoImageData}}" alt="{{ config('app.name') }}">
                        <!-- <span class="content">Hotline:</span><span class="font mr-3"> 09740814035</span> -->
                    </td>
                    <td class="contact">
                        <span class="content">Hotline:</span><span class="phone_number"> 09740814035</span></br>
                        <span class="content">Viber:</span><span class="phone_number"> 09740814036</span>
                    </td>
                    <!-- <td class="bot">
                        <span class="content">Viber:</span><span class="font"> 09740814036</span>
                    </td> -->
                </tr>
                <tr>
                    <td class="left">
                        <img src="data:image/png;base64, {{ base64_encode(QrCode::size(150)->generate($order->id)) }}">
                        <table>
                            <tr>
                        <td><span class="font"> {{ $order->order_code }}</span></td>
                    </tr>
                        </table>
                    </td>
                    <td class="maincontent">
                        <table>
                            <tr>
                                <td><span class="label"> Customer Name </span></td>
                                <td><span class="label"> - </span></td>
                                <td><span class="font"> {{ $order->customer_name }} </span></td>
                            </tr>
                            <tr>
                                <td><span class="label"> Ph No. </span></td>
                                <td><span class="label"> - </span></td>
                                <td><span class="font"> {{ $order->customer_phone_number }}</span></td>
                            </tr>
                            
                            <tr>
                                <td><span class="label"> Township </span></td>
                                <td><span class="label"> - </span></td>
                                <td><span class="font"> {{ $order->township->name }}</span></td>
                            </tr>
                            <tr>
                                <td><span class="label"> Cash To Collect </span></td>
                                <td><span class="label"> - </span></td>
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
                                <td><span class="label"> Shop </span></td>
                                <td><span class="label"> - </span></td>
                                <td><span class="font"> {{ $order->shop->name }}</span></td>
                            </tr>
                            
                            <tr>
                                <td><span class="label"> Remark </span></td>
                                <td><span class="label"> - </span></td>
                                <td><span class="font"> {{ $order->remark }}</span></td>
                            </tr>     
                        </table>
                    </td>
                </tr>
                
            </table>
        </div>
    </div>
    @endforeach
</body>

</html>
