<div>
    @include('livewire.template.loading-spinner')

    <div class="card">
        <div class="card-body">
            @can('can create user management')
            <div class="col-md-12 my-2 d-inline-flex align-content-center justify-content-end">
                <button class="btn btn-primary btn-md btn-icon-text" wire:click="showAddUserModal"> Add <i class="typcn typcn-plus-outline btn-icon-append"></i></button>
            </div>
            @endcan

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
                            <input type="text" class="form-control" id="exampleInputName" placeholder="Name" wire:model="name">
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
            'Username',
            "Email",
            "Role",
            {
                name: "Status",
                formatter: (cell, row) => {
                    const status = row.cells[5].data === 'yes' ? 'Active' : 'Inactive';
                    const statusClass = row.cells[5].data === 'yes' ? 'text-success' : 'text-danger';
                    return gridjs.html(`
                    <span class="${statusClass}">
                        ${status}
                    </span>
                `);
                }
            },
            {
                name: "Actions",
                formatter: (cell, row) => {
                    // Directly access the ID from the first column (index 0)
                    const id = row.cells[0].data; // Since ID is in the first column
                    const isInactive = row.cells[5].data;

                    return gridjs.html(`
                    @can('can update user management')
                    <div class="btn-group" role="group" aria-label="Basic example">
                    <button class="btn btn-success btn-sm btn-icon-text" title="Edit" wire:click="readUser('${id}')">
                    <i class='bx  bx-pencil'  ></i> 
                    </button>

                    <button class="btn btn-warning btn-sm btn-icon-text" wire:click="resetPassword('${id}')">
                    <i class='bx  bx-key'  ></i> 
                    </button>
                    
                    <button class="btn ${isInactive === 'no' ? 'btn-info' : 'btn-danger'} btn-sm btn-icon-text" wire:click="${isInactive === 'no' ? `activateUser('${id}')` : `deactivateUser('${id}')`}">
                        ${isInactive === 'no' ? '<i class="bx  bx-user-plus"  ></i> ' : '<i class="bx  bx-user-minus"  ></i> '}
                    </button>
                    </div>
                    @endcan
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
                        users.map(user => [
                            user.id,
                            user.name,
                            user.username,
                            user.email,
                            user.roles.map(role => role.name).join(', ') || 'No role assigned', // Retrieve roles here.
                            user.is_active
                        ])
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
                            data[0].map(user => [
                                user.id,
                                user.name,
                                user.username,
                                user.email,
                                user.roles.map(role => role.name).join(', ') || 'No role assigned', // Retrieve roles here.
                                user.is_active
                            ])
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

    $wire.on('select-role', (key) => {
        document.querySelector('#role-select').setValue(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    $wire.on('refresh-plugins', () => {
        document.querySelector('#role-select').reset();
    });
</script>
@endscript