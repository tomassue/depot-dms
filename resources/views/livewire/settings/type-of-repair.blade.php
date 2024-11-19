<div>
    <div class="row">
        <div class="card">
            @can('create type of repair')
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showTypeOfRepairModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            @endcan
            <div class="col-md-12 my-2">
                <div id="table_type_of_repairs" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- typeOfRepairModal -->
    <div class="modal fade" id="typeOfRepairModal" tabindex="-1" aria-labelledby="typeOfRepairModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="typeOfRepairModalLabel">{{ $editMode ? 'Update' : 'Add' }} Type of Repair</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateTypeOfRepair' : 'createTypeOfRepair' }}" novalidate>
                        <div class="form-group">
                            <label for="inputTypeOfRepair">Type of Repair</label>
                            <input type="text" class="form-control @error('type_of_repair') is-invalid @enderror" id="inputTypeOfRepair" placeholder="Type of Repair" wire:model="type_of_repair">
                            @error('type_of_repair')
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
    $wire.on('showTypeOfRepairModal', () => {
        $('#typeOfRepairModal').modal('show');
    });

    $wire.on('hideTypeOfRepairModal', () => {
        $('#typeOfRepairModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($type_of_repairs); // Ensure that $mechanics includes 'deleted_at' field
    const table_type_of_repairs = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Type of Repairs",
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
                        @can('update type of repair')
                        <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readTypeOfRepair('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
                        @endcan
                        @can('delete type of repair')
                        <button class="btn ${isInactive ? 'btn-info' : 'btn-danger'} btn-sm btn-icon-text me-3" wire:click="${isInactive ? `restoreTypeOfRepair('${id}')` : `softDeleteTypeOfRepair('${id}')`}">
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
    }).render(document.getElementById("table_type_of_repairs"));

    $wire.on('refresh-table-type-of-repairs', (data) => {
        table_type_of_repairs.updateConfig({
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