<div>
    <div class="row" style="display: {{ $page == 1 ? '' : 'none' }}">
        <div class="card">
            <div class="card-body">
                @can('create incoming')
                <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                    <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showIncomingModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
                </div>
                @endcan
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

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputDateAndTime">Date & Time</label>
                                    <div wire:ignore>
                                        <input class="form-control date_and_time">
                                    </div>
                                    @error('date_and_time')
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
                                    <div class="col-md-6">
                                        <label for="inputMileage">Mileage / Odometer Reading</label>
                                        <input type="text" class="form-control @error('mileage') is-invalid @enderror" id="inputMileage" wire:model="mileage">
                                        @error('mileage')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Row for Driver and Contact -->
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="inputDriverInCharge">Driver in charge</label>
                                        <input type="text" class="form-control @error('driver_in_charge') is-invalid @enderror" id="inputDriverInCharge" wire:model="driver_in_charge">
                                        @error('driver_in_charge')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputContactNumber">Contact Number</label>
                                        <input type="text" class="form-control @error('contact_number') is-invalid @enderror" data-ddg-inputtype="identities.contactNumber" id="inputContactNumber" maxlength="11" oninput="this.value = '09' + this.value.slice(2).replace(/\D/g, '');" placeholder="09XXXXXXXXX" wire:model="contact_number">
                                        @error('contact_number')
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

    <div class="row" style="display: {{ $page == 2 ? '' : 'none' }}">
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
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Reference No.</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="reference_no" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Type</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="ref_types_id" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="number" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
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
                    <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showJobOrderModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
                </div>
                @endcan

                <div class="col-md-12 my-2">
                    <div id="table_job_orders" wire:ignore></div>
                </div>
            </div>
        </div>
    </div>

    <!-- jobOrderModal -->
    <div class="modal fade" id="jobOrderModal" tabindex="-1" aria-labelledby="jobOrderModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="jobOrderModalLabel">{{ $editMode ? 'Update' : 'Add' }} Request Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear2"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateJobOrder' : 'createJobOrder' }}">
                        <p class="card-description">
                            Equipment Details
                        </p>
                        <hr>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="inputType">Type</label>
                                <input type="text" class="form-control disabled_input" id="inputType" wire:model="ref_types_id">
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
                            <div class="col-md-6">
                                <label for="inputMileage">Mileage / Odometer Reading</label>
                                <input type="text" class="form-control disabled_input" id="inputMileage" wire:model="mileage">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="inputDriverInCharge">Driver in charge</label>
                                <input type="text" class="form-control disabled_input" id="inputDriverInCharge" wire:model="driver_in_charge">
                            </div>
                            <div class="col-md-6">
                                <label for="inputContactNumber">Contact Number</label>
                                <input type="text" class="form-control disabled_input" id="inputContactNumber" wire:model="contact_number">
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
                            <div class="col-md-6" style="display: {{ $editMode ? '' : 'none'}}">
                                <label for="selectModel">Status</label>
                                <div id="status-select" wire:ignore></div>
                                @error('ref_status_id')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="selectCategory">Category</label>
                                <div id="category-select" wire:ignore></div>
                                @error('ref_category_id')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="selectTypeOfRepair">Type of Repair</label>
                                <div id="type-of-repair-select" wire:ignore></div>
                                @error('ref_type_of_repair_id')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="selectSubCategory">Sub-category</label>
                                <div id="sub-category-select" wire:ignore></div>
                                @error('ref_sub_category_id')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="selectMechanics">Mechanics Assigned</label>
                                <div id="mechanics-select" wire:ignore></div>
                                @error('ref_mechanics')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="selectLocation">Location</label>
                                <div id="location-select" wire:ignore></div>
                                @error('ref_location_id')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="inputIssueOrConcern">Issues or Concerns</label>
                                <div wire:ignore>
                                    <div id="issue-or-concern-summernote"></div>
                                </div>
                                @error('issue_or_concern')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear2">Close</button>
                    <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Save' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                                <label for="inputDateTime">Date & Time</label>
                                <div wire:ignore>
                                    <input class="form-control jo_date_and_time">
                                </div>
                                @error('jo_date_and_time')
                                <div class="custom-invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="inputTotalRepairTime">Total repair time</label>
                                <input type="text" class="form-control @error('total_repair_time') is-invalid @enderror" id="inputTotalRepairTime" wire:model="total_repair_time">
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
                    <p class="card-description">
                        Equipment Details
                    </p>
                    <hr>
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
                        <div class="col-md-6">
                            <label for="inputMileage">Mileage / Odometer Reading</label>
                            <input type="text" class="form-control disabled_input" id="inputMileage" wire:model="mileage">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputDriverInCharge">Driver in charge</label>
                            <input type="text" class="form-control disabled_input" id="inputDriverInCharge" wire:model="driver_in_charge">
                        </div>
                        <div class="col-md-6">
                            <label for="inputContactNumber">Contact Number</label>
                            <input type="text" class="form-control disabled_input" id="inputContactNumber" wire:model="contact_number">
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
                            <input type="text" class="form-control disabled_input" id="inputIssueOrConcern" wire:model="issue_or_concern">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputDateTime">Date & Time</label>
                            <input type="text" class="form-control disabled_input" id="inputDateTime" wire:model="jo_date_and_time">
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
                            <input type="text" class="form-control disabled_input" id="inputJobOrder" wire:model="remarks">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear2">Close</button>
                    <button type="button" class="btn btn-info" data-bs-dismiss="modal" wire:click="printJobOrder('{{$job_order_no}}')">Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jobOrderDetailsPDF -->
    <div class="modal fade" id="jobOrderDetailsPDF" tabindex="-1" aria-labelledby="jobOrderDetailsPDFModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="jobOrderDetailsPDFModalLabel">Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear2"></button>
                </div>
                <div class="modal-body">
                    <embed src="{{ $job_order_details_pdf }}" type="application/pdf" width="100%" height="700px">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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

    $wire.on('showJobOrderDetailsPDF', () => {
        $('#jobOrderDetailsPDF').modal('show');
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
            "Date",
            "Office/Department",
            "Type",
            "Model",
            "Status",
            "Assigned to",
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
                            new Date(item.date_and_time).toLocaleString('en-US', { // Format date
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            }),
                            item.office.name,
                            item.type.name,
                            item.model.name,
                            '-',
                            item.driver_in_charge
                        ])
                    ), 3000);
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
                                new Date(item.date_and_time).toLocaleString('en-US', { // Format date
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                }),
                                item.office.name,
                                item.type.name,
                                item.model.name,
                                '-',
                                item.driver_in_charge
                            ])
                        ), 3000);
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

    var date_and_time = $(".date_and_time").flatpickr({
        enableTime: true,
        altInput: true, // altInput hides your original input and creates a new one. Upon date selection, the original input will contain a Y-m-d... string, while the altInput will display the date in a more legible, customizable format.
        altFormat: 'F j, Y h:i K',
        dateFormat: "Y-m-d H:i", // display in 12-hour format
        onChange: function(selectedDates, dateStr) {
            @this.set('date_and_time', dateStr);
        }
    });

    $wire.on('set-date-and-time', (key) => {
        date_and_time.setDate(key[0]);
        @this.set('date_and_time', key[0]);
    });

    $wire.on('reset-date-and-time', () => {
        date_and_time.clear();
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
                                    item.date_and_time ?
                                    new Date(item.date_and_time).toLocaleString('en-US', {
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
                        }, 3000);
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
                            return gridjs.html(`
                                <span class="badge rounded-pill ${row.cells[4].data === 'Pending' ? 'text-bg-secondary' : 'text-bg-success'}">
                                ${row.cells[4].data}
                                </span>
                            `);
                        }
                    },
                    "Date & Time",
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
                                    item.date_and_time ?
                                    new Date(item.date_and_time).toLocaleString('en-US', {
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
                        }, 3000);
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

    var jo_date_and_time = $(".jo_date_and_time").flatpickr({
        enableTime: true,
        altInput: true, // altInput hides your original input and creates a new one. Upon date selection, the original input will contain a Y-m-d... string, while the altInput will display the date in a more legible, customizable format.
        altFormat: 'F j, Y h:i K',
        dateFormat: "Y-m-d H:i", // display in 12-hour format
        onChange: function(selectedDates, dateStr) {
            @this.set('jo_date_and_time', dateStr);
        }
    });

    $wire.on('set-date-and-time', (key) => {
        jo_date_and_time.setDate(key[0]);
        @this.set('jo_date_and_time', key[0]);
    });

    $wire.on('reset-date-and-time', () => {
        jo_date_and_time.clear();
    });
</script>
@endscript