<div>
    <div class="row">
        <div class="card">
            @can('create models')
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showModelModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            @endcan
            <div class="col-md-12 my-2">
                <div id="table_models" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- modelModal -->
    <div class="modal fade" id="modelModal" tabindex="-1" aria-labelledby="modelModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modelModalLabel">{{ $editMode ? 'Update' : 'Add' }} Model (Equipment / Vehicle)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateModel' : 'createModel' }}" novalidate>
                        <div class="form-group">
                            <label for="inputModel">Type (Equipment / Vehicle)</label>
                            <div id="types-select" wire:ignore></div>
                            @error('ref_types_id')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="inputModel">Model (Equipment / Vehicle)</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" id="inputModel" placeholder="Model (Equipment / Vehicle)" wire:model="model">
                            @error('model')
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
    $wire.on('showModelModal', () => {
        $('#modelModal').modal('show');
    });

    $wire.on('hideModelModal', () => {
        $('#modelModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#types-select',
        options: @json($types),
        search: true,
        maxWidth: '100%'
    });

    let ref_types_id = document.querySelector('#types-select');
    ref_types_id.addEventListener('change', () => {
        let data = ref_types_id.value;
        @this.set('ref_types_id', data);
    });

    $wire.on('set-types-select', (key) => {
        document.querySelector('#types-select').setValue(key[0]);
    });

    $wire.on('reset-types-select', () => {
        document.querySelector('#types-select').reset();
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($models); // Ensure that $mechanics includes 'deleted_at' field
    const table_models = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Model",
            "Type",
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
                        @can('update models')
                        <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readModel('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
                        @endcan
                        @can('restore models')
                        <button class="btn ${isInactive ? 'btn-info' : 'btn-danger'} btn-sm btn-icon-text me-3" wire:click="${isInactive ? `restoreModel('${id}')` : `softDeleteModel('${id}')`}">
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
                            item.type.name,
                            item.deleted_at ? 'Inactive' : 'Active' // Use plain text for status here
                        ])
                    ), 3000);
            });
        }
    }).render(document.getElementById("table_models"));

    $wire.on('refresh-table-models', (data) => {
        table_models.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id,
                                item.name,
                                item.type.name,
                                item.deleted_at ? 'Inactive' : 'Active' // Use plain text for status here
                            ])
                        ), 3000);
                });
            }
        }).forceRender();
    });
</script>
@endscript