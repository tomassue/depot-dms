<div>
    @include('livewire.template.loading-spinner')

    <div class="card">
        <div class="card-body">
            @can('create signatory')
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showSignatoryModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            @endcan
            <div class="col-md-12 my-2">
                <div id="table_signatories" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- signatoryModal -->
    <div class="modal fade" id="signatoryModal" tabindex="-1" aria-labelledby="signatoryModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="signatoryModalLabel">{{ $editMode ? 'Update' : 'Add' }} Signatory</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateSignatory' : 'createSignatory' }}" novalidate>
                        <div class="form-group">
                            <label for="inputName">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputName" placeholder="Full name" wire:model="name">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputDesignation">Designation</label>
                            <input type="text" class="form-control @error('designation') is-invalid @enderror" id="inputDesignation" placeholder="Designation" wire:model="designation">
                            @error('designation')
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
    $wire.on('showSignatoryModal', () => {
        $('#signatoryModal').modal('show');
    });

    $wire.on('hideSignatoryModal', () => {
        $('#signatoryModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($signatories); // Ensure that $signatories includes 'deleted_at' field
    const table_signatories = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Full name",
            "Designation",
            {
                name: "Status",
                formatter: (cell, row) => {
                    return gridjs.html(`
                    <span class="${row.cells[3].data === 'Inactive' ? 'text-danger' : 'text-success'}">
                    ${row.cells[3].data}
                    </span>
                `);
                }
            },
            {
                name: "Actions",
                formatter: (cell, row) => {
                    const id = row.cells[0].data;
                    const isInactive = row.cells[3].data === 'Inactive';
                    return gridjs.html(`
                        @can('update signatory')
                        <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readSignatory('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
                        @endcan
                        @can('delete signatory')
                        <button class="btn ${isInactive ? 'btn-info' : 'btn-danger'} btn-sm btn-icon-text me-3" wire:click="${isInactive ? `restoreSignatory('${id}')` : `softDeleteSignatory('${id}')`}">
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
                            item.designation,
                            item.deleted_at ? 'Inactive' : 'Active' // Use plain text for status here
                        ])
                    ), 3000);
            });
        }
    }).render(document.getElementById("table_signatories"));

    $wire.on('refresh-table-signatories', (data) => {
        table_signatories.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id,
                                item.name,
                                item.designation,
                                item.deleted_at ? 'Inactive' : 'Active' // Use plain text for status here
                            ])
                        ), 3000);
                });
            }
        }).forceRender();
    });
</script>
@endscript