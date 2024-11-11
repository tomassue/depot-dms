<div>
    <div class="row">
        <div class="card">
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="showAddUserModal"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            <div class="col-md-12 my-2">
                <div id="table_users" wire:ignore></div>
            </div>
        </div>
    </div>

    <!-- userModal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="userModalLabel">{{ $editMode ? 'Update' : 'Add' }} User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="{{ $editMode ? 'updateUser' : 'createUser' }}" novalidate>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Username</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" placeholder="Username" wire:model="username">
                            @error('username')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputName">Name</label>
                            <input type="text" class="form-control" id="exampleInputName" placeholder="Username" wire:model="name">
                            @error('name')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" wire:model="email">
                            @error('email')
                            <div class="custom-invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="inputRole">Assign a Role</label>
                            <div id="role-select" wire:ignore></div>
                            @error('role')
                            <span class="custom-invalid-feedback">
                                {{ $message }}
                            </span>
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

    <!-- roleModal -->
    <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="roleModalLabel">Assign Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" wire:submit="assignUserRole" novalidate>
                        <div class="form-group">
                            <label for="inputRole">Assign a Role</label>
                            <div id="role-select" wire:ignore></div>
                            @error('email')
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
    $wire.on('showUserModal', () => {
        $('#userModal').modal('show');
    });

    $wire.on('hideUserModal', () => {
        $('#userModal').modal('hide');
    });

    $wire.on('showRoleModal', () => {
        $('#roleModal').modal('show');
    });

    /* -------------------------------------------------------------------------- */

    const users = @json($users);
    const users_grid = new gridjs.Grid({
        columns: [{
                name: "ID",
                hidden: true
            },
            "Name",
            "Email",
            {
                name: "Actions",
                formatter: (cell, row) => {
                    // Directly access the ID from the first column (index 0)
                    const id = row.cells[0].data; // Since ID is in the first column
                    return gridjs.html(`
                    <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readUser('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
                    `);
                }
            }
        ],
        search: true,
        pagination: true,
        sort: true,
        data: () => {
            return new Promise(resolve => { // This is for the loading state
                setTimeout(() =>
                    resolve(
                        users.map(user => [user.id, user.name, user.email])
                    ), 3000);
            });
        }
        // data: users.map(user => [user.id, user.name, user.email]) // This is applicable if you don't want to have loading state
    }).render(document.getElementById("table_users"));

    $wire.on('refresh-table-users', (data) => {
        users_grid.updateConfig({
            data: () => {
                return new Promise(resolve => { // This is for the loading state
                    setTimeout(() =>
                        resolve(
                            data[0].map(user => [user.id, user.name, user.email])
                        ), 3000);
                });
            }
        }).forceRender();
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#role-select',
        options: @json($roles),
        maxWidth: '100%'
    });

    let role = document.querySelector('#role-select');
    role.addEventListener('change', () => {
        let data = role.value;
        @this.set('role', data);
    });
</script>
@endscript