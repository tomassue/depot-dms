<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Title('User Management | DEPOT DMS')]
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
        $this->dispatch('refresh-plugins');
    }

    public function refreshTableUsers()
    {
        $this->dispatch('refresh-table-users', $this->readUsers());
    }

    public function createUser()
    {
        $this->authorize('create', User::class); // UserPolicy. Proceed with creating a new user if authorized
        $this->validate();

        DB::beginTransaction();
        try {
            $user           = new User();
            $user->username = $this->username;
            $user->name     = $this->name;
            $user->email    = $this->email;
            $user->password = Hash::make('password');
            $user->save();

            if ($this->role) {
                $role = Role::findById($this->role); // Retrieve the role instance by ID
                $user->syncRoles($role);

                // Log the role assignment
                // We can't have it the same with User Model where we use the trait and have getActivitylogOptions() method. Roles and Permission, their models are automatically included because it is a package. It is much safer if we just manually log them if there are changes in the role or permission because modifying the models in the package can lead to errors.
                activity()
                    ->causedBy(Auth::user()) // Who made the change. Should be an instance.
                    ->performedOn($user) // The affected user
                    ->withProperties(['role' => $role->name])
                    ->event('assigned role')
                    ->log("Role '{$role->name}' assigned to user '{$user->name}'");
            }

            DB::commit();
            $this->clear();
            $this->hideAddUserModal();
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-plugins');
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
            $this->dispatch('select-role', $user->roles->pluck('id')->toArray());

            $this->dispatch('showUserModal');
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateUser()
    {
        $this->authorize('update', User::class); // UserPolicy. Proceed with updating a new user if authorized

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
                $user->syncRoles($role);

                // Log the role assignment
                // We can't have it the same with User Model where we use the trait and have getActivitylogOptions() method. Roles and Permission, their models are automatically included because it is a package. It is much safer if we just manually log them if there are changes in the role or permission because modifying the models in the package can lead to errors.
                activity()
                    ->causedBy(Auth::user()) // Who made the change. Should be an instance.
                    ->performedOn($user) // The affected user
                    ->withProperties(['role' => $role->name])
                    ->event('granted role')
                    ->log("Role '{$role->name}' granted to user '{$user->name}'");
            }

            DB::commit();
            $this->clear();
            $this->hideAddUserModal();
            $this->dispatch('show-success-update-message-toast');
            $this->dispatch('refresh-plugins');
            $this->refreshTableUsers();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function resetPassword($key)
    {
        $this->authorize('update', User::class); // UserPolicy. Proceed with updating a new user if authorized

        try {
            DB::transaction(function () use ($key) {
                $query = User::findOrFail($key);
                $query->password = Hash::make('password');
                $query->save();
            });

            $this->clear();
            $this->dispatch('show-success-reset-password-message-toast');
            $this->refreshTableUsers();
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function activateUser($key)
    {
        $this->authorize('update', User::class); // UserPolicy. Proceed with updating a new user if authorized

        try {
            DB::transaction(function () use ($key) {
                $query = User::findOrFail($key);
                $query->is_active = 'yes';
                $query->save();
            });

            $this->clear();
            $this->dispatch('show-activated-message-toast');
            $this->refreshTableUsers();
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function deactivateUser($key)
    {
        $this->authorize('update', User::class); // UserPolicy. Proceed with updating a new user if authorized

        try {
            DB::transaction(function () use ($key) {
                $query = User::findOrFail($key);
                $query->is_active = 'no';
                $query->save();
            });

            $this->clear();
            $this->dispatch('show-deactivated-message-toast');
            $this->refreshTableUsers();
        } catch (\Throwable $th) {
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
        $users = User::with('roles')->get(); // I'm using Laravel Permission and here's how we retrieve the user's role

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
