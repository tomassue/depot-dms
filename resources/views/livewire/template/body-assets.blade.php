<div>
    <!-- :) -->
</div>

@assets
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

<!-- summernote -->
<script src="{{ asset('summernote/summernote-bs5.js') }}"></script>

<!-- FilePond -->
<script src="{{ asset('jquery-filepond-master/filepond.min.js') }}"></script>
<script src="{{ asset('jquery-filepond-master/filepond-plugin-file-validate-type.js') }}"></script>
<script src="{{ asset('jquery-filepond-master/filepond-plugin-file-validate-size.js') }}"></script>
<script src="{{ asset('jquery-filepond-master/filepond-plugin-image-preview.js') }}"></script>
<script src="{{ asset('jquery-filepond-master/filepond.jquery.js') }}"></script>
@endassets

@script
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
@endscript