<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
            $role = Role::findById($key);
            $this->id_role = $role->id;

            $this->dispatch('showPermissionsModal');
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function assignPermissions()
    {
        try {
            $role        = Role::findById($this->id_role); // Retrieve the role instance
            $permissions = Permission::whereIn('id', $this->selectedPermissions)->get(); // Retrieve permissions as models based on selected IDs

            $role->syncPermissions($permissions); // Sync permissions with the role

            $this->clear();
            $this->hideAddRolesModal();
            $this->dispatch('show-success-update-message-toast');
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

    public function showAddRolesModal()
    {
        $this->dispatch('showAddRolesModal');
    }

    public function hideAddRolesModal()
    {
        $this->dispatch('hideAddRolesModal');
    }
}
