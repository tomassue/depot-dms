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
            /* border: 1px solid black; */
        }

        #header td {
            /* border: 1px solid black; */
        }

        .job-order-cell {
            text-align: center;
            vertical-align: bottom;
            padding-bottom: 10px;
        }

        .job-order-title {
            font-size: 30px;
            font-weight: bolder;
            /* margin-left: -90px; */
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

        /* Watermark Styling */
        #content-container {
            position: relative;
        }

        #watermark {
            position: absolute;
            bottom: 17cm;
            left: 5cm;
            width: 8cm;
            height: 8cm;
            opacity: 0.2;
            z-index: -1;
            /* Behind the content */
        }

        .cut-here {
            border-top: 2px dashed #000;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div id="content-container">
        <!-- Watermark in the content -->
        <div id="watermark">
            <img src="data:image/png;base64,{{ $watermark }}" alt="depot-logo" width="290" />
        </div>

        <!-- Duplicate content for watermark inclusion -->
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
                    <img src="data:image/png;base64,{{ $rise_logo }}" alt="Rise Logo" width="84" />
                </td>
            </tr>
            <tr>
                <td class="job-order-cell" colspan="3">
                    <span class="job-order-title">JOB ORDER</span>
                </td>
            </tr>
        </table>

        <!-- Content table with details -->
        <table class="content-table">
            <tr>
                <td width="390px">JOB ORDER NO.: <span style="font-weight: normal;">{{ $job_order_no }}</span></td>
                <td>EQPT/VEHICLE TYPE: <span style="font-weight: normal">{{ $equipment_type }}</span></td>
            </tr>
            <tr>
                <td width="390px">DEPARTMENT: <span style="font-weight: normal">{{ $department }}</span></td>
                <td>MODEL: <span style="font-weight: normal">{{ $model }}</span></td>
            </tr>
            <tr>
                <td width="390px">DATE/TIME IN : <span style="font-weight: normal">{{ $date_and_time_in }}</span></td>
                <td>NO. : <span style="font-weight: normal">{{ $plate_no }}</span></td>
            </tr>
            <tr>
                <td width="390px">DATE/TIME OUT : <span style="font-weight: normal">{{ $date_and_time_out }}</span></td>
                <td></td>
            </tr>
        </table>

        <!-- Content table with details 2 -->
        <table class="content-table-2">
            <tr>
                <td class="no-right-border" width="300px">STATEMENT OF WORK</td>
                <td class="no-left-border" style="text-align:center;">MECHANICS ASSIGNED</td>
            </tr>
            <tr>
                <td class="no-right-border"><span style="font-weight: normal">{{ $issues_or_concern }}</span></td>
                <td class="no-left-border"><span style="font-weight: normal">{!! $mechanic !!}</span></td> <!-- rendering the mechanic field as raw HTML -->
            </tr>
        </table>

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
                <td class="no-top-border no-bottom-border no-left-border no-right-border" style="text-align:center; text-transform: uppercase; font-weight: normal;">{{ $signatory_name }}</td>
            </tr>
            <tr>
                <td style="text-align:center; vertical-align: top" class="no-bottom-border no-left-border no-right-border">NAME & SIGNATURE</td>
                <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
                <td style="text-align:center; vertical-align: top" class="no-bottom-border no-left-border no-right-border">CONTACT NUMBER</td>
                <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
                <td style="text-align:center; text-transform: uppercase; vertical-align: top" class="no-bottom-border no-left-border no-right-border">{{ $signatory_designation }}</td>
            </tr>
        </table>
    </div>

    <!-- Cut here line -->
    <div class="cut-here"></div>

    <div id="content-container">
        <!-- Watermark in the content -->
        <div id="watermark">
            <img src="data:image/png;base64,{{ $watermark }}" alt="depot-logo" width="290" />
        </div>

        <!-- Duplicate content for watermark inclusion -->
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
                    <img src="data:image/png;base64,{{ $rise_logo }}" alt="Rise Logo" width="84" />
                </td>
            </tr>
            <tr>
                <td class="job-order-cell" colspan="3">
                    <span class="job-order-title">JOB ORDER</span>
                </td>
            </tr>
        </table>

        <!-- Content table with details -->
        <table class="content-table">
            <tr>
                <td width="390px">JOB ORDER NO.: <span style="font-weight: normal;">{{ $job_order_no }}</span></td>
                <td>EQPT/VEHICLE TYPE: <span style="font-weight: normal">{{ $equipment_type }}</span></td>
            </tr>
            <tr>
                <td width="390px">DEPARTMENT: <span style="font-weight: normal">{{ $department }}</span></td>
                <td>MODEL: <span style="font-weight: normal">{{ $model }}</span></td>
            </tr>
            <tr>
                <td width="390px">DATE/TIME IN : <span style="font-weight: normal">{{ $date_and_time_in }}</span></td>
                <td>NO. : <span style="font-weight: normal">{{ $plate_no }}</span></td>
            </tr>
            <tr>
                <td width="390px">DATE/TIME OUT : <span style="font-weight: normal">{{ $date_and_time_out }}</span></td>
                <td></td>
            </tr>
        </table>

        <!-- Content table with details 2 -->
        <table class="content-table-2">
            <tr>
                <td class="no-right-border" width="300px">STATEMENT OF WORK</td>
                <td class="no-left-border" style="text-align:center;">MECHANICS ASSIGNED</td>
            </tr>
            <tr>
                <td class="no-right-border"><span style="font-weight: normal">{{ $issues_or_concern }}</span></td>
                <td class="no-left-border"><span style="font-weight: normal">{!! $mechanic !!}</span></td> <!-- rendering the mechanic field as raw HTML -->
            </tr>
        </table>

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
                <td class="no-top-border no-bottom-border no-left-border no-right-border" style="text-align:center; text-transform: uppercase; font-weight: normal;">{{ $signatory_name }}</td>
            </tr>
            <tr>
                <td style="text-align:center; vertical-align: top" class="no-bottom-border no-left-border no-right-border">NAME & SIGNATURE</td>
                <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
                <td style="text-align:center; vertical-align: top" class="no-bottom-border no-left-border no-right-border">CONTACT NUMBER</td>
                <td class="no-top-border no-bottom-border no-left-border no-right-border"></td>
                <td style="text-align:center; text-transform: uppercase; vertical-align: top" class="no-bottom-border no-left-border no-right-border">{{ $signatory_designation }}</td>
            </tr>
        </table>
    </div>
</body>

</html>