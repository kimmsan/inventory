<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('title')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kumbh Sans', sans-serif, Helvetica, Arial, sans-serif;
            line-height: 1;
            padding: 5px;
            font-size: 14px;
        }

        .invoice-header {
            overflow: visible;
            margin-bottom: 30px;
            height: 130px;
        }

        .invoice-header h1 {
            font-size: 24px;
            margin-top: 0;
        }

        .invoice-header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .logo {
            float: left;
            max-width: 400px;
        }

        .logo img {
            max-width: 150px;
        }

        .invoice-info {
            float: right;
            text-align: right;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .invoice-table th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
        }

        .footer p {
            font-size: 14px;
            margin: 5px 0;
        }

        .footer strong {
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="invoice-header">
        <div class="logo">
            @php
                $path = asset('images/' . config('config.logoBlack'));
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
            <img src="{{ $logo }}" style="width: 100%; max-width: 300px" /><br />
            <p><strong>@lang('Phone'):</strong> {{ config('config.companyPhoneNumber') }}</p>
            <p><strong>@lang('Email'):</strong> {{ config('config.companyEmail') }}</p>
            <p><strong>@lang('Address'):</strong> {{ config('config.address') }}</p>
        </div>
        <div class="invoice-info">
            @yield('receiver-info')
        </div>
    </div>
    <br />

    @yield('pdf-content')

    <div class="footer">
        <p><strong>@lang('Thank you for your business!')</strong></p>
    </div>
</body>

</html>
