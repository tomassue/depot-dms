<?php

namespace App\Livewire\Settings;

use App\Models\RefTypeModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Type extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $type_id;
    /* ---------------------------------- Model --------------------------------- */
    public $type;

    public function rules()
    {
        $rules = [
            'type' => [
                'required',
                Rule::unique('ref_types', 'name')->ignore($this->type_id, 'id')
            ]
        ];

        return $rules;
    }

    public function render()
    {
        $data = [
            'types' => $this->readTypes()
        ];

        return view('livewire.settings.type', $data);
    }

    public function mount()
    {
        $this->authorize('read', RefTypeModel::class);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function readTypes()
    { // table_types
        $types = RefTypeModel::withTrashed()->get();

        return $types;
    }

    public function createType()
    {
        $this->authorize('create', RefTypeModel::class);

        $this->validate();

        try {
            DB::transaction(function () {
                $type = new RefTypeModel();
                $type->name = $this->type;
                $type->save();
            });

            $this->clear();
            $this->dispatch('hideTypeModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-types', $this->readTypes());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readType($key)
    {
        $this->authorize('read', RefTypeModel::class);

        try {
            $type           = RefTypeModel::withTrashed()->findOrFail($key);
            $this->type     = $type->name;
            $this->type_id  = $key;
            $this->editMode = true;

            $this->dispatch('showTypeModal');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateType()
    {
        $type = RefTypeModel::withTrashed()->findOrFail($this->type_id);

        $this->authorize('update', $type);

        $this->validate();

        try {
            DB::transaction(function () use ($type) {
                $type->name     = $this->type;
                $type->save();
            });

            $this->clear();
            $this->dispatch('hideTypeModal');
            $this->dispatch('show-success-update-message-toast');
            $this->dispatch('refresh-table-types', $this->readTypes());
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function softDeleteType($key)
    {
        $type = RefTypeModel::findOrFail($key);

        $this->authorize('delete', $type);

        try {
            DB::transaction(function () use ($type) {
                $type->delete();

                $this->clear();
                $this->dispatch('show-deactivated-message-toast');
                $this->dispatch('refresh-table-types', $this->readTypes());
            });
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function restoreType($key)
    {
        $type = RefTypeModel::onlyTrashed()->findOrFail($key);

        $this->authorize('restore', $type);

        try {
            DB::transaction(function () use ($type) {
                $type->restore();

                $this->clear();
                $this->dispatch('show-activated-message-toast');
                $this->dispatch('refresh-table-types', $this->readTypes());
            });
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
