<div>
    <div class="card">
        <div class="card-body">
            @can('create types')
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showTypeModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            @endcan
            <div class="col-md-12 my-2">
                <div id="table_types" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- typeModal -->
    <div class="modal fade" id="typeModal" tabindex="-1" aria-labelledby="typeModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="typeModalLabel">{{ $editMode ? 'Update' : 'Add' }} Type (Equipment / Vehicle)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateType' : 'createType' }}" novalidate>
                        <div class="form-group">
                            <label for="inputType">Type (Equipment / Vehicle)</label>
                            <input type="text" class="form-control @error('type') is-invalid @enderror" id="inputType" placeholder="Type (Equipment / Vehicle)" wire:model="type">
                            @error('type')
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
    $wire.on('showTypeModal', () => {
        $('#typeModal').modal('show');
    });

    $wire.on('hideTypeModal', () => {
        $('#typeModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($types); // Ensure that $mechanics includes 'deleted_at' field
    const table_types = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Types",
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
                        @can('update types')
                        <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readType('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
                        @endcan
                        @can('delete types')
                        <button class="btn ${isInactive ? 'btn-info' : 'btn-danger'} btn-sm btn-icon-text me-3" wire:click="${isInactive ? `restoreType('${id}')` : `softDeleteType('${id}')`}">
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
    }).render(document.getElementById("table_types"));

    $wire.on('refresh-table-types', (data) => {
        table_types.updateConfig({
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