<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use AuthorizesRequests;

    public $editMode, $disableInput;
    public $id_user;

    public $username, $name, $email, $role;

    public function mount()
    {
        $this->authorize('can read user management'); // This will throw a 403 error if the user doesn't have permission
    }

    public function rules()
    {
        $rules = [
            'username' => ['required', Rule::unique('users', 'username')->ignore($this->id_user, 'id')],
            'name'     => 'required',
            'email'    => ['required', Rule::unique('users', 'email')->ignore($this->id_user, 'id')],
            'role'     => 'required'
        ];

        return $rules;
    }

    public function render()
    {
        $data = [
            'users' => $this->readUsers(),
            'roles' => $this->readRoles()
        ];

        return view('livewire.settings.user-management', $data);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function refreshTableUsers()
    {
        $this->dispatch('refresh-table-users', $this->readUsers());
    }

    public function createUser()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $user           = new User();
            $user->username = $this->username;
            $user->name     = $this->name;
            $user->email    = $this->email;
            $user->password = Hash::make('password');
            $user->save();

            DB::commit();
            $this->clear();
            $this->hideAddUserModal();
            $this->dispatch('show-success-save-message-toast');
            $this->refreshTableUsers();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readUser($key)
    {
        try {
            $this->editMode = true;

            $user           = User::find($key);
            $this->id_user  = $user->id;
            $this->username = $user->username;
            $this->name     = $user->name;
            $this->email    = $user->email;

            $this->dispatch('showUserModal');
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateUser()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $user = User::find($this->id_user);
            $user->username = $this->username;
            $user->name = $this->name;
            $user->email = $this->email;
            $user->save();

            if ($this->role) {
                $role = Role::findById($this->role); // Retrieve the role instance by ID
                $user->assignRole($role);
            }

            DB::commit();
            $this->clear();
            $this->hideAddUserModal();
            $this->dispatch('show-success-update-message-toast');
            $this->refreshTableUsers();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readUserRole($key)
    {
        $this->id_user = $key;

        $this->dispatch('showRoleModal');
    }

    public function readUsers()
    {
        $users = User::all();

        return $users;
    }

    public function readRoles()
    {
        $roles = Role::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        return $roles;
    }

    public function showAddUserModal()
    {
        $this->dispatch('showUserModal');
    }

    public function hideAddUserModal()
    {
        $this->dispatch('hideUserModal');
    }

    public function showRoleModal()
    {
        $this->dispatch('showRoleModal');
    }
}
