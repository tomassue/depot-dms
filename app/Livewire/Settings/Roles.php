<?php

namespace App\Livewire\Settings;

use App\Models\RefOfficesModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Title('Roles | DEPOT DMS')]
class Roles extends Component
{
    public $editMode, $disableInput;
    public $id_role;

    public $role, $selectedPermissions = [];

    public function render()
    {
        $data = [
            'roles' => $this->readRoles(),
            'permissions' => $this->readPermissions()
        ];

        return view('livewire.settings.roles', $data);
    }

    public function rules()
    {
        $rules = [
            'role' => ['required', Rule::unique('roles', 'name')->ignore($this->id_role, 'id')]
        ];

        return $rules;
    }

    public function refreshTableRoles()
    {
        $this->dispatch('refresh-table-roles', $this->readRoles());
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('refresh-plugins');
    }

    /* ---------------------------------- CRUD ---------------------------------- */

    public function createRole()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $role = new Role();
            $role->name = $this->role;
            $role->save();

            DB::commit();
            $this->clear();
            $this->hideAddRolesModal();
            $this->dispatch('show-success-save-message-toast');
            $this->refreshTableRoles();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readRole($key)
    { // Modal
        try {
            $role           = Role::findOrFail($key);
            $this->role     = $role->name;
            $this->id_role  = $role->id;
            $this->editMode = true;
            $this->showAddRolesModal();
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateRole()
    { // Modal
        DB::beginTransaction();
        try {
            $role = Role::find($this->id_role);
            $role->name = $this->role;
            $role->save();

            DB::commit();
            $this->clear();
            $this->hideAddRolesModal();
            $this->dispatch('show-success-update-message-toast');
            $this->refreshTableRoles();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readPermission($key)
    { // Modal - permission
        try {
            $role                      = Role::findById($key); // Retrieve the role by its ID
            $this->id_role             = $role->id; // Set the role ID
            $this->selectedPermissions = $role->permissions->pluck('id')->toArray(); // Get permissions currently assigned to the role

            $this->dispatch('show-permissions', $this->selectedPermissions);
            $this->showPermissionsModal();
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function assignPermissions()
    { // This method, at least for this component, this is where the super admin can assign or update permissions to a role. There's no need to  have a separate method for assigning and updating assigned permissions because of the syncPermissions().
        try {
            $role        = Role::findById($this->id_role); // Retrieve the role instance
            $permissions = Permission::whereIn('id', $this->selectedPermissions)->get(); // Retrieve permissions as models based on selected IDs

            $role->syncPermissions($permissions); // Sync permissions with the role

            $this->clear();
            $this->hideAddRolesModal();
            $this->dispatch('show-success-update-message-toast');
            $this->hidePermissionsModal();
            $this->refreshTableRoles();
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readRoles()
    {
        $roles = Role::all();

        return $roles;
    }

    public function readPermissions()
    { // Select
        // I will be using option group in virtual select but I will be making it manually since we are working on the permissions. This is for the front-end sake only.
        $permissions = [
            [
                'label' => 'Reports',
                'options' => Permission::whereIn('id', [66, 67])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Incoming Page',
                'options' => Permission::whereIn('id', [25, 26, 27])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            // [
            //     'label' => 'Job Order',
            //     'options' => Permission::whereIn('id', [58, 59, 60])
            //         ->get()
            //         ->map(function ($item) {
            //             return [
            //                 'label' => $item->name,
            //                 'value' => $item->id
            //             ];
            //         })->toArray()
            // ],
            [
                'label' => 'Mechanics Page',
                'options' => Permission::whereIn('id', [10, 11, 12, 19, 20])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Category Page',
                'options' => Permission::whereIn('id', [13, 14, 15, 21, 22])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Sub-category Page',
                'options' => Permission::whereIn('id', [33, 34, 35, 36, 37])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Location Page',
                'options' => Permission::whereIn('id', [16, 17, 18, 23, 24])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Office',
                'options' => Permission::whereIn('id', [28, 29, 30, 31, 32])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Status',
                'options' => Permission::whereIn('id', [38, 39, 40, 41, 42])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Type (Equipments / Vehicle)',
                'options' => Permission::whereIn('id', [48, 49, 50, 51, 52])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Model (Equipments / Vehicle)',
                'options' => Permission::whereIn('id', [53, 54, 55, 56, 57])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Type of Repair',
                'options' => Permission::whereIn('id', [43, 44, 45, 46, 47])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Signatories',
                'options' => Permission::whereIn('id', [61, 62, 63, 64, 65])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'User Management Page',
                'options' => Permission::whereIn('id', [1, 2, 3])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Roles Page',
                'options' => Permission::whereIn('id', [4, 5, 6])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ],
            [
                'label' => 'Permissions Page',
                'options' => Permission::whereIn('id', [7, 8, 9])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->name,
                            'value' => $item->id
                        ];
                    })->toArray()
            ]
        ];

        return $permissions;
    }

    /* -------------------------------- End CRUD -------------------------------- */

    /* --------------------------------- Modals --------------------------------- */

    public function showAddRolesModal()
    {
        $this->dispatch('showAddRolesModal');
    }

    public function hideAddRolesModal()
    {
        $this->dispatch('hideAddRolesModal');
    }

    public function showPermissionsModal()
    {
        $this->dispatch('showPermissionsModal');
    }

    public function hidePermissionsModal()
    {
        $this->dispatch('hidePermissionsModal');
    }

    /* ------------------------------- End Modals ------------------------------- */
}
