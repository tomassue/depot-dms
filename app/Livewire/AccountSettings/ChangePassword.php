<?php

namespace App\Livewire\AccountSettings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Change Password | DEPOT DMS')]
class ChangePassword extends Component
{
    public $oldPassword;
    public $newPassword;
    public $confirmPassword;

    public function render()
    {
        return view('livewire.account-settings.change-password');
    }

    public function rules()
    {
        $rules = [
            'oldPassword'       => 'required|current_password',
            'newPassword'       => 'required|min:8|regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'confirmPassword'   => 'required|same:newPassword',
        ];

        return $rules;
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function updatePassword()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $query = User::findOrFail(Auth::id());
                $query->password = Hash::make($this->newPassword);
                $query->save();
            });

            $this->clear();
            return redirect()->route('dashboard');
            // $this->dispatch('show-success-update-password-message-toast');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
