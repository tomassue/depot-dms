<div>
    <div class="row">
        <div class="card">
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="showAddRolesModal"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            <div class="col-md-12 my-2">
                <div id="table_roles" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="rolesModal" tabindex="-1" aria-labelledby="rolesModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="rolesModalLabel">{{ $editMode ? 'Update' : 'Add' }} Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="createRole">
                        <div class="form-group">
                            <label for="exampleInputRole">Role</label>
                            <input type="text" class="form-control" id="exampleInputRole" placeholder="Role" wire:model="role">
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
    $wire.on('showAddRolesModal', () => {
        $('#rolesModal').modal('show');
    });

    $wire.on('hideAddRolesModal', () => {
        $('#rolesModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    const roles = @json($roles);
    const roles_grid = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Role",
            {
                name: "Actions",
                formatter: (cell, row) => {
                    // Directly access the ID from the first column (index 0)
                    const id = row.cells[0].data; // Since ID is in the first column
                    return gridjs.html(`
                    <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readRow('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
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
                        roles.map(role => [role.id, role.name])
                    ), 3000);
            });
        }
    }).render(document.getElementById("table_roles"));

    $wire.on('refresh-table', (data) => {
        roles_grid.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(role => [role.id, role.name])
                        ), 3000);
                });
            }
        }).forceRender();
    });
</script>
@endscript