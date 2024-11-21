<div>
    <div class="row">
        <div class="card">
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

    <!-- incomingModal -->
    <div class="modal fade" id="incomingModal" tabindex="-1" aria-labelledby="incomingModalLabel" aria-hidden="true" wire:ignore.self>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputType">Type (Equipment / Vehicle)</label>
                                    <div id="type-select" wire:ignore></div>
                                    @error('ref_types_id')
                                    <div class="custom-invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="inputNumber">Number (Equipment / Vehicle)</label>
                                    <input type="text" class="form-control @error('number') is-invalid @enderror" id="inputNumber" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\-]/g, '')" wire:model="number">
                                    @error('number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="inputDriverInCharge">Driver in charge</label>
                                    <input type="text" class="form-control @error('driver_in_charge') is-invalid @enderror" id="inputDriverInCharge" wire:model="driver_in_charge">
                                    @error('driver_in_charge')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputModel">Model (Equipment / Vehicle)</label>
                                    <div id="model-select" wire:ignore></div>
                                    <div id="model-select-2" wire:ignore></div>
                                    @error('ref_models_id')
                                    <div class="custom-invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="inputMileage">Mileage / Odometer Reading</label>
                                    <input type="text" class="form-control @error('mileage') is-invalid @enderror" id="inputMileage" wire:model="mileage">
                                    @error('mileage')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                    <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Save' }}</button>
                    </form>
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
                        <button class="btn btn-info btn-sm btn-icon-text me-3" title="View" wire:click="readOffice('${id}')"><i class="bx bx-detail bx-sm"></i></button>
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
                            item.number,
                            item.mileage,
                            item.driver_in_charge,
                            item.contact_number
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
                                item.number,
                                item.mileage,
                                item.driver_in_charge,
                                item.contact_number
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
        date_and_time.setDate(key);
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
        // First, set the options for the VirtualSelect element
        document.querySelector('#model-select').setOptions(key.options);
        document.querySelector('#model-select').setValue(key.selected);

        // // After options are set, then set the value
        // setTimeout(function() {
        //     // Now set the value for VirtualSelect after the options have been applied
        //     document.querySelector('#model-select').setValue(key.selected);
        // }, 0); // Use 0ms delay to allow the DOM to update
    });

    $wire.on('reset-model-select', (key) => {
        document.querySelector('#model-select').reset(key[0]);
    });
</script>
@endscript