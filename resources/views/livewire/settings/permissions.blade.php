<div>
    <div class="card">
        <div class="card-body">
            @can('can create permissions')
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="showAddPermissionsModal"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            @endcan
            <div class="col-md-12 my-2">
                <div id="table_permissions" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- permissionsModal -->
    <div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="permissionsModalLabel">{{ $editMode ? 'Update' : 'Add' }} Permission</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updatePermission' : 'createPermission' }}" novalidate>
                        <div class="form-group">
                            <label for="exampleInputPermission">Permission</label>
                            <input type="text" class="form-control @error('permission') is-invalid @enderror" id="exampleInputPermission" placeholder="Permission" wire:model="permission">
                            @error('permission')
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
    $wire.on('showAddPermissionsModal', () => {
        $('#permissionsModal').modal('show');
    });

    $wire.on('hideAddPermissionsModal', () => {
        $('#permissionsModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    const permissions = @json($permissions);
    const permissions_grid = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Permission"
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
                        permissions.map(permission => [permission.id, permission.name])
                    ), 3000);
            });
        }
    }).render(document.getElementById("table_permissions"));

    $wire.on('refresh-table-permissions', (data) => {
        permissions_grid.updateConfig({
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() =>
                        resolve(
                            data[0].map(permission => [permission.id, permission.name])
                        ), 3000);
                });
            }
        }).forceRender();
    });
</script>
@endscript