<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Roles extends Component
{
    public $editMode, $disableInput;
    public $id_role;

    public $role;

    public function render()
    {
        $data = [
            'roles' => $this->readRoles()
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

    public function readRoles()
    {
        $roles = Role::all();

        return $roles;
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
