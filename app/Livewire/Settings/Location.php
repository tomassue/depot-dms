<?php

namespace App\Livewire\Settings;

use App\Models\RefLocationModel;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Location extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $id_location;

    /* ---------------------------------- Model --------------------------------- */
    public $location;

    public function mount()
    {
        $this->authorize('can read location');
    }

    public function rules()
    {
        $rules = [
            'location' => ['required', Rule::unique('ref_location', 'name')->ignore($this->id_location, 'id')]
        ];

        return $rules;
    }

    public function render()
    {
        $data = [
            'locations' => $this->readLocations()
        ];

        return view('livewire.settings.location', $data);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function readLocations()
    { // table_locations
        $location = RefLocationModel::withTrashed()->get();

        return $location;
    }

    public function createLocation()
    {
        $this->authorize('create', RefLocationModel::class);

        $this->validate();

        try {
            DB::transaction(function () {
                $location       = new RefLocationModel();
                $location->name = $this->location;
                $location->save();
            });

            $this->clear();
            $this->dispatch('hideLocationModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-locations', $this->readLocations());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readLocation($key)
    {
        try {
            $location           = RefLocationModel::findOrFail($key);
            $this->location     = $location->name;
            $this->id_location  = $location->id;
            $this->editMode     = true;

            $this->dispatch('showLocationModal');
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateLocation()
    {
        $location = RefLocationModel::withTrashed()->findOrFail($this->id_location);

        $this->authorize('update', $location);

        $this->validate();

        try {
            DB::transaction(function () use ($location) {
                // $location is defined outside the closure, so if you try to use it within the DB::transaction() callback, it won’t work as expected because of variable scope
                // To ensure $location is accessible within the closure, pass it in using the use keyword in the closure definition.
                $location->name = $this->location;
                $location->save();

                $this->clear();
                $this->dispatch('hideLocationModal');
                $this->dispatch('show-success-update-message-toast');
                $this->dispatch('refresh-table-locations', $this->readLocations());
            });
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function softDeleteLocation($key)
    {
        $location = RefLocationModel::findOrFail($key);

        $this->authorize('delete', $location);

        try {
            DB::transaction(function () use ($location) {
                $location->delete();

                $this->clear();
                $this->dispatch('show-deactivated-message-toast');
                $this->dispatch('refresh-table-locations', $this->readLocations());
            });
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function restoreLocation($key)
    {
        $location = RefLocationModel::withTrashed()->findOrFail($key);

        $this->authorize('restore', $location);

        try {
            DB::transaction(function () use ($location) {
                $location->restore();

                $this->clear();
                $this->dispatch('show-activated-message-toast');
                $this->dispatch('refresh-table-locations', $this->readLocations());
            });
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
