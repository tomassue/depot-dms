<?php

namespace App\Livewire\Settings;

use App\Models\RefMechanicsModel;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Mechanics extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $id_mechanic;

    /* ---------------------------------- Model --------------------------------- */
    public $mechanic;

    public function mount()
    {
        $this->authorize('can read mechanics');
    }

    public function rules()
    {
        $rules = [
            'mechanic' => ['required', Rule::unique('ref_mechanics', 'name')->ignore($this->id_mechanic, 'id')]
        ];

        return $rules;
    }

    public function render()
    {
        $data = [
            'mechanics' => $this->readMechanics()
        ];

        return view('livewire.settings.mechanics', $data);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function readMechanics()
    { // table_mechanics
        $mechanics = RefMechanicsModel::all();

        return $mechanics;
    }

    /* --------------------------------- Modals --------------------------------- */
    public function showAddMechanicsModal()
    {
        $this->dispatch('showAddMechanicsModal');
    }

    public function createMechanic()
    {
        $this->authorize('can create mechanics');

        $this->validate();

        //TODO - Continue working on the References->Dropdown CRUD FUNCTIONS
    }
}
