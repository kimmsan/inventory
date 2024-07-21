<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@lang('PDF View')</title>
    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Kumbh Sans', sans-serif, Helvetica, Arial, sans-serif;
            font-size: .90em;
            line-height: 1;
        }

        h6,
        .h6,
        h5,
        .h5,
        h4,
        .h4,
        h3,
        .h3,
        h2,
        .h2,
        h1,
        .h1 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-weight: 600;
            line-height: 1.25;
            color: #24292d;
        }

        .table-listing {
            font-family: 'Kumbh Sans', sans-serif, Helvetica, Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .table-listing td,
        .table-listing th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table-listing tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-listing tr:hover {
            background-color: #ddd;
        }

        .table-listing th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #6366f1;
            color: white;
        }

        .table-image-preview {
            width: 60px;
            height: 60px;
            border-radius: 4px;
        }

        .no-preview {
            width: 60px;
            height: 60px;
            border-radius: 4px;
            background: rgb(219, 219, 219);
            display: block;
            padding: 0;
            margin-top: -5px;
            text-align: center;
        }

        .no-preview small {
            text-align: center;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }
    </style>
    @yield('page-style')
</head>

<body>
    <div class="container">
        <div class="row table-content">
            @yield('content-area')
        </div>
    </div>
</body>

</html>
