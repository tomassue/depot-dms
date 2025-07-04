<div>
    @include('livewire.template.loading-spinner')

    <div class="row card-group">
        <div class="col-md-6 col-lg-6 col-xl-3 my-2">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                        <div>
                            <p class="mb-2 text-md-center text-lg-left">Total J.O.</p>
                            <h1 class="mb-0">{{ $total }}</h1>
                        </div>
                        <i class="bx bx-file bx-lg text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-xl-3 my-2">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                        <div>
                            <p class="mb-2 text-md-center text-lg-left">Pending J.O.</p>
                            <h1 class="mb-0">{{ $pending }}</h1>
                        </div>
                        <i class="bx bx-loader-circle bx-lg text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-xl-3 my-2">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                        <div>
                            <p class="mb-2 text-md-center text-lg-left">Accomplished J.O.</p>
                            <h1 class="mb-0">{{ $done }}</h1>
                        </div>
                        <i class="bx bx-badge-check bx-lg text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-xl-3 my-2">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                        <div>
                            <p class="mb-2 text-md-center text-lg-left">Referred J.O.</p>
                            <h1 class="mb-0">{{ $referred }}</h1>
                        </div>
                        <i class="bx bx-send bx-lg text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 grid-margin stretch-card flex-column">
            <div class="card">
                <div class="card-body">
                    <div id="table_pending_job_orders" wire:ignore></div>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.modals.incoming.jobOrderModal')
    @include('livewire.modals.incoming.jobOrderStatusUpdateModal')
</div>

