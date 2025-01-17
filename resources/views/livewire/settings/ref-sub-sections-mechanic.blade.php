<div>
    <div class="card mt-3">
        <div class="card-body">
            <h4 class="card-title">Sub-sections</h4>

            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showSubSectionsMechanicsModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>

            <div class="col-md-12 my-2">
                <div id="table_sub_sections_mechanic" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- subSectionsMechanicsModal -->
    <div class="modal fade" id="subSectionsMechanicsModal" tabindex="-1" aria-labelledby="subSectionsMechanicsModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="subSectionsMechanicsModalLabel">{{ $editMode ? 'Update' : 'Add' }} sub-section</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateSubSection' : 'createSubSection' }}" novalidate>
                        <div class="form-group">
                            <label for="inputSection">Section</label>
                            <select class="form-select  @error('ref_sections_mechanic_id') is-invalid @enderror" aria-label="Default select example" wire:model="ref_sections_mechanic_id">
                                <option selected>Select a section</option>
                                @forelse($ref_sections_mechanic as $item)
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
                            <label for="inputSubSection">Sub-section</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputSubSection" wire:model="name">
                            @error('name')
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
    $wire.on('showSubSectionsMechanicsModal', () => {
        $('#subSectionsMechanicsModal').modal('show');
    });

    $wire.on('hideSubSectionsMechanicsModal', () => {
        $('#subSectionsMechanicsModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($sub_sections); // Ensure that $mechanics includes 'deleted_at' field
    const table_sub_sections_mechanic = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Sections",
            "Sub-sections",
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
                        <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readSubSection('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
                        
                        <button class="btn ${isInactive ? 'btn-info' : 'btn-danger'} btn-sm btn-icon-text me-3" wire:click="${isInactive ? `restoreSubSection('${id}')` : `deleteSubSection('${id}')`}">
                            ${isInactive ? 'Activate' : 'Deactivate'} 
                            <i class='bx ${isInactive ? 'bx-check-circle' : 'bx-trash'} bx-xs'></i>
                        </button>
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
                            item.section.name,
                            item.name,
                            item.deleted_at ? 'Inactive' : 'Active' // Use plain text for status here
                        ])
                    ), 3000);
            });
        }
    }).render(document.getElementById("table_sub_sections_mechanic"));

    $wire.on('refresh-table-sub-sections-mechanic', (data) => {
        table_sub_sections_mechanic.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id,
                                item.section.name,
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