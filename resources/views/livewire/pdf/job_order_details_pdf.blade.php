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

        .job-order-cell {
            text-align: center;
            vertical-align: bottom;
            padding-bottom: 10px;
        }

        .job-order-title {
            font-size: 40px;
            font-weight: bolder;
            margin-left: -90px;
        }

        table.content-table {
            width: 90%;
            /* Adjust width as needed */
            margin: 20px auto;
            /* Centers the table horizontally */
            border-collapse: collapse;
        }

        table.content-table td {
            padding: 5px;
            /* border: 1px solid #ccc; */
            font-weight: bold;
            /* Optional for better visibility */
        }

        table.content-table-2 {
            width: 100%;
            /* Adjust width as needed */
            margin: 20px auto;
            /* Centers the table horizontally */
            border-collapse: collapse;
        }

        table.content-table-2 td {
            padding: 10px;
            border: 1px solid #000000;
            font-weight: bold;
            /* Optional for better visibility */
        }

        table.content-table-3 {
            width: 90%;
            /* Adjust width as needed */
            margin: 20px auto;
            /* Centers the table horizontally */
            border-collapse: collapse;
        }

        table.content-table-3 td {
            padding: 10px;
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
            bottom: 12cm;
            left: 3.7cm;

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
            <td class="job-order-cell">
                <span class="job-order-title">JOB ORDER</span>
            </td>
            <td style="text-align: right;">
                <img src="data:image/png;base64,{{ $rise_logo }}" alt="Rise Logo" width="120" />
            </td>
        </tr>
    </table>

    <br>

    <!-- Content table with details -->
    <table class="content-table">
        <tr>
            <td width="300px">JOB ORDER NO.: <span style="font-weight: normal;">{{ $job_order_no }}</span></td>
            <td>EQPT/VEHICLE TYPE: <span style="font-weight: normal">{{ $equipment_type }}</span></td>
        </tr>
        <tr>
            <td width="300px">DEPARTMENT: <span style="font-weight: normal">{{ $department }}</span></td>
            <td>MODEL: <span style="font-weight: normal">{{ $model }}</span></td>
        </tr>
        <tr>
            <td width="300px">DATE/TIME IN : <span style="font-weight: normal">{{ $date_and_time_in }}</span></td>
            <td>PLATE NO. : <span style="font-weight: normal">{{ $plate_no }}</span></td>
        </tr>
        <tr>
            <td width="300px">DATE/TIME OUT : <span style="font-weight: normal">{{ $date_and_time_out }}</span></td>
            <td></td>
        </tr>
    </table>

    <br>

    <!-- Content table with details 2 -->
    <table class="content-table-2">
        <tr>
            <td class="no-right-border" width="300px">STATEMENT OF WORK</td>
            <td class="no-left-border" style="text-align:center;">MECHANICS ASSIGNED</td>
        </tr>
        <tr>
            <td class="no-right-border"><span style="font-weight: normal">{{ $issues_or_concern }}</span></td>
            <td class="no-left-border"><span style="font-weight: normal">{{ $mechanic }}</span></td>
        </tr>
    </table>

    <br>
    <br>
    <br>

    <!-- Content table with details 3 -->
    <table class="content-table-3">
        <tr>
            <td width="190px" class="no-top-border no-bottom-border no-left-border no-right-border">REQUESTED BY: </td>
            <td width="2px" class="no-top-border no-bottom-border no-left-border no-right-border"></td>
            <td width="120px" class="no-top-border no-bottom-border no-left-border no-right-border"></td>
            <td width="10px" class="no-top-border no-bottom-border no-left-border no-right-border"></td>
            <td style="text-align:center;" class="no-top-border no-bottom-border no-left-border no-right-border">JOB AUTHORIZED / APPROVED BY: </td>
        </tr>
        <tr>
            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
        </tr>
        <tr height="200px;">
            <td class="no-top-border no-bottom-border no-left-border no-right-border" style="text-align:center; text-transform: uppercase; font-weight: normal;">{{ $name }}</td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border" style="text-align:center; font-weight: normal;">{{ $contact_number }}</td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border" style="text-align:center; text-transform: uppercase; font-weight: normal;"></td>
        </tr>
        <tr>
            <td style="text-align:center;" class="no-bottom-border no-left-border no-right-border">NAME & SIGNATURE</td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
            <td style="text-align:center;" class="no-bottom-border no-left-border no-right-border">CONTACT NUMBER</td>
            <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
            <td style="text-align:center;" class="no-bottom-border no-left-border no-right-border">SECTION CHIEF</td>
        </tr>
    </table>
</body>

</html>