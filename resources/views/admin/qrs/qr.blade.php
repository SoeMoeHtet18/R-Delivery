<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
        .dashed_container {
            height: 200px;
            border: 1px solid black;
            padding: 50px;
        }

        .left {
            float: left;
            margin-right: 20px;
        }

        .right {
            overflow: hidden; /* Clear floats */
            position: relative; /* Required for absolute positioning */
        }

        .content {
            font-size: 16px;
            font-weight: bold;
            font-family: 'Poppins';
        }

        .font {
            font-family: 'Poppins';
        }

        .bot {
            position: absolute;
            bottom: 0; /* Position at the bottom of the right div */
            left: 0; /* Align left */
            right: 0; /* Align right */
            padding: 10px 0; /* Optional padding */
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="dashed_container p-5">
            <div class="left">
                <img src="data:image/png;base64, {{ base64_encode(QrCode::size(200)->generate($data['id'])) }}">
            </div>
            <div class="right">
                <div><b class="content">Delivery Fees :</b><span class="font"> {{$data['delivery_fees']}}</span></div>
                <div><b class="content">Item Amount :</b><span class="font"> {{$data['item_amount']}}</span></div>
                <div><b class="content">Cash To Collect :</b><span class="font"> {{$data['cash_to_collect']}}</span></div>
                <div><b class="content">Phone Number :</b><span class="font"> {{$data['customer_phone_number']}}</span></div>
                <div><b class="content">Customer Name :</b><span class="font"> {{$data['customer_name']}}</span></div>
                <div><b class="content">Township :</b><span class="font"> {{$data['township']}}</span></div>
            </div>
            <div class="right">
                 <img src="data:image/png;base64, {{ base64_encode(config('app.url'). 'images/tcp_delivery.jpg' }}">
            </div>
            <div class="bot">
                <b class="content">Hotline :</b><span class="font"> 09740814035</span>
                <b class="content">Viber :</b><span class="font"> 09740814036</span>
            </div>
        </div>
    </div>
</body>

</html>