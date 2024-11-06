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

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="userModalLabel">{{ $editMode ? 'Update' : 'Add' }} User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample">
                        <div class="form-group">
                            <label for="exampleInputUsername1">Username</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" placeholder="Username" wire:model="username">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" wire:model="email">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                    <button type="button" class="btn btn-primary">{{ $editMode ? 'Update' : 'Save' }}</button>
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

    /* -------------------------------------------------------------------------- */

    const users = @json($users);
    new gridjs.Grid({
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
                    <button class="btn btn-success btn-sm btn-icon-text me-3" wire:click="readRow('${id}')"> Edit <i class="typcn typcn-edit btn-icon-append"></i></button>
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
</script>
@endscript