<div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                @can('can create location')
                <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                    <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showLocationModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
                </div>
                @endcan
                <div class="col-md-12 my-2">
                    <div id="table_locations" wire:ignore></div>
                </div>
            </div>
        </div>
    </div>

    <!-- locationModal -->
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="locationModalLabel">{{ $editMode ? 'Update' : 'Add' }} Location</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateLocation' : 'createLocation' }}" novalidate>
                        <div class="form-group">
                            <label for="inputLocation">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="inputLocation" placeholder="Location" wire:model="location">
                            @error('location')
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
    $wire.on('showLocationModal', () => {
        $('#locationModal').modal('show');
    });

    $wire.on('hideLocationModal', () => {
        $('#locationModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($locations);
    const table_locations = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Locations",
            {
                name: "Status",
                formatter: (cell, row) => {
                    return gridjs.html(`
                    <span class="${row.cells[2].data === 'Inactive' ? 'text-danger' : 'text-success'}">
                    ${row.cells[2].data}
                    </span>
                `);
                }
            },
            {
                name: "Actions",
                formatter: (cell, row) => {
                    const id = row.cells[0].data;
                    const isInactive = row.cells[2].data === 'Inactive';
                    return gridjs.html(`
                        @can('can update category')
                        <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readLocation('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
                        @endcan
                        @can('delete category')
                        <button class="btn ${isInactive ? 'btn-info' : 'btn-danger'} btn-sm btn-icon-text me-3" wire:click="${isInactive ? `restoreLocation('${id}')` : `softDeleteLocation('${id}')`}">
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
                            item.deleted_at ? 'Inactive' : 'Active' // Use plain text for status here
                        ])
                    ), 3000);
            });
        }
    }).render(document.getElementById("table_locations"));

    $wire.on('refresh-table-locations', (data) => {
        table_locations.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id,
                                item.name,
                                item.deleted_at ? 'Inactive' : 'Active' // Use plain text for status here
                            ])
                        ), 3000);
                });
            }
        }).forceRender();
    });
</script>
@endscript