<div>
    @include('livewire.template.loading-spinner')

    <div style="display: {{ $page == 1 ? '' : 'none' }}">
        <div class="card mb-2">
            <div class="card-body">
                @can('create incoming')
                <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                    <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showIncomingModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
                </div>
                @endcan
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="col-md-12 my-2">
                    <div id="table_incoming_requests" wire:ignore></div>
                </div>
            </div>
        </div>
    </div>

    <!-- incomingModal -->
    <div class="modal fade" id="incomingModal" tabindex="-1" aria-labelledby="incomingModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="incomingModalLabel">{{ $editMode ? 'Update' : 'Add' }} Request Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateIncomingRequest' : 'createIncomingRequest' }}" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputReferenceNo">Reference No.</label>
                                    <input type="text" class="form-control disabled_input" id="inputReferenceNo" wire:model="reference_no">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputOffice">Office</label>
                                    <div id="office-select" wire:ignore></div>
                                    @error('ref_office_id')
                                    <div class="custom-invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputIncomingRequestType">Equipment Type</label>
                                    <div id="incoming-request-types-select" wire:ignore></div>
                                    @error('ref_incoming_request_types_id')
                                    <div class="custom-invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <p class="text-muted">
                            Other Details
                        </p>
                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- Row for Type and Model -->
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="inputType">Type (Equipment / Vehicle)</label>
                                        <div id="type-select" wire:ignore></div>
                                        @error('ref_types_id')
                                        <div class="custom-invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputModel">Model (Equipment / Vehicle)</label>
                                        <div id="model-select" wire:ignore></div>
                                        <div id="model-select-2" wire:ignore></div>
                                        @error('ref_models_id')
                                        <div class="custom-invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Row for Number and Mileage -->
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="inputNumber">Number (Equipment / Vehicle)</label>
                                        <input type="text" class="form-control @error('number') is-invalid @enderror" id="inputNumber" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\-]/g, '')" wire:model="number">
                                        @error('number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                    <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Save' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div style="display: {{ $page == 2 ? '' : 'none' }}">
        <div class="card mb-2">
            <div class="card-body">
                <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-start">
                    <button type="button" class="btn btn-secondary btn-icon-text" wire:click="setPage">
                        <i class='bx bx-arrow-back bx-xs'></i>
                    </button>
                </div>

                <div class="col-md-12 my-2">
                    <form class="form-sample">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row" style="margin-bottom: unset;">
                                    <label class="col-sm-3 col-form-label">Reference No.</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="reference_no" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Office</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="ref_office_id" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="card-description">
                            Other Details
                        </p>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row" style="margin-bottom: unset;">
                                    <label class="col-sm-3 col-form-label">Type</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="ref_types_id" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row" style="margin-bottom: unset;">
                                    <label class="col-sm-3 col-form-label">Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="number" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row" style="margin-bottom: unset;">
                                    <label class="col-sm-3 col-form-label">Model</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="ref_models_id_2" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @can('create incoming')
                <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                    <button class="btn btn-primary btn-md btn-icon-text" wire:click="jobOrderModal"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
                </div>
                @endcan

                <div class="col-md-12 my-2">
                    <div id="table_job_orders" wire:ignore></div>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.modals.incoming.jobOrderModal')

    <!-- statusUpdate -->
    <div class="modal fade" id="statusUpdate" tabindex="-1" aria-labelledby="statusUpdateLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="statusUpdateLabel">Status Update</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear3"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="updateJobOrder" novalidate>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="inputDateTime">Date & Time (Out)</label>
                                <div wire:ignore>
                                    <input class="form-control date_and_time_out">
                                </div>
                                @error('date_and_time_out')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="inputTotalRepairTime">Total repair time</label>
                                <input type="text" class="form-control @error('total_repair_time') is-invalid @enderror disabled_input" id="inputTotalRepairTime" wire:model="total_repair_time">
                                @error('total_repair_time')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="inputClaimedBy">Claimed by</label>
                                <input type="text" class="form-control @error('claimed_by') is-invalid @enderror" id="inputClaimedBy" wire:model="claimed_by">
                                @error('claimed_by')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="inputRemarks">Remarks</label>
                                <input type="text" class="form-control @error('remarks') is-invalid @enderror" id="inputRemarks" wire:model="remarks">
                                @error('remarks')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear3">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jobOrderDetails -->
    <div class="modal fade" id="jobOrderDetails" tabindex="-1" aria-labelledby="jobOrderDetailsModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="jobOrderDetailsModalLabel">Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear2"></button>
                </div>
                <div class="modal-body">
                    <!-- <p class="card-description">
                        Equipment Details
                    </p>
                    <hr> -->
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputType">Type</label>
                            <input type="text" class="form-control disabled_input" id="inputType" wire:model="ref_office_id">
                        </div>
                        <div class="col-md-6">
                            <label for="inputModel">Model</label>
                            <input type="text" class="form-control disabled_input" id="inputModel" wire:model="ref_models_id_2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputNumber">Number</label>
                            <input type="text" class="form-control disabled_input" id="inputNumber" wire:model="number">
                        </div>
                    </div>
                    <p class="card-description">
                        Equipment Details
                    </p>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputJobOrder">Job Order</label>
                            <input type="text" class="form-control disabled_input" id="inputJobOrder" wire:model="job_order_no">
                        </div>
                        <div class="col-md-6">
                            <label for="selectModel">Status</label>
                            <input type="text" class="form-control disabled_input" id="selectModel" wire:model="ref_status_id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputMileage">Mileage / Odometer Reading</label>
                            <input type="text" class="form-control disabled_input" id="inputMileage" wire:model="mileage">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputPersonInCharge">Driver in charge</label>
                            <input type="text" class="form-control disabled_input" id="inputPersonInCharge" wire:model="person_in_charge">
                        </div>
                        <div class="col-md-6">
                            <label for="inputContactNumber">Contact Number</label>
                            <input type="text" class="form-control disabled_input" id="inputContactNumber" wire:model="contact_number">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="selectCategory">Category</label>
                            <input type="text" class="form-control disabled_input" id="selectCategory" wire:model="ref_category_id">
                        </div>
                        <div class="col-md-6">
                            <label for="selectTypeOfRepair">Type of Repair</label>
                            <input type="text" class="form-control disabled_input" id="selectTypeOfRepair" wire:model="ref_type_of_repair_id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="selectSubCategory">Sub-category</label>
                            <input type="text" class="form-control disabled_input" id="selectSubCategory" wire:model="ref_sub_category_id_2">
                        </div>
                        <div class="col-md-6">
                            <label for="selectMechanics">Mechanics Assigned</label>
                            <input type="text" class="form-control disabled_input" id="selectMechanics" wire:model="ref_mechanics">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="selectLocation">Location</label>
                            <input type="text" class="form-control disabled_input" id="selectLocation" wire:model="ref_location_id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="inputIssueOrConcern">Issues or Concerns</label>
                            <textarea class="form-control disabled_input" id="exampleTextarea1" rows="4" spellcheck="false" wire:model="issue_or_concern"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputDateTime">Date & Time (Out)</label>
                            <input type="text" class="form-control disabled_input" id="inputDateTime" wire:model="date_and_time_out">
                        </div>
                        <div class="col-md-6">
                            <label for="inputTotalRepairTime">Total repair time</label>
                            <input type="text" class="form-control disabled_input" id="inputTotalRepairTime" wire:model="total_repair_time">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="inputClaimedBy">Claimed by</label>
                            <input type="text" class="form-control disabled_input" id="inputJobOrder" wire:model="claimed_by">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="inputRemarks">Remarks</label>
                            <textarea class="form-control disabled_input" id="exampleTextarea1" rows="4" spellcheck="false" wire:model="remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear2">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- assignSignatory -->
    <div class="modal fade" id="assignSignatory" tabindex="-1" aria-labelledby="assignSignatoryModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="assignSignatoryModalLabel">Assign Signatory</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear2"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="selectSignatory">Signatory <small class="text-info fst-italic">(Please select a signatory first before clicking "print".)</small></label>
                            <div id="signatories-select" wire:ignore></div>
                            @error('ref_signatories_id')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" wire:click="printJobOrder('{{$job_order_no}}')">Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jobOrderDetailsPDF -->
    <div class="modal fade" id="jobOrderDetailsPDF" tabindex="-1" aria-labelledby="jobOrderDetailsPDFModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="jobOrderDetailsPDFModalLabel">PDF</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear2"></button>
                </div>
                <div class="modal-body">
                    <embed src="{{ $job_order_details_pdf }}" type="application/pdf" width="100%" height="700px">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear2">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.modals.incoming.jobOrderLogsModal')
</div>

@script
<script>
    $wire.on('showIncomingModal', () => {
        $('#incomingModal').modal('show');

        if (@this.editMode) {
            return;
        }

        $wire.generateReferenceNo(); // Call Livewire method
    });

    $wire.on('hideIncomingModal', () => {
        $('#incomingModal').modal('hide');
    });

    $wire.on('showJobOrderModal', () => {
        $('#jobOrderModal').modal('show');

        if (@this.editMode) {
            return;
        }

        $wire.generateJobOrderNo();
    });

    $wire.on('hideJobOrderModal', () => {
        $('#jobOrderModal').modal('hide');
    });

    // $wire.on('send-reference-no', (key) => {
    //     $wire.generateJobOrder(key);
    // });

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

    $wire.on('showJobOrderDetailsModal', () => {
        $('#jobOrderDetails').modal('show');
    });

    $wire.on('showAssignSignatoryModal', () => {
        $('#assignSignatory').modal('show');
    });

    $wire.on('showJobOrderDetailsPDF', () => {
        $('#assignSignatory').modal('hide');
        $('#jobOrderDetailsPDF').modal('show');
    });

    $wire.on('showJobOrderLogsModal', () => {
        $('#jobOrderLogsModal').modal('show');
    });

    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */

    const data = @json($incoming_requests); // Ensure that $mechanics includes 'deleted_at' field
    const table_incoming_requests = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Reference No.",
            "Office/Department",
            "Equipment",
            "Type",
            "Model",
            "No.",
            {
                name: "Actions",
                formatter: (cell, row) => {
                    const id = row.cells[0].data;
                    return gridjs.html(`
                        @can('read incoming')
                        <button class="btn btn-info btn-sm btn-icon-text me-3" title="View" wire:click="readJobOrders('${id}')"><i class="bx bx-detail bx-sm"></i></button>
                        <button class="btn btn-success btn-sm btn-icon-text me-3" title="Edit" wire:click="readIncomingRequest('${id}')"><i class="bx bx-edit bx-sm"></i></button>
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
        data: () => {
            return new Promise(resolve => {
                setTimeout(() =>
                    resolve(
                        data.map(item => [
                            item.id,
                            item.reference_no,
                            item.office.name,
                            item.incoming_request_type.name,
                            item.type.name,
                            item.model.name,
                            item.number
                        ])
                    ), 1000);
            });
        }
    }).render(document.getElementById("table_incoming_requests"));

    $wire.on('refresh-table-incoming-requests', (data) => {
        table_incoming_requests.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id,
                                item.reference_no,
                                item.office.name,
                                item.incoming_request_type.name,
                                item.type.name,
                                item.model.name,
                                item.number
                            ])
                        ), 1000);
                });
            }
        }).forceRender();
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#office-select',
        options: @json($offices),
        search: true,
        maxWidth: '100%'
    });

    let ref_office_id = document.querySelector('#office-select');
    ref_office_id.addEventListener('change', () => {
        let data = ref_office_id.value;
        @this.set('ref_office_id', data);
    });

    $wire.on('set-office-select', (key) => {
        document.querySelector('#office-select').setValue(key[0]);
    });

    $wire.on('reset-office-select', (key) => {
        document.querySelector('#office-select').reset(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#incoming-request-types-select',
        options: @json($incoming_request_types),
        maxWidth: '100%'
    });

    let ref_incoming_request_types_id = document.querySelector('#incoming-request-types-select');
    ref_incoming_request_types_id.addEventListener('change', () => {
        let data = ref_incoming_request_types_id.value;
        @this.set('ref_incoming_request_types_id', data);
    });

    $wire.on('set-incoming-request-types-select', (key) => {
        document.querySelector('#incoming-request-types-select').setValue(key[0]);
    });

    $wire.on('reset-incoming-request-types-select', (key) => {
        document.querySelector('#incoming-request-types-select').reset(key[0]);
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

    VirtualSelect.init({
        ele: '#type-select',
        options: @json($types),
        search: true,
        maxWidth: '100%'
    });

    let ref_types_id = document.querySelector('#type-select');
    ref_types_id.addEventListener('change', () => {
        let data = ref_types_id.value;
        @this.set('ref_types_id', data);
    });

    $wire.on('set-type-select', (key) => {
        document.querySelector('#type-select').setValue(key[0]);
    });

    $wire.on('reset-type-select', (key) => {
        document.querySelector('#type-select').reset(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#model-select',
        options: @json($models),
        search: true,
        maxWidth: '100%'
    });

    let ref_models_id = document.querySelector('#model-select');
    ref_models_id.addEventListener('change', () => {
        let data = ref_models_id.value;
        @this.set('ref_models_id', data);
    });

    // $wire.on('set-model-select', (key) => {
    //     document.querySelector('#model-select').setValue(key[0]);
    // });

    $wire.on('refresh-model-select-options', (key) => {
        document.querySelector('#model-select').setOptions(key.options);
        document.querySelector('#model-select').setValue(key.selected);
        //// First, set the options for the VirtualSelect element
        // // After options are set, then set the value
        // setTimeout(function() {
        //     // Now set the value for VirtualSelect after the options have been applied
        //     document.querySelector('#model-select').setValue(key.selected);
        // }, 0); // Use 0ms delay to allow the DOM to update
    });

    $wire.on('reset-model-select', (key) => {
        document.querySelector('#model-select').reset(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    let table_job_orders;

    $wire.on('load-table-job-orders', (key) => {
        const data_2 = JSON.parse(key);
        const container_table_job_orders = document.getElementById("table_job_orders");

        // Check if the table exists
        if (table_job_orders) {
            // Update the data dynamically using updateConfig
            table_job_orders.updateConfig({
                data: () => {
                    return new Promise(resolve => {
                        setTimeout(() => {
                            // Check if data_2 is empty and pass empty array
                            const dataToShow = data_2.length > 0 ?
                                data_2.map(item => [
                                    item.id,
                                    item.id,
                                    item.category.name,
                                    item.sub_category.name,
                                    item.status.name,
                                    item.date_and_time_out ?
                                    new Date(item.date_and_time_out).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'short',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        hour12: true
                                    }) :
                                    '-',
                                    item.total_repair_time ? item.total_repair_time : '-'
                                ]) : []; // Pass empty array when no data

                            resolve(dataToShow);
                        }, 1000);
                    });
                }
            });

            // Force render after updating config to make sure the message appears
            table_job_orders.forceRender();
        } else {
            // Clear the container if table doesn't exist
            container_table_job_orders.innerHTML = "";

            // Initialize the Grid.js table
            table_job_orders = new gridjs.Grid({
                columns: [{
                        name: "ID",
                        hidden: true
                    },
                    "Job Order No.",
                    "Category",
                    "Sub-category",
                    {
                        name: "Status",
                        formatter: (cell, row) => {
                            const status = row.cells[4].data;

                            const getBadgeClass = (status) => {
                                if (status === 'Pending') return 'text-bg-danger';
                                if (status === 'Done') return 'text-bg-success';
                                if (status === 'Referred to') return 'text-bg-info';
                            };

                            return gridjs.html(`
                                <span class="badge rounded-pill ${getBadgeClass(status)}">
                                ${row.cells[4].data}
                                </span>
                            `);
                        }
                    },
                    "Date & Time (Out)",
                    "Total Repair Time",
                    {
                        name: "Actions",
                        formatter: (cell, row) => {
                            const id = row.cells[0].data;
                            return gridjs.html(`
                            @can('read incoming')
                            <button class="btn btn-success btn-sm btn-icon-text me-3" title="Edit" style="display: ${row.cells[4].data === 'Pending' ? '' : 'none'}" wire:click="readJobOrder('${id}')">
                                <i class="bx bx-edit bx-sm"></i>
                            </button>
                            <button class="btn btn-info btn-sm btn-icon-text me-3" title="View" style="display: ${row.cells[4].data === 'Pending' ? 'none' : ''}" wire:click="readJobOrdersDetails('${id}')">
                                <i class="bx bx-detail bx-sm"></i>
                            </button>
                            @endcan
                            <button class="btn btn-white btn-sm btn-icon-text me-3" title="Print" wire:click="assignSignatory('${id}')">
                                <i class="bx bx-printer bx-sm"></i>
                            </button>
                            <button class="btn btn-white btn-sm btn-icon-text me-3" title="Print" wire:click="readLogs('${id}')">
                                <i class="bx bx-history bx-sm"></i>
                            </button>
                        `);
                        }
                    }
                ],
                search: true,
                pagination: {
                    limit: 10
                },
                sort: true,
                data: () => {
                    return new Promise(resolve => {
                        setTimeout(() => {
                            // Check if data_2 is empty and pass empty array
                            const dataToShow = data_2.length > 0 ?
                                data_2.map(item => [
                                    item.id,
                                    item.id,
                                    item.category.name,
                                    item.sub_category.name,
                                    item.status.name,
                                    item.date_and_time_out ?
                                    new Date(item.date_and_time_out).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'short',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        hour12: true
                                    }) :
                                    '-',
                                    item.total_repair_time ? item.total_repair_time : '-'
                                ]) : []; // Pass empty array when no data

                            resolve(dataToShow);
                        }, 1000);
                    });
                }
            }).render(container_table_job_orders);
        }
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
        ele: '#sub-category-select',
        options: @json($sub_categories),
        search: true,
        maxWidth: '100%'
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

    VirtualSelect.init({
        ele: '#signatories-select',
        options: @json($signatories),
        search: true,
        maxWidth: '100%',
        hasOptionDescription: true
    });

    let ref_signatories_id = document.querySelector('#signatories-select');
    ref_signatories_id.addEventListener('change', () => {
        let data = ref_signatories_id.value;
        @this.set('ref_signatories_id', data);
    });

    $wire.on('set-signatories-select', (key) => {
        document.querySelector('#signatories-select').setValue(key[0]);
    });

    $wire.on('reset-signatories-select', (key) => {
        document.querySelector('#signatories-select').reset(key[0]);
    });
</script>
@endscript