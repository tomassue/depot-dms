<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>job-order-print</title>
    <style>
        /* html {
            font-size: x-small;
        } */

        body {
            font-family: 'montserrat', sans-serif;
        }

        h4 {
            margin: 0;
        }

        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        .margin-top {
            margin-top: 1.25rem;
        }

        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(241 245 249);
        }

        table {
            width: 100%;
            border-spacing: 0;
        }

        table.products {
            font-size: 0.875rem;
        }

        table.products tr {
            background-color: rgb(10, 52, 94);
        }

        table.products th {
            color: #ffffff;
            padding: 0.5rem;
        }

        table tr.items {
            background-color: rgb(241 245 249);
        }

        table tr.items td {
            padding: 0.5rem;
        }

        table th {
            text-align: left;
            text-transform: uppercase;
        }

        .total {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.875rem;
            margin-right: 50px;
        }
    </style>
</head>

<body>
    <table class="w-full">
        <tr>
            <td style="text-align: left;">
                <img src="data:image/png;base64,{{ $cdo_full }}" alt="CDO Seal" width="200" />
            </td>

            <td style="text-align: center;">
                <span style="font-size:xx-large; font-weight:bolder">JOB ORDER</span>
            </td>

            <td style="text-align: right;">
                <img src="data:image/png;base64,{{ $rise_logo }}" alt="rise-logo" width="150" />
            </td>
        </tr>
    </table>

</body>

</html>