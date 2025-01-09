<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment/Vehicle Releasing Print</title>
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

        .job-order-cell {
            text-align: center;
            vertical-align: bottom;
            padding-bottom: 10px;
        }

        .job-order-title {
            font-size: 21px;
            font-weight: bolder;
            margin-left: -90px;
        }

        table.content-table {
            width: 100%;
            /* Adjust width as needed */
            margin: 20px auto;
            /* Centers the table horizontally */
            border-collapse: collapse;
            /* border: solid 1px black; */
        }

        table.content-table td {
            padding: 5px;
            border: 1px solid black;
            font-weight: bold;
            /* Optional for better visibility */
        }

        .no-top-border {
            border-top: unset !important;
        }

        .no-bottom-border {
            border-bottom: unset !important;
        }

        .no-right-border {
            border-right: unset !important;
        }

        .no-left-border {
            border-left: unset !important;
        }

        #watermark {
            position: fixed;

            /** 
                Set a position in the page for your image
                This should center it vertically
            **/
            /* bottom: 12cm; */
            bottom: 17cm;
            left: 5.6cm;

            /** Change image dimensions**/
            width: 8cm;
            height: 8cm;

            /** Your watermark should be behind every content**/
            z-index: -1000;
            opacity: 0.2;
        }
    </style>
</head>

<body>
    <div id="watermark">
        <img src="data:image/png;base64,{{ $watermark }}" alt="depot-logo" width="290" />
    </div>

    <!-- Header table with logo and title -->
    <table id="header">
        <tr>
            <td style="text-align: left;">
                <img src="data:image/png;base64,{{ $cdo_full }}" alt="CDO Seal" width="140" />
            </td>
            <td class="job-order-cell">
                <span class="job-order-title">EQUIPMENT/VEHICLE RELEASING</span>
            </td>
            <td style="text-align: right;">
                <img src="data:image/png;base64,{{ $rise_logo }}" alt="Rise Logo" width="84" />
            </td>
        </tr>
    </table>

    <div style="padding-top: 40px;">
        <span style="font-size: 15px; font-weight: bold;">
            COMMENTS/RECOMMENDATION:
        </span>
    </div>

    <div style="padding-top: 10px; text-indent: 50px; text-align: justify;">
        <span style="font-size: 14px; text-decoration: underline; display: inline-block; width: 100%;">
            {{ $job_order->remarks }}
        </span>
    </div>

    <!-- Wrapper for remarks and table -->
    <div style="margin-top: 50px; position: relative;">
        <table class="content-table">
            <tr>
                <td style="border: unset;" width="35%">Equipment/Vehicle Received by:</td>
                <td style="border: unset;" width="1%"></td>
                <td style="border: unset;" width="15%"></td>
                <td style="border: unset;" width="5%"></td>
                <td style="border: unset;">Approved Release by:</td>
            </tr>
            <tr>
                <td style="border: unset; text-align: center; vertical-align: bottom; font-weight: normal;" height="25">{{ $job_order->claimed_by }}</td>
                <td style="border: unset;" width="1%"></td>
                <td style="border: unset; text-align: center; vertical-align: bottom; font-weight: normal;" height="25"></td>
                <td style="border: unset;" width="5%"></td>
                <td style="border: unset; text-align: center; vertical-align: bottom; font-weight: normal;" height="25">
                    {{ $division_chief->name }}
                </td>
            </tr>
            <tr>
                <td style="border-bottom: none; border-right: none; border-left: none; text-align: center;">Driver/Operator</td>
                <td style="border: unset;" width="1%"></td>
                <td style="border-bottom: none; border-right: none; border-left: none; text-align: center;">Date Received</td>
                <td style="border: unset;" width="5%"></td>
                <td style="border-bottom: none; border-right: none; border-left: none; text-align: center;">
                    {{ $division_chief->designation }}
                    <br>
                    Chief, Repair & Maintenance Division
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 10%; position: relative;">
        <div style="text-align: center; border-top: 1px dashed black; margin: 20px 0; position: relative;">
            <span style="background: white; padding: 0 10px; position: absolute; top: -10px; left: 50%; transform: translateX(-50%); font-size: 12px; font-weight: bold;">
                Cut Here
            </span>
        </div>
    </div>

</body>

</html>