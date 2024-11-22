<?php

namespace App\Livewire;

use App\Models\RefModelModel;
use App\Models\RefOfficesModel;
use App\Models\RefTypeModel;
use App\Models\TblIncomingRequestModel;
use App\Models\TblJobOrderModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Incoming extends Component
{
    use AuthorizesRequests;

    public $page = 2; // Change this to 1 after finishing the modal's layout in jobOrderModal

    /* -------------------------------------------------------------------------- */

    public $editMode, $disable_input;
    public $incoming_request_id;
    public $reference_no;
    public $job_orders = [];

    /* ---------------------------------- Model --------------------------------- */
    public $ref_office_id;
    public $date_and_time;
    public $ref_types_id;
    public $ref_models_id, $ref_models_id_2;
    public $number;
    public $mileage;
    public $driver_in_charge;
    public $contact_number;

    /* ----------------------------- Job Order Model ---------------------------- */
    public $ref_category_id;
    public $ref_sub_category_id;
    public $ref_location_id;
    public $ref_status_id;
    public $ref_type_of_repair_id;
    public $ref_mechanics;
    public $issue_or_concern;
    public $jo_date_and_time;
    public $total_repair_time;
    public $claimed_by;
    public $remarks;

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
    }

    public function generateReferenceNo()
    {
        $this->reference_no = TblIncomingRequestModel::generateUniqueReference('REF-', 8); // Pre-generate reference number to show in the input field (disabled).
    }

    public function updated($property)
    {
        $virtual_select = $this->loadPageData();

        if ($property === 'ref_types_id') {
            $this->dispatch('refresh-model-select-options', options: $virtual_select['models'], selected: $this->ref_models_id_2);
        }
    }

    public function clear()
    {
        $this->resetExcept('page');
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
        $incoming_requests = TblIncomingRequestModel::with(['office', 'type', 'model'])
            ->get();

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

            $table_incoming_request = $this->loadPageData(); // reloads the method so that we can fetch updated data from incoming_requests.
            $this->dispatch('refresh-table-incoming-requests', $table_incoming_request['incoming_requests']);
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readIncomingRequest($key)
    {
        $this->authorize('read', TblIncomingRequestModel::class);

        try {
            $this->editMode             = true;
            $this->reference_no         = $key;

            $incoming_request           = TblIncomingRequestModel::findOrFail($key);
            $this->incoming_request_id  = $incoming_request->id;
            $this->reference_no         = $incoming_request->reference_no;
            $this->dispatch('set-office-select', $incoming_request->ref_office_id);
            $this->dispatch('set-date-and-time', $incoming_request->date_and_time);
            $this->dispatch('set-type-select', $incoming_request->ref_types_id);

            /**
             * This is for displaying ref_models_id without causing it to disappear due to setOptions by assigning another property that will hold on this value.
             * Refer to updated($property) -> ($property === 'ref_types_id') if block.
             * */
            $this->ref_models_id_2      = $incoming_request->ref_models_id;

            $this->number               = $incoming_request->number;
            $this->mileage              = $incoming_request->mileage;
            $this->driver_in_charge     = $incoming_request->driver_in_charge;
            $this->contact_number       = $incoming_request->contact_number;

            $this->dispatch('showIncomingModal');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateIncomingRequest()
    {
        $incoming_request = TblIncomingRequestModel::findOrFail($this->incoming_request_id);

        $this->authorize('update', $incoming_request);

        try {
            DB::transaction(function () use ($incoming_request) {
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
            $this->dispatch('show-success-update-message-toast');

            $table_incoming_request = $this->loadPageData(); // reloads the method so that we can fetch updated data from incoming_requests.
            $this->dispatch('refresh-table-incoming-requests', $table_incoming_request['incoming_requests']);
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    /* -------------------------------------------------------------------------- */

    public function readJobOrders($key)
    {
        $this->authorize('read', TblIncomingRequestModel::class);

        try {
            $this->page = 2;

            $incoming_request       = TblIncomingRequestModel::with(['office', 'type', 'model'])->findOrFail($key);
            $this->reference_no     = $incoming_request->reference_no;
            $this->ref_office_id    = $incoming_request->office->name;
            $this->ref_types_id     = $incoming_request->type->name;
            $this->ref_models_id_2  = $incoming_request->model->name;
            $this->number           = $incoming_request->number;

            $job_orders             = TblJobOrderModel::where('reference_no', $incoming_request->reference_no)->get();
            $this->job_orders       = $job_orders;
        } catch (\Throwable $th) {
            $this->page = 1;
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
