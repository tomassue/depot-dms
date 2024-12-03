<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

#[Title('Permissions | DEPOT DMS')]
class Permissions extends Component
{
    public $editMode, $disableInput;
    public $id_permission;

    public $permission;

    public function rules()
    {
        $rules = [
            'permission' => ['required', Rule::unique('permissions', 'name')->ignore($this->id_permission, 'id')]
        ];

        return $rules;
    }

    public function render()
    {
        $data = [
            'permissions' => $this->readPermissions()
        ];

        return view('livewire.settings.permissions', $data);
    }

    public function refreshTablePermissions()
    {
        $this->dispatch('refresh-table-permissions', $this->readPermissions());
    }

    public function createPermission()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $permission = new Permission();
            $permission->name = strtolower($this->permission);
            $permission->save();
            DB::commit();
            $this->clear();
            $this->hideAddPermissionsModal();
            $this->dispatch('show-success-save-message-toast');
            $this->refreshTablePermissions();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function readPermission($key)
    { // Modal
        try {
            $permission          = Permission::findOrFail($key);
            $this->permission    = $permission->name;
            $this->id_permission = $permission->id;
            $this->showAddPermissionsModal();
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readPermissions()
    {
        $permissions = Permission::all();

        return $permissions;
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function showAddPermissionsModal()
    {
        $this->dispatch('showAddPermissionsModal');
    }

    public function hideAddPermissionsModal()
    {
        $this->dispatch('hideAddPermissionsModal');
    }
}
