<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>{{ $title ?? 'Page Title' }}</title>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

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

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Grid.js -->
    <link rel="stylesheet" href="{{ asset('grid.js/mermaid.min.css') }}" />

    <!-- Sweetalert2 -->
    <link rel="stylesheet" href="{{ asset('sweetalert2/sweetalert2.all.min.css') }}">

    <!-- Virtual Select -->
    <link rel="stylesheet" href="{{ asset('virtual-select/virtual-select.min.css') }}">

    <!-- Flatpickr -->
    <link rel="stylesheet" href="{{ asset('flatpickr-v3/flatpickr.min.css') }}">

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

        /* Navbar */
        .navbar-breadcrumb {
            background: #66ABAC;
        }

        /* End Navbar */

        /* Buttons */
        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: #66ABAC;
            --bs-btn-border-color: #66ABAC;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #7043a4;
            --bs-btn-hover-border-color: #6a3f9a;
            --bs-btn-focus-shadow-rgb: 150, 105, 202;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #6a3f9a;
            --bs-btn-active-border-color: #633b91;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: #66ABAC;
            --bs-btn-disabled-border-color: #66ABAC;
        }

        /* End Buttons */

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
            border: 1px solid #ddd;
            cursor: pointer;
            display: flex;
            position: relative;
            width: 100%;
            height: 50px;
            padding: 0.875rem 1.375rem;
            font-size: 0.875rem;
        }
    </style>
</head>

<body class="sidebar-icon-only">
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

    <!-- Grid.js -->
    <script src="{{ asset('grid.js/gridjs.umd.js') }}"></script>

    <!-- Sweetalert2 -->
    <script src="{{ asset('sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- Virtual Select -->
    <script src="{{ asset('virtual-select/virtual-select.min.js') }}"></script>

    <!-- flatpickr.js -->
    <script src="{{ asset('flatpickr-v3/flatpickr.js') }}"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-success-save-message-toast', (event) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Saved successfully."
                });
            });

            Livewire.on('show-success-update-message-toast', (event) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Record updated successfully."
                });
            });

            Livewire.on('show-deactivated-message-toast', (event) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Record deactivated successfully."
                });
            });

            Livewire.on('show-activated-message-toast', (event) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Record activated successfully."
                });
            });

            Livewire.on('show-error-duplicate-entry-message-toast', (event) => {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Duplicate entry."
                });
            });

            Livewire.on('show-something-went-wrong-toast', (event) => {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong."
                });
            });

            Livewire.on('show-overlapping-venu-request-toast', (event) => {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's already a request made for this venue during the specified time."
                });
            });

            /* -------------------------------------------------------------------------- */

            /**
             * NOTE
             * Interactions for the history modal is made global because history modal would likely come accross in every pages.
             * I also made it as an independent file. I mean we will just `include` it if it's applicable.
             * 
             * LINK - resources\views\livewire\history_modal\history_modal.blade.php
             * LINK - resources\views\livewire\incoming\documents.blade.php#96
             */

            Livewire.on('show-historyModal', (event) => {
                $('#historyModal').modal('show');
            });

            Livewire.on('hide-historyModal', (event) => {
                $('#historyModal').modal('hide');
            });

            /* -------------------------------------------------------------------------- */
        });
    </script>
</body>

</html>