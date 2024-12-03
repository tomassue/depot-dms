<?php

namespace App\Livewire\Settings;

use App\Models\RefModelModel;
use App\Models\RefTypeModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Model | DEPOT DMS')]
class Model extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $model_id;
    /* ---------------------------------- Model --------------------------------- */
    public $ref_types_id;
    public $model;

    public function rules()
    {
        $rules = [
            'ref_types_id' => 'required',
            'model' => [
                'required',
                Rule::unique('ref_models', 'name')
                    ->where(function ($query) {
                        $query->where('ref_types_id', $this->ref_types_id);
                    })
                    ->ignore($this->model_id, 'id')
            ]
        ];

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'ref_types_id' => 'type'
        ];

        return $attributes;
    }

    public function render()
    {
        $data = [
            'models' => $this->readModels(),
            'types' => $this->readTypes()
        ];

        return view('livewire.settings.model', $data);
    }

    public function mount()
    {
        $this->authorize('read', RefModelModel::class);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('reset-types-select');
    }

    public function readModels()
    { // table_models
        $models = RefModelModel::with('type')
            ->withTrashed()
            ->get();

        return $models;
    }

    public function readTypes()
    { // types-select
        $types = RefTypeModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        return $types;
    }

    public function createModel()
    {
        $this->authorize('create', RefModelModel::class);

        $this->validate();

        try {
            DB::transaction(function () {
                $model               = new RefModelModel();
                $model->ref_types_id = $this->ref_types_id;
                $model->name         = $this->model;
                $model->save();
            });

            $this->clear();
            $this->dispatch('hideModelModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-models', $this->readModels());
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readModel($key)
    {
        $this->authorize('read', RefModelModel::class);

        try {
            $model = RefModelModel::withTrashed()->findOrFail($key);
            $this->dispatch('set-types-select', $model->ref_types_id);
            $this->model = $model->name;
            $this->model_id = $key;
            $this->editMode = true;

            $this->dispatch('showModelModal');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateModel()
    {
        $model = RefModelModel::withTrashed()->findOrFail($this->model_id);

        $this->authorize('update', $model);

        $this->validate();

        try {
            DB::transaction(function () use ($model) {
                $model->ref_types_id = $this->ref_types_id;
                $model->name         = $this->model;
                $model->save();
            });

            $this->clear();
            $this->dispatch('hideModelModal');
            $this->dispatch('show-success-update-message-toast');
            $this->dispatch('refresh-table-models', $this->readModels());
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function softDeleteModel($key)
    {
        $model = RefModelModel::findOrFail($key);

        $this->authorize('delete', $model);

        try {
            DB::transaction(function () use ($model) {
                $model->delete();
            });

            $this->clear();
            $this->dispatch('show-deactivated-message-toast');
            $this->dispatch('refresh-table-models', $this->readModels());
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function restoreModel($key)
    {
        $model = RefModelModel::onlyTrashed()->findOrFail($key);

        $this->authorize('restore', $model);

        try {
            DB::transaction(function () use ($model) {
                $model->restore();
            });

            $this->clear();
            $this->dispatch('show-deactivated-message-toast');
            $this->dispatch('refresh-table-models', $this->readModels());
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
