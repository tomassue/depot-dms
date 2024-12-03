<div>
    @include('livewire.template.loading-spinner')

    <div class="card">
        <div class="card-body">
            @can('create status')
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showStatusModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            @endcan
            <div class="col-md-12 my-2">
                <div id="table_statuses" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- statusModal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="statusModalLabel">{{ $editMode ? 'Update' : 'Add' }} Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateStatus' : 'createStatus' }}" novalidate>
                        <div class="form-group">
                            <label for="inputStatus">Status</label>
                            <input type="text" class="form-control @error('status') is-invalid @enderror" id="inputStatus" placeholder="Status" wire:model="status">
                            @error('status')
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
    $wire.on('showStatusModal', () => {
        $('#statusModal').modal('show');
    });

    $wire.on('hideStatusModal', () => {
        $('#statusModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($statuses); // Ensure that $mechanics includes 'deleted_at' field
    const table_statuses = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Statuses",
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
                        @can('update status')
                        <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readStatus('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
                        @endcan
                        @can('delete status')
                        <button class="btn ${isInactive ? 'btn-info' : 'btn-danger'} btn-sm btn-icon-text me-3" wire:click="${isInactive ? `restoreStatus('${id}')` : `softDeleteStatus('${id}')`}">
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
    }).render(document.getElementById("table_statuses"));

    $wire.on('refresh-table-statuses', (data) => {
        table_statuses.updateConfig({
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