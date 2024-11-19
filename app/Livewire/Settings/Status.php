<?php

namespace App\Livewire\Settings;

use App\Models\RefStatusModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Status extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $status_id;
    /* ---------------------------------- Model --------------------------------- */
    public $status;

    public function rules()
    {
        $rules = [
            'status' => [
                'required',
                Rule::unique('ref_status', 'name')->ignore($this->status_id, 'id')
            ]
        ];

        return $rules;
    }

    public function render()
    {
        $data = [
            'statuses' => $this->readStatuses()
        ];

        return view('livewire.settings.status', $data);
    }

    public function mount()
    {
        $this->authorize('read', RefStatusModel::class);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function readStatuses()
    { // table_statuses
        $statuses = RefStatusModel::withTrashed()
            ->get();

        return $statuses;
    }

    public function createStatus()
    {
        $this->authorize('create', RefStatusModel::class);

        $this->validate();

        try {
            DB::transaction(function () {
                $status = new RefStatusModel();
                $status->name = $this->status;
                $status->save();
            });

            $this->clear();
            $this->dispatch('hideStatusModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-statuses', $this->readStatuses());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readStatus($key)
    {
        $this->authorize('read', RefStatusModel::class);

        try {
            $status             = RefStatusModel::withTrashed()->findOrFail($key);
            $this->status       = $status->name;
            $this->status_id    = $key;
            $this->editMode     = true;

            $this->dispatch('showStatusModal');
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateStatus()
    {
        $status = RefStatusModel::withTrashed()->findOrFail($this->status_id);

        $this->authorize('update', $status);

        $this->validate();

        try {
            DB::transaction(function () use ($status) {
                $status->name = $this->status;
                $status->save();
            });

            $this->clear();
            $this->dispatch('hideStatusModal');
            $this->dispatch('show-success-update-message-toast');
            $this->dispatch('refresh-table-statuses', $this->readStatuses());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function softDeleteStatus($key)
    {
        $status = RefStatusModel::findOrFail($key);

        $this->authorize('delete', $status);

        try {
            DB::transaction(function () use ($status) {
                $status->delete();
            });

            $this->clear();
            $this->dispatch('show-deactivated-message-toast');
            $this->dispatch('refresh-table-statuses', $this->readStatuses());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function restoreStatus($key)
    {
        $status = RefStatusModel::onlyTrashed()->findOrFail($key);

        $this->authorize('delete', $status);

        try {
            DB::transaction(function () use ($status) {
                $status->restore();
            });

            $this->clear();
            $this->dispatch('show-activated-message-toast');
            $this->dispatch('refresh-table-statuses', $this->readStatuses());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
