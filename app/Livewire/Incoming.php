<?php

namespace App\Livewire;

use App\Models\RefModelModel;
use App\Models\RefOfficesModel;
use App\Models\RefTypeModel;
use App\Models\TblIncomingRequestModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Incoming extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $reference_no;
    /* ---------------------------------- Model --------------------------------- */
    public $ref_office_id;
    public $date_and_time;
    public $ref_types_id;
    public $ref_models_id;
    public $number;
    public $mileage;
    public $driver_in_charge;
    public $contact_number;

    public function rules()
    {
        $rules = [
            'ref_office_id' => 'required',
            'date_and_time' => 'required',
            'ref_types_id'  => 'required',
            'ref_models_id' => 'required',
            'number'        => 'required',
            'mileage'       => 'required',
            'driver_in_charge' => 'required',
            'contact_number' => 'required',
        ];

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'ref_office_id' => 'office',
            'ref_types_id' => 'type of equipment or vehicle',
            'ref_models_id' => 'model of equipment or vehicle'
        ];

        return $attributes;
    }

    public function render()
    {
        return view('livewire.incoming', $this->loadPageData());
    }

    public function mount()
    {
        $this->authorize('read', TblIncomingRequestModel::class);

        $this->reference_no = TblIncomingRequestModel::generateUniqueReference('REF-', 8); // Pre-generate reference number to show in the input field (disabled).

    }

    public function updated($property)
    {
        if ($property === 'ref_types_id') {
            $virtual_select = $this->loadPageData();

            $this->dispatch('refresh-model-select-options', $virtual_select['models']); // trigger an event then update the options.
        }
    }

    public function clear()
    {
        $this->resetExcept('reference_no');
        $this->resetValidation();

        $this->dispatch('reset-date-and-time');
        $this->dispatch('reset-office-select');
        $this->dispatch('reset-type-select');
        $this->dispatch('reset-model-select');
    }

    // This function reads all necessary data from the database.
    public function loadPageData()
    {
        // table_incoming_requests
        $incoming_requests = TblIncomingRequestModel::with(['office', 'type', 'model'])->get();

        // office-select
        $offices = RefOfficesModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        // type-select
        $types = RefTypeModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        // model-select
        $models = RefModelModel::when($this->ref_types_id, function ($query) {
            return $query->where('ref_types_id', $this->ref_types_id);
        })
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        return [
            'incoming_requests' => $incoming_requests,
            'offices' => $offices,
            'types'   => $types,
            'models'  => $models
        ];
    }

    public function createIncomingRequest()
    {
        $this->authorize('create', TblIncomingRequestModel::class);

        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $incoming_request                   = new TblIncomingRequestModel();
                $incoming_request->reference_no     = $this->reference_no;
                $incoming_request->ref_office_id    = $this->ref_office_id;
                $incoming_request->date_and_time    = $this->date_and_time;
                $incoming_request->ref_types_id     = $this->ref_types_id;
                $incoming_request->ref_models_id    = $this->ref_models_id;
                $incoming_request->number           = strtoupper($this->number);
                $incoming_request->mileage          = $this->mileage;
                $incoming_request->driver_in_charge = $this->driver_in_charge;
                $incoming_request->contact_number   = $this->contact_number;
                $incoming_request->save();
            });

            $this->clear();
            $this->dispatch('hideIncomingModal');
            $this->dispatch('show-success-save-message-toast');

            $loadPageData = $this->loadPageData(); // reloads the method so that we can fetch updated data from incoming_requests.
            $this->dispatch('refresh-table-incoming-requests', $loadPageData['incoming_requests']);
        } catch (\Throwable $th) {
            dd($th);
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
