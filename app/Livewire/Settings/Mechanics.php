<?php

namespace App\Livewire\Settings;

use App\Models\RefMechanicsModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Mechanics | DEPOT DMS')]
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

    public function refreshTableMechanics()
    {
        $this->dispatch('refresh-table-mechanics', $this->readMechanics());
    }

    public function readMechanics()
    { // table_mechanics
        $mechanics = RefMechanicsModel::withTrashed()->get();

        return $mechanics;
    }

    /* --------------------------------- Modals --------------------------------- */
    public function showAddMechanicsModal()
    {
        $this->dispatch('showAddMechanicsModal');
    }

    public function createMechanic()
    {
        $this->authorize('create', RefMechanicsModel::class);
        $this->validate();

        DB::beginTransaction();
        try {
            $mechanic = new RefMechanicsModel();
            $mechanic->name = $this->mechanic;
            $mechanic->save();
            DB::commit();
            $this->clear();
            $this->dispatch('hideAddMechanicsModal');
            $this->dispatch('show-success-save-message-toast');
            $this->refreshTableMechanics();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readMechanic($key)
    {
        try {
            $mechanic           = RefMechanicsModel::withTrashed()->findOrFail($key);
            $this->mechanic     = $mechanic->name;
            $this->id_mechanic  = $key;
            $this->editMode     = true;
            $this->showAddMechanicsModal();
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateMechanic()
    {
        $mechanic = RefMechanicsModel::withTrashed()->findOrFail($this->id_mechanic); // Include soft-deleted records in the search
        $this->authorize('update', $mechanic);
        $this->validate();
        DB::beginTransaction();
        try {
            $mechanic->name = $this->mechanic;
            $mechanic->save();
            DB::commit();
            $this->clear();
            $this->dispatch('hideAddMechanicsModal');
            $this->dispatch('show-success-update-message-toast');
            $this->refreshTableMechanics();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function softDeleteMechanic($key)
    {
        $mechanic = RefMechanicsModel::findOrFail($key);
        $this->authorize('delete', $mechanic); // Pass the specific instance of RefMechanicsModel to the authorize method

        DB::beginTransaction();
        try {
            $mechanic->delete();

            DB::commit();

            $this->clear();
            $this->dispatch('show-deactivated-message-toast');
            $this->refreshTableMechanics();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function restoreMechanic($key)
    {
        $mechanic = RefMechanicsModel::withTrashed()->findOrFail($key); // Include soft-deleted records in the search
        $this->authorize('restore', $mechanic); // Authorize the restore action

        DB::beginTransaction();
        try {
            $mechanic->restore(); // Restore the soft-deleted mechanic record
            DB::commit();
            $this->clear();
            $this->dispatch('show-activated-message-toast');
            $this->refreshTableMechanics();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
