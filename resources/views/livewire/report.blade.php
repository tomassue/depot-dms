<div>
    @include('livewire.template.loading-spinner')

    <div class="card mb-2">
        <div class="card-body">
            <div class="col-md-2 my-2 d-flex flex-column align-items-start">
                <div class="form-group w-100">
                    <div wire:ignore>
                        <input class="form-control filter_date_range" placeholder="Date">
                    </div>
                </div>

                <div class="form-group w-100">
                    <div id="filter-status-select" wire:ignore></div>
                </div>
            </div>

            <div class="col-md-12 my-2 d-inline-flex align-items-center mb-5" title="Filter">
                <!-- <button class="btn btn-primary btn-md btn-icon-text me-2" wire:click="filter">
                    <i class="bx bx-filter-alt bx-sm"></i>
                </button> -->

                @can('print reports')
                <button class="btn btn-info btn-md btn-icon-text me-4" title="Print" wire:click="print">
                    <i class="bx bx-printer bx-sm"></i>
                </button>
                @endcan

                <button class="btn btn-secondary btn-md btn-icon-text" title="Clear" wire:click="clear">
                    <i class="bx bxs-eraser bx-sm"></i>
                </button>
            </div>

            <div class="col-md-12 my-2">
                <div id="table_requests" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- jobOrderDetails -->
    <div class="modal fade" id="jobOrderDetails" tabindex="-1" aria-labelledby="jobOrderDetailsModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="jobOrderDetailsModalLabel">Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="inputType">Type</label>
                            <input type="text" class="form-control disabled_input" id="inputType" wire:model="ref_types_id">
                        </div>
                        <div class="col-md-6">
                            <label for="inputModel">Model</label>
                            <input type="text" class="form-control disabled_input" id="inputModel" wire:model="ref_models_id">
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
                            <label for="inputDriverInCharge">Driver in charge</label>
                            <input type="text" class="form-control disabled_input" id="inputDriverInCharge" wire:model="driver_in_charge">
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
                            <label for="inputDateTime">Date & Time</label>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- printPDF -->
    <div class="modal fade" id="printPDF" tabindex="-1" aria-labelledby="printPDFModalLabel" aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="printPDFModalLabel">PDF</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <embed src="{{ $pdfData }}" type="application/pdf" width="100%" height="700px">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('showJobOrderDetailsModal', () => {
        $('#jobOrderDetails').modal('show');
    });

    $wire.on('hideJobOrderDetailsModal', () => {
        $('#jobOrderDetails').modal('hide');
    });

    $wire.on('showPdfModal', () => {
        $('#printPDF').modal('show');
    });

    $wire.on('hidePdfModal', () => {
        $('#printPDF').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    $(".filter_date_range").flatpickr({
        mode: "range",
        altInput: true,
        altFormat: 'M j, Y',
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr) {
            @this.set('filter_date_range', dateStr);
        }
    });

    $wire.on('set-date-and-time', (key) => {
        $(".filter_date_range")[0]._flatpickr.setDate(key[0]);
        @this.set('filter_date_range', key[0]);
    });

    $wire.on('reset-date-and-time', () => {
        $(".filter_date_range")[0]._flatpickr.clear(); // Clear the Flatpickr input without using a variable
        @this.set('filter_date_range', null);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#filter-status-select',
        options: @json($filter_status),
        maxWidth: '100%'
    });

    let filter_status_range = document.querySelector('#filter-status-select');
    filter_status_range.addEventListener('change', () => {
        let data = filter_status_range.value;
        @this.set('filter_status_range', data);
    });

    $wire.on('set-filter-status-select-select', (key) => {
        document.querySelector('#filter-status-select').setValue(key[0]);
    });

    $wire.on('reset-filter-status-select-select', (key) => {
        document.querySelector('#filter-status-select').reset(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($table_requests); // Ensure that $mechanics includes 'deleted_at' field
    const table_requests = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            'Date',
            'Office/Department',
            'Category',
            'Sub-category',
            'Vehicle Type',
            'Diagnosis',
            {
                name: "Actions",
                formatter: (cell, row) => {
                    const id = row.cells[0].data;
                    return gridjs.html(`
                        @can('read incoming')
                        <button class="btn btn-info btn-sm btn-icon-text me-3" title="View" wire:click="readJobOrder('${id}')"><i class="bx bx-detail bx-sm"></i></button>
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
                            new Date(item.created_at).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            }),
                            item.office,
                            item.category,
                            item.sub_category,
                            item.type,
                            item.issue_or_concern
                        ])
                    ), 1000);
            });
        }
    }).render(document.getElementById("table_requests"));

    $wire.on('refresh-table-incoming-requests', (data) => {
        table_requests.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id,
                                new Date(item.created_at).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric'
                                }),
                                item.office,
                                item.category,
                                item.sub_category,
                                item.type,
                                item.issue_or_concern
                            ])
                        ), 1000);
                });
            }
        }).forceRender();
    });
</script>
@endscript