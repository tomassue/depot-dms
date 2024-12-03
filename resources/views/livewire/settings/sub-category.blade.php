<div>
    @include('livewire.template.loading-spinner')

    <div class="card">
        <div class="card-body">
            @can('create sub-category')
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="$dispatch('showSubCategoryModal')"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            @endcan
            <div class="col-md-12 my-2">
                <div id="table_sub_category" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- subCategoryModal -->
    <div class="modal fade" id="subCategoryModal" tabindex="-1" aria-labelledby="subCategoryModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="subCategoryModalLabel">{{ $editMode ? 'Update' : 'Add' }} Sub-category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateSubCategory' : 'createSubCategory' }}" novalidate>
                        <div class="form-group">
                            <label for="inputSubCategory">Category</label>
                            <div id="category-select" wire:ignore></div>
                            @error('id_ref_category')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            <label for="inputSubCategory" class="mt-3">Sub-category</label>
                            <input type="text" class="form-control @error('sub_category') is-invalid @enderror" id="inputSubCategory" placeholder="Sub-category" wire:model="sub_category">
                            @error('sub_category')
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
    $wire.on('showSubCategoryModal', () => {
        $('#subCategoryModal').modal('show');
    });

    $wire.on('hideSubCategoryModal', () => {
        $('#subCategoryModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    const data = @json($sub_categories);
    const table_sub_category = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Sub-category",
            "Category",
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
                        @can('update sub-category')
                        <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readSubCategory('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
                        @endcan
                        @can('delete sub-category')
                        <button class="btn ${isInactive ? 'btn-info' : 'btn-danger'} btn-sm btn-icon-text me-3" wire:click="${isInactive ? `restoreSubCategory('${id}')` : `softDeleteSubCategory('${id}')`}">
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
                            item.category ? item.category.name : 'N/A', // Category name or fallback
                            item.deleted_at ? 'Inactive' : 'Active' // Use plain text for status here
                        ])
                    ), 3000);
            });
        }
    }).render(document.getElementById("table_sub_category"));

    $wire.on('refresh-table-sub-categories', (data) => {
        table_sub_category.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(item => [
                                item.id,
                                item.name,
                                item.category ? item.category.name : 'N/A', // Category name or fallback
                                item.deleted_at ? 'Inactive' : 'Active' // Use plain text for status here
                            ])
                        ), 3000);
                });
            }
        }).forceRender();
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#category-select',
        options: @json($id_ref_categories),
        search: true,
        maxWidth: '100%'
    });

    let id_ref_category = document.querySelector('#category-select');
    id_ref_category.addEventListener('change', () => {
        let data = id_ref_category.value;
        @this.set('id_ref_category', data);
    });

    $wire.on('select-id-ref-category', (key) => {
        document.querySelector('#category-select').setValue(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    $wire.on('refresh-plugin', () => {
        document.querySelector('#category-select').reset();
    });
</script>
@endscript