@script
<script>
    $wire.on('showJobOrderModal', () => {
        $('#jobOrderModal').modal('show');
    });

    $wire.on('hideJobOrderModal', () => {
        $('#jobOrderModal').modal('hide');
    });

    $wire.on('showStatusUpdateModal', () => {
        $('#jobOrderModal').modal('hide');
        $('#statusUpdate').modal('show');
    });

    $wire.on('hideStatusUpdateModal', () => {
        $('#statusUpdate').modal('hide');
        $('#jobOrderModal').modal('show');
    });

    $wire.on('hideBothJobOrderModalAndStatusUpdateModal', () => {
        $('#statusUpdate').modal('hide');
        $('#incomingModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($table_pending_job_orders); // Ensure that $mechanics includes 'deleted_at' field
    const table_pending_job_orders = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Job Order No.",
            "Office",
            "Category",
            "Sub-category",
            "Model",
            "Type",
            "No.",
            "Issue/Concern/Request",
            {
                name: "Status",
                width: "150px"
            },
            {
                name: "Actions",
                sort: false,
                formatter: (cell, row) => {
                    const id = row.cells[0].data;
                    return gridjs.html(`
                        @can('read incoming')
                        <button class="btn btn-success btn-sm btn-icon-text me-3" title="Edit" style="display: ${row.cells[9].data === 'Pending' ? '' : 'none'}" wire:click="readJobOrder('${id}')">
                            <i class="bx bx-edit bx-sm"></i>
                        </button>
                        @endcan
                    `);
                }
            }
        ],
        search: true,
        pagination: {
            limit: 10
        },
        sort: true,
        resizable: true,
        data: () => {
            return new Promise(resolve => {
                setTimeout(() =>
                    resolve(
                        data.map(item => [
                            item.id, // Primary Key
                            item.id,
                            item.incoming_request.office.name,
                            item.category_names,
                            item.sub_category_names, // Access the sub_category_names attribute
                            item.incoming_request.model.name,
                            item.incoming_request.type.name, // Access nested property
                            item.incoming_request.number,
                            item.issue_or_concern,
                            item.status.name
                        ])
                    ), 1000);
            });
        }
    }).render(document.getElementById("table_pending_job_orders"));

    $wire.on('refresh-table-incoming-requests', (data) => {
        table_pending_job_orders.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id, // Primary Key
                                item.id,
                                item.incoming_request.office.name,
                                item.category_names,
                                item.sub_category_names, // Access the sub_category_names attribute
                                item.incoming_request.model.name,
                                item.incoming_request.type.name, // Access nested property
                                item.incoming_request.number,
                                item.issue_or_concern,
                                item.status.name
                            ])
                        ), 1000);
                });
            }
        }).forceRender();
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#status-select',
        options: @json($statuses),
        search: true,
        maxWidth: '100%'
    });

    let ref_status_id = document.querySelector('#status-select');
    ref_status_id.addEventListener('change', () => {
        let data = ref_status_id.value;
        @this.set('ref_status_id', data);
    });

    $wire.on('set-status-select', (key) => {
        document.querySelector('#status-select').setValue(key[0]);
    });

    $wire.on('set-status-select-pending', () => {
        document.querySelector('#status-select').setValue(1);
    });

    $wire.on('reset-status-select', (key) => {
        document.querySelector('#status-select').reset(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#category-select',
        options: @json($categories),
        search: true,
        multiple: true,
        maxWidth: '100%'
    });

    let ref_category_id = document.querySelector('#category-select');
    ref_category_id.addEventListener('change', () => {
        let data = ref_category_id.value;
        @this.set('ref_category_id', data);
    });

    $wire.on('set-category-select', (key) => {
        document.querySelector('#category-select').setValue(key[0]);
    });

    $wire.on('reset-category-select', (key) => {
        document.querySelector('#category-select').reset(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#sub-category-select',
        options: @json($sub_categories),
        search: true,
        maxWidth: '100%',
        multiple: true
    });

    let ref_sub_category_id = document.querySelector('#sub-category-select');
    ref_sub_category_id.addEventListener('change', () => {
        let data = ref_sub_category_id.value;
        @this.set('ref_sub_category_id', data);
    });

    $wire.on('set-sub-category-select', (key) => {
        document.querySelector('#sub-category-select').setValue(key[0]);
    });

    $wire.on('reset-sub-category-select', (key) => {
        document.querySelector('#sub-category-select').reset(key[0]);
    });

    $wire.on('refresh-sub-category-select-options', (key) => {
        document.querySelector('#sub-category-select').setOptions(key.options);
        document.querySelector('#sub-category-select').setValue(key.selected);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#type-of-repair-select',
        options: @json($type_of_repairs),
        search: true,
        maxWidth: '100%'
    });

    let ref_type_of_repair_id = document.querySelector('#type-of-repair-select');
    ref_type_of_repair_id.addEventListener('change', () => {
        let data = ref_type_of_repair_id.value;
        @this.set('ref_type_of_repair_id', data);
    });

    $wire.on('set-type-of-repair-select', (key) => {
        document.querySelector('#type-of-repair-select').setValue(key[0]);
    });

    $wire.on('reset-type-of-repair-select', (key) => {
        document.querySelector('#type-of-repair-select').reset(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#mechanics-select',
        options: @json($mechanics),
        search: true,
        multiple: true,
        maxWidth: '100%'
    });

    let ref_mechanics = document.querySelector('#mechanics-select');
    ref_mechanics.addEventListener('change', () => {
        let data = ref_mechanics.value;
        @this.set('ref_mechanics', data);
    });

    $wire.on('set-mechanics-select', (key) => {
        document.querySelector('#mechanics-select').setValue(key[0]);
    });

    $wire.on('reset-mechanics-select', (key) => {
        document.querySelector('#mechanics-select').reset(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#location-select',
        options: @json($locations),
        search: true,
        maxWidth: '100%'
    });

    let ref_location_id = document.querySelector('#location-select');
    ref_location_id.addEventListener('change', () => {
        let data = ref_location_id.value;
        @this.set('ref_location_id', data);
    });

    $wire.on('set-location-select', (key) => {
        document.querySelector('#location-select').setValue(key[0]);
    });

    $wire.on('reset-location-select', (key) => {
        document.querySelector('#location-select').reset(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    var date_and_time_in = $(".date_and_time_in").flatpickr({
        enableTime: true,
        altInput: true, // altInput hides your original input and creates a new one. Upon date selection, the original input will contain a Y-m-d... string, while the altInput will display the date in a more legible, customizable format.
        altFormat: 'F j, Y h:i K',
        dateFormat: "Y-m-d H:i", // display in 12-hour format
        onChange: function(selectedDates, dateStr) {
            @this.set('date_and_time_in', dateStr);
        }
    });

    $wire.on('set-date-and-time-in', (key) => {
        date_and_time_in.setDate(key[0]);
        @this.set('date_and_time_in', key[0]);
    });

    $wire.on('reset-date-and-time-in', () => {
        date_and_time_in.clear();
    });

    /* -------------------------------------------------------------------------- */

    var date_and_time_out = $(".date_and_time_out").flatpickr({
        enableTime: true,
        altInput: true, // altInput hides your original input and creates a new one. Upon date selection, the original input will contain a Y-m-d... string, while the altInput will display the date in a more legible, customizable format.
        altFormat: 'F j, Y h:i K',
        dateFormat: "Y-m-d H:i", // display in 12-hour format
        onChange: function(selectedDates, dateStr) {
            @this.set('date_and_time_out', dateStr);
        }
    });

    $wire.on('set-date-and-time-out', (key) => {
        date_and_time_out.setDate(key[0]);
        @this.set('date_and_time_out', key[0]);
    });

    $wire.on('reset-date-and-time-out', () => {
        date_and_time_out.clear();
    });

    /* -------------------------------------------------------------------------- */

    $('#issue-or-concern-summernote').summernote({
        toolbar: false,
        disableDragAndDrop: true,
        tabsize: 2,
        height: 120,
        callbacks: {
            onChange: function(contents, $editable) {
                // Create a temporary div element to strip out HTML tags
                var plainText = $('<div>').html(contents).text();
                @this.set('issue_or_concern', plainText);
            }
        }
    });

    $wire.on('set-issue-or-concern-summernote', (key) => {
        $('#issue-or-concern-summernote').summernote('code', key[0]);
    });

    $wire.on('reset-issue-or-concern-summernote', () => {
        $('#issue-or-concern-summernote').summernote('reset');
    });

    $wire.on('set-issue-or-concern-summernote-disabled', (key) => {
        $('#issue-or-concern-summernote').summernote('code', key[0]);
        $('#issue-or-concern-summernote').summernote('disable');
    });

    /* -------------------------------------------------------------------------- */

    $('#findings-summernote').summernote({
        toolbar: false,
        disableDragAndDrop: true,
        tabsize: 2,
        height: 120,
        callbacks: {
            onChange: function(contents, $editable) {
                // Create a temporary div element to strip out HTML tags
                var plainText = $('<div>').html(contents).text();
                @this.set('findings', plainText);
            }
        }
    });

    $wire.on('set-findings-summernote', (key) => {
        $('#findings-summernote').summernote('code', key[0]);
    });

    $wire.on('reset-findings-summernote', () => {
        $('#findings-summernote').summernote('reset');
    });

    $wire.on('set-findings-summernote-disabled', (key) => {
        $('#findings-summernote').summernote('code', key[0]);
        $('#findings-summernote').summernote('disable');
    });

    /* -------------------------------------------------------------------------- */

    // Register the plugin 
    FilePond.registerPlugin(FilePondPluginFileValidateType); // for file type validation
    FilePond.registerPlugin(FilePondPluginFileValidateSize); // for file size validation
    FilePond.registerPlugin(FilePondPluginImagePreview); // for image preview

    // Turn input element into a pond with configuration options
    $('.my-pond-files').filepond({
        // required: true,
        allowFileTypeValidation: true,
        acceptedFileTypes: ['image/jpg', 'image/png', 'application/pdf'],
        labelFileTypeNotAllowed: 'File of invalid type',
        allowFileSizeValidation: true,
        maxFileSize: '10MB',
        labelMaxFileSizeExceeded: 'File is too large',
        server: {
            // This will assign the data to the files[] property.
            process: (fieldName, file, metadata, load, error, progress, abort) => {
                @this.upload('files', file, load, error, progress);
            },
            revert: (uniqueFileId, load, error) => {
                @this.removeUpload('files', uniqueFileId, load, error);
            }
        }
    });

    $wire.on('reset-my-pond-files', () => {
        $('.my-pond-files').each(function() {
            $(this).filepond('removeFiles');
        });
    });

    $wire.on('open-file', (url) => {
        window.open(event.detail.url, '_blank'); // Open the signed URL in a new tab
    });

    $wire.on('confirm-file-deletion', (fileId) => {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.removeFile(fileId);
            }
        });
    });
</script>
@endscript