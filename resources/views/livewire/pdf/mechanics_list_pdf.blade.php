<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanics List Print</title>
    <style>
        html {
            font-size: x-small;
        }

        body {
            font-family: 'Montserrat', sans-serif;
        }

        table {
            width: 100%;
            border-spacing: 0;
        }

        #header {
            width: 100%;
            /* border: 1px solid black; */
        }

        #header td {
            /* border: 1px solid black; */
        }

        .title-cell {
            text-align: center;
            vertical-align: bottom;
            padding-bottom: 10px;
        }

        .title {
            font-size: 25px;
            font-weight: bolder;
            /* margin-left: -40px; */
        }

        table.content-table {
            width: 100%;
            /* Adjust width as needed */
            margin: 20px auto;
            /* Centers the table horizontally */
            border-collapse: collapse;
        }

        table.content-table td {
            padding: 15px;
            border: 1px solid #000000;
            font-weight: bold;
            /* Optional for better visibility */
        }

        .no-top-border {
            border-top: none !important;
        }

        .no-bottom-border {
            border-bottom: none !important;
        }

        .no-right-border {
            border-right: none !important;
        }

        .no-left-border {
            border-left: none !important;
        }

        #watermark {
            position: fixed;

            /** 
                Set a position in the page for your image
                This should center it vertically
            **/
            bottom: 11cm;
            left: 3.3cm;

            /** Change image dimensions**/
            width: 9cm;
            height: 8cm;

            /** Your watermark should be behind every content**/
            z-index: -1000;
            opacity: 0.1;
        }
    </style>
</head>

<body>
    <div id="watermark">
        <img src="data:image/png;base64,{{ $watermark }}" alt="depot-logo" width="450" />
    </div>

    <!-- Header table with logo and title -->
    <table id="header">
        <tr>
            <td style="text-align: left;" width="20">
                <img src="data:image/png;base64,{{ $cdo_full }}" alt="CDO Seal" width="70" />
            </td>
            <td>
                Republic of the Philippines <br>
                City of Cagayan de Oro <br>
                CITY EQUIPMENT DEPOT
            </td>
            <td style="text-align: right;">
                <img src="data:image/png;base64,{{ $rise_logo }}" alt="Rise Logo" width="120" />
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="title-cell">
                <span class="title">Mechanics List</span>
            </td>
        </tr>
    </table>

    <table class="content-table" style="margin-bottom: 5px;">
        <tr>
            <td class="no-top-border no-bottom-border no-left-border no-right-border" width="2">
                <span>Date(s)</span>
            </td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border" width="2" style="padding-left: 5px;">
                :
            </td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border" style="padding-left: 5px;">
                {{ $date }}
            </td>
        </tr>
    </table>

    <br>

    <!-- Content table with details -->
    <table class="content-table" style="margin-top: unset">
        <tr>
            <td class="no-right-border" width="60%">Name</td>
            <td style="text-align:center" width="13%">Pending</td>
            <td style="text-align:center" width="13%">Completed</td>
            <td style="text-align:center" width="13%">Total</td>
        </tr>
        @forelse($mechanics as $item)
        <tr>
            <td style="font-weight:unset">{{ $item->name }}</td>
            <td style="text-align:center; font-weight:unset">{{ $item->pending_jobs }}</td>
            <td style="text-align:center; font-weight:unset">{{ $item->completed_jobs }}</td>
            <td style="text-align:center; font-weight:unset">{{ $item->total_jobs }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4" style="text-align:center">No record found.</td>
        </tr>
        @endforelse
    </table>
</body>

</html>