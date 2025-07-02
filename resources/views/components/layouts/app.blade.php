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
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
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

            Livewire.on('show-success-update-password-message-toast', (event) => {
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
                    title: "Password updated successfully."
                });
            });

            Livewire.on('show-success-reset-password-message-toast', (event) => {
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
                    title: "Password reset to default."
                });
            });

            Livewire.on('show-can-not-add-job-order-alert', (event) => {
                Swal.fire({
                    icon: "error",
                    title: "Unable to add Job Order",
                    text: "Please resolve the pending job order to proceed."
                });
            });

            Livewire.on('show-assign-division-chief-toast', (event) => {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Please assign a Division Chief to your signatories list."
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