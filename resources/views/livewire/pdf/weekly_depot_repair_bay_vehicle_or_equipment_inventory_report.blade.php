<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Order Print</title>
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
            bottom: 6.8cm;
            left: 7.7cm;

            /** Change image dimensions**/
            width: 8cm;
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
            <td style="text-align: left;">
                <img src="data:image/png;base64,{{ $cdo_full }}" alt="CDO Seal" width="200" />
            </td>
            <td class="title-cell">

            </td>
            <td style="text-align: right;">
                <img src="data:image/png;base64,{{ $rise_logo }}" alt="Rise Logo" width="120" />
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="title-cell">
                <span class="title">Weekly Depot Repair Bay Vehicle / <br> Equipment Inventory Report</span>
            </td>
        </tr>
    </table>

    <br>

    <!-- Content table with details -->
    <table class="content-table">
        <tr>
            <td class="no-right-border">Department</td>
            <td class="no-right-border no-left-border">Vehicle Type</td>
            <td class="no-right-border no-left-border">Plate No.</td>
            <td class="no-right-border no-left-border">Diagnosis/Problem</td>
            <td class="no-right-border no-left-border">Status</td>
            <td class="no-right-border no-left-border">Date IN</td>
            <td class="no-left-border">Days Elapsed</td>
        </tr>
        @forelse($table_requests as $item)
        <tr>
            <td class="no-right-border">{{ $item['department'] }}</td>
            <td class="no-right-border no-left-border">{{ $item['type'] }}</td>
            <td class="no-right-border no-left-border">{{ $item['number'] }}</td>
            <td class="no-right-border no-left-border">{{ $item['issues_or_concern'] }}</td>
            <td class="no-right-border no-left-border">{{ $item['status'] }}</td>
            <td class="no-right-border no-left-border">{{ $item['date_in'] }}</td>
            <td class="no-left-border">{{ $item['date_elapsed'] }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center">No record found.</td>
        </tr>
        @endforelse
    </table>
</body>

</html>