<?php

namespace App\Livewire\Settings;

use App\Models\RefOfficesModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Office extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $id_office;

    /* ---------------------------------- Model --------------------------------- */
    public $office;

    public function rules()
    {
        $rules = [
            'office' => ['required', Rule::unique('ref_offices', 'name')->ignore($this->id_office, 'id')]
        ];

        return $rules;
    }

    public function render()
    {
        $data = [
            'offices' => $this->readOffices()
        ];

        return view('livewire.settings.office', $data);
    }

    public function mount()
    {
        $this->authorize('read', RefOfficesModel::class);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function readOffices()
    {
        $offices = RefOfficesModel::withTrashed()->get();

        return $offices;
    }

    public function createOffice()
    {
        $this->authorize('create', RefOfficesModel::class);

        $this->validate();

        try {
            DB::transaction(function () {
                $office       = new RefOfficesModel();
                $office->name = $this->office;
                $office->save();
            });

            $this->clear();
            $this->dispatch('hideOfficeModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-offices', $this->readOffices());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readOffice($key)
    {
        $this->authorize('read', RefOfficesModel::class);

        try {
            $office             = RefOfficesModel::findOrFail($key);
            $this->office       = $office->name;
            $this->id_office    = $key;
            $this->editMode     = true;

            $this->dispatch('showOfficeModal');
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateOffice()
    {
        $office = RefOfficesModel::findOrFail($this->id_office);

        $this->authorize('update', $office);

        $this->validate();

        try {
            DB::transaction(function () use ($office) {
                $office->name = $this->office;
                $office->save();
            });

            $this->clear();
            $this->dispatch('hideOfficeModal');
            $this->dispatch('show-success-update-message-toast');
            $this->dispatch('refresh-table-offices', $this->readOffices());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function softDeleteOffice($key)
    {
        $office = RefOfficesModel::findOrFail($key);

        $this->authorize('delete', $office);

        try {
            DB::transaction(function () use ($office) {
                $office->delete();

                $this->clear();
                $this->dispatch('show-deactivated-message-toast');
                $this->dispatch('refresh-table-offices', $this->readOffices());
            });
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function restoreOffice($key)
    {
        $office = RefOfficesModel::withTrashed()->findOrFail($key);

        $this->authorize('restore', $office);

        try {
            DB::transaction(function () use ($office) {
                $office->restore();

                $this->clear();
                $this->dispatch('show-activated-message-toast');
                $this->dispatch('refresh-table-offices', $this->readOffices());
            });
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
