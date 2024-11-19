<?php

namespace App\Livewire\Settings;

use App\Models\RefTypeOfRepairModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TypeOfRepair extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $type_of_repair_id;
    /* ---------------------------------- Model --------------------------------- */
    public $type_of_repair;

    public function rules()
    {
        $rules = [
            'type_of_repair' => [
                'required',
                Rule::unique('ref_type_of_repair', 'name')->ignore($this->type_of_repair_id, 'id')
            ]
        ];

        return $rules;
    }

    public function render()
    {
        $data = [
            'type_of_repairs' => $this->readTypeOfRepairs()
        ];

        return view('livewire.settings.type-of-repair', $data);
    }

    public function mount()
    {
        $this->authorize('read', RefTypeOfRepairModel::class);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function readTypeOfRepairs()
    { // table_type_of_repairs
        $type_of_repairs = RefTypeOfRepairModel::withTrashed()->get();

        return $type_of_repairs;
    }

    public function createTypeOfRepair()
    {
        $this->authorize('create', RefTypeOfRepairModel::class);

        $this->validate();

        try {
            DB::transaction(function () {
                $type_of_repair = new RefTypeOfRepairModel();
                $type_of_repair->name = $this->type_of_repair;
                $type_of_repair->save();
            });

            $this->clear();
            $this->dispatch('hideTypeOfRepairModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-type-of-repairs', $this->readTypeOfRepairs());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readTypeOfRepair($key)
    {
        $this->authorize('read', RefTypeOfRepairModel::class);

        try {
            $type_of_repair             = RefTypeOfRepairModel::withTrashed()->findOrFail($key);
            $this->type_of_repair       = $type_of_repair->name;
            $this->type_of_repair_id    = $key;
            $this->editMode             = true;

            $this->dispatch('showTypeOfRepairModal');
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateTypeOfRepair()
    {
        $type_of_repair = RefTypeOfRepairModel::withTrashed()->findOrFail($this->type_of_repair_id);

        $this->authorize('update', $type_of_repair);

        $this->validate();

        try {
            DB::transaction(function () use ($type_of_repair) {
                $type_of_repair->name = $this->type_of_repair;
                $type_of_repair->save();
            });

            $this->clear();
            $this->dispatch('hideTypeOfRepairModal');
            $this->dispatch('show-success-update-message-toast');
            $this->dispatch('refresh-table-type-of-repairs', $this->readTypeOfRepairs());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function softDeleteTypeOfRepair($key)
    {
        $type_of_repair = RefTypeOfRepairModel::findOrFail($key);

        $this->authorize('delete', $type_of_repair);

        try {
            $type_of_repair->delete();

            $this->clear();
            $this->dispatch('show-success-update-message-toast');
            $this->dispatch('refresh-table-type-of-repairs', $this->readTypeOfRepairs());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function restoreTypeOfRepair($key)
    {
        $type_of_repair = RefTypeOfRepairModel::onlyTrashed()->findOrFail($key);

        $this->authorize('restore', $type_of_repair);

        try {
            $type_of_repair->restore();

            $this->clear();
            $this->dispatch('show-activated-message-toast');
            $this->dispatch('refresh-table-type-of-repairs', $this->readTypeOfRepairs());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
