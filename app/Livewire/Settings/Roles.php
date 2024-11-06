<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Roles extends Component
{
    public $editMode, $disableInput;

    public $role;

    public function render()
    {
        $data = [
            'roles' => $this->readRoles()
        ];

        return view('livewire.settings.roles', $data);
    }

    public function refreshTable()
    {
        $this->dispatch('refresh-table', $this->readRoles());
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function createRole()
    {
        DB::beginTransaction();
        try {
            $role = new Role();
            $role->name = $this->role;
            $role->save();

            DB::commit();
            $this->clear();
            $this->hideAddRolesModal();
            $this->dispatch('show-success-save-message-toast');
            $this->refreshTable();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function readRow($key)
    {
        dd($key);
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
