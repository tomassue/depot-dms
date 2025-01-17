<div>
    @include('livewire.template.loading-spinner')

    <div class="card">
        <div class="card-body">
            <div class="col-md-2 my-2 d-flex flex-column align-items-start" title="Filter">
                <div class="form-group w-100">
                    <div wire:ignore>
                        <input class="form-control filter_date_range" placeholder="Select date range">
                    </div>
                </div>
            </div>
            <div class="col-md-12 my-2 d-inline-flex align-items-center mb-5">
                <button class=" btn btn-info btn-md btn-icon-text me-4" title="Print" wire:click="generateMechanicsPDF">
                    <i class="bx bx-printer bx-sm btn-icon-append"></i>
                </button>
                <button class="btn btn-secondary btn-md btn-icon-text" title="Clear" wire:click="clear">
                    <i class="bx bxs-eraser bx-sm"></i>
                </button>
            </div>
            @can('can create mechanics')
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="showAddMechanicsModal"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            @endcan
            <div class="col-md-12 my-2">
                <div id="table_mechanics" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- mechanicsModal -->
    <div class="modal fade" id="mechanicsModal" tabindex="-1" aria-labelledby="mechanicsModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="mechanicsModalLabel">{{ $editMode ? 'Update' : 'Add' }} Mechanic</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateMechanic' : 'createMechanic' }}" novalidate>
                        <div class="form-group">
                            <label for="inputSection">Section</label>
                            <select class="form-select  @error('ref_sections_mechanic_id') is-invalid @enderror" aria-label="Default select example" wire:model.live="ref_sections_mechanic_id">
                                <option selected>Select a section</option>
                                @forelse($sections as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @empty
                                <option value="">Please add sections</option>
                                @endforelse
                            </select>
                            @error('ref_sections_mechanic_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputSubSection" style="display: {{ $sub_sections->count() > 0 ? '' : 'none' }}">Sub-section</label>
                            <select class="form-select  @error('ref_sub_sections_mechanic_id') is-invalid @enderror" style="display: {{ $sub_sections->count() > 0 ? '' : 'none' }}" aria-label="Default select example" wire:model.live="ref_sub_sections_mechanic_id">
                                <option selected>Select a sub-section</option>
                                @forelse($sub_sections as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @empty
                                <option value="">Please add sections</option>
                                @endforelse
                            </select>
                            @error('ref_sub_sections_mechanic_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputMechanic">Mechanic</label>
                            <input type="text" class="form-control @error('mechanic') is-invalid @enderror" id="inputMechanic" placeholder="Mechanic" wire:model="mechanic">
                            @error('mechanic')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
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
    $wire.on('print-pdf', (url) => {
        window.open(event.detail.url, '_blank');
    });

    /* -------------------------------------------------------------------------- */

    $wire.on('showAddMechanicsModal', () => {
        $('#mechanicsModal').modal('show');
    });

    $wire.on('hideAddMechanicsModal', () => {
        $('#mechanicsModal').modal('hide');
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

    const data = @json($mechanics); // Ensure that $mechanics includes 'deleted_at' field
    const table_mechanics = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Name of Mechanics",
            "Section",
            "Sub-section",
            {
                name: "Status",
                formatter: (cell, row) => {
                    return gridjs.html(`
                    <span class="${row.cells[4].data === 'Inactive' ? 'text-danger' : 'text-success'}">
                    ${row.cells[4].data}
                    </span>
                `);
                }
            },
            {
                name: "Actions",
                formatter: (cell, row) => {
                    const id = row.cells[0].data;
                    const isInactive = row.cells[4].data === 'Inactive';
                    return gridjs.html(`
                        @can('can update mechanics')
                        <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readMechanic('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
                        @endcan
                        @can('can delete mechanics')
                        <button class="btn ${isInactive ? 'btn-info' : 'btn-danger'} btn-sm btn-icon-text me-3" wire:click="${isInactive ? `restoreMechanic('${id}')` : `softDeleteMechanic('${id}')`}">
                            ${isInactive ? 'Activate' : 'Deactivate'} 
                            <i class='bx ${isInactive ? 'bx-check-circle' : 'bx-trash'} bx-xs'></i>
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
                setTimeout(() =>
                    resolve(
                        data.map(item => [
                            item.id,
                            item.name,
                            item.ref_sections_mechanic_id,
                            item.ref_sub_sections_mechanic_id,
                            item.deleted_at ? 'Inactive' : 'Active' // Use plain text for status here
                        ])
                    ), 3000);
            });
        }
    }).render(document.getElementById("table_mechanics"));

    $wire.on('refresh-table-mechanics', (data) => {
        table_mechanics.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id,
                                item.name,
                                item.ref_sections_mechanic_id,
                                item.ref_sub_sections_mechanic_id,
                                item.deleted_at ? 'Inactive' : 'Active' // Use plain text for status here
                            ])
                        ), 3000);
                });
            }
        }).forceRender();
    });
</script>
@endscript