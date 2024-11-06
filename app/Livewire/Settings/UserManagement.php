<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Livewire\Component;

class UserManagement extends Component
{
    public $editMode, $disableInput;

    public $username, $name, $email;

    public function render()
    {
        $data = [
            'users' => $this->readUsers()
        ];

        return view('livewire.settings.user-management', $data);
    }

    public function clear()
    {
        $this->reset();
    }

    public function readUsers()
    {
        $users = User::all();

        return $users;
    }

    public function readRow($key)
    {
        dd($key);
    }

    public function showAddUserModal()
    {
        $this->dispatch('showUserModal');
    }
}
