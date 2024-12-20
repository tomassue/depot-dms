<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <meta name="description" content="The DEPOT Document Management System is developed to assist the City Equipment Depot Office in managing requests such as vehicle repair and aircon cleaning/repair. Additionally, this system helps the office to track outgoing documents.">
    <meta name="keywords" content="DEPOT, Cagayan de Oro, RISE">
    <meta name="author" content="City Management Information Systems and Database Management">

    <META NAME="robots" CONTENT="noindex,nofollow">

    <title>{{ $title ?? 'Page Title' }}</title>

    <!-- jquery -->
    <script src="{{ asset('jquery-3.7.1/jquery-3.7.1.js') }}"></script>

    <!-- base:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/typicons/typicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}" />
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <!-- endinject -->

    <link rel="shortcut icon" href="{{ asset('assets/images/cdo-seal.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/cdo-seal.png') }}">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Grid.js -->
    <link rel="stylesheet" href="{{ asset('grid.js/mermaid.min.css') }}" />

    <!-- Virtual Select -->
    <link rel="stylesheet" href="{{ asset('virtual-select/virtual-select.min.css') }}">

    <!-- Flatpickr -->
    <link rel="stylesheet" href="{{ asset('flatpickr-v3/flatpickr.min.css') }}">

    <!-- Summernote -->
    <link href="{{ asset('summernote/summernote-bs5.css') }}" rel="stylesheet">

    <!-- FilePond -->
    <link href="{{ asset('jquery-filepond-master/filepond.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('jquery-filepond-master/filepond-plugin-image-preview.css') }}">

    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
        }

        /* Apply Montserrat font to the navbar explicitly */
        nav,
        footer {
            font-family: 'Montserrat', sans-serif !important;
        }

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

        /* Yellow */
        .bg-color-3 {
            background-color: rgb(182, 165, 89);
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

        /* Navbar */
        .navbar-breadcrumb {
            background: #66ABAC;
        }

        .navbar .navbar-brand-wrapper {
            background: #314e4f;
        }

        .navbar {
            border-bottom: 4px solid #314e4f;
        }

        /* End Navbar */

        .custom-invalid-feedback {
            color: #dc3545;
            margin-top: 0.25rem;
            font-size: 0.875em;
        }

        .disabled_input {
            background-color: #e9ecef;
            opacity: 0.5;
            pointer-events: none;
            cursor: not-allowed;
        }

        /* Modified virtual-select CSS */
        .vscomp-toggle-button {
            align-items: center;
            background-color: #fff;
            border: 1px solid #f3f3f3;
            cursor: pointer;
            display: flex;
            position: relative;
            width: 100%;
            height: 50px;
            padding: 0.875rem 1.375rem;
            font-size: 0.875rem;
        }

        .vscomp-wrapper:not(.has-value) .vscomp-value {
            opacity: .3 !important;
        }

        .text-bg-success {
            color: #ffffff !important;
            background-color: #507f50 !important;
        }

        .text-bg-info {
            color: #ffffff !important;
        }

        /* Custom Hover */
        .hover-bg:hover {
            background-color: #718e8f !important;
        }
    </style>
</head>

<body class="sidebar-icon-only">
    <div class="container-scroller">
        <!-- NAVBAR -->
        <livewire:template.navbar />

        <div class="container-fluid page-body-wrapper">

            @if (!Hash::check('password', Auth::user()->password))
            <!-- SIDEBAR -->
            <livewire:template.sidebar />
            @endif

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

    <livewire:template.body-assets />
</body>

</html>