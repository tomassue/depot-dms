<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>{{ $title ?? 'Page Title' }}</title>

    <!-- base:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/typicons/typicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}" />
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

    <style>
        /* Random Profile Picture */
        .profile-picture {
            width: 50px;
            /* Adjust the size as needed */
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            /* Adjust the font size as needed */
            font-weight: bold;
            color: white;
            background-color: #3498db;
            /* Default color if dynamic color is not set */
        }

        /* Optional: Dynamic color classes */
        /* Teal */
        .bg-color-1 {
            background-color: #1abc9c;
        }

        /* Red */
        .bg-color-2 {
            background-color: #e74c3c;
        }

        /* Purple */
        .bg-color-3 {
            background-color: #9b59b6;
        }

        /* Orange */
        .bg-color-4 {
            background-color: #f39c12;
        }

        /* Green */
        .bg-color-5 {
            background-color: #2ecc71;
        }

        /* End Random Profile Picture */

        /* Cursor */
        .pointer {
            cursor: pointer;
        }

        /* End Cursor */
    </style>
</head>

<body>
    <div class="container-scroller">
        <!-- NAVBAR -->
        <livewire:template.navbar />

        <div class="container-fluid page-body-wrapper">
            <!-- SIDEBAR -->
            <livewire:template.sidebar />

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    {{ $slot }}
                </div>
                <!-- content-wrapper ends -->

                <!-- FOOTER -->
                <livewire:template.footer />
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <!-- base:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <!-- End custom js for this page-->
</body>

</html>