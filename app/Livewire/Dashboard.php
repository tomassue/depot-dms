<?php

namespace App\Livewire;

use App\Models\RefCategoryModel;
use App\Models\RefLocationModel;
use App\Models\RefMechanicsModel;
use App\Models\RefStatusModel;
use App\Models\RefSubCategoryModel;
use App\Models\RefTypeOfRepairModel;
use App\Models\TblIncomingRequestModel;
use App\Models\TblJobOrderModel;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard | DEPOT DMS')]
class Dashboard extends Component
{
    public $editMode;
    public $ref_incoming_request_types_id;

    /* -------------------------------------------------------------------------- */

    public $ref_types_id;
    public $ref_models_id_2;
    public $number;

    public $job_order_no;
    public $person_in_charge;
    public $contact_number;
    public $ref_sub_category_id, $ref_sub_category_id_2;
    public $ref_category_id;
    public $ref_type_of_repair_id;
    public $ref_status_id;
    public $ref_mechanics;
    public $ref_location_id;
    public $mileage;
    public $date_and_time_in;
    public $issue_or_concern;
    public $findings;

    public $date_and_time_out;
    public $total_repair_time;
    public $claimed_by;
    public $remarks;

    public function render()
    {
        return view('livewire.dashboard', $this->loadPageData());
    }

    public function rules()
    {
        $rules = [
            'person_in_charge'      => 'required',
            'contact_number'        => 'required|size:11',
            'date_and_time_in'      => 'required',
            'ref_category_id'       => 'required',
            'ref_sub_category_id'   => 'required',
            'ref_type_of_repair_id' => 'required',
            'ref_mechanics'         => 'required',
            'ref_location_id'       => 'required',
            'issue_or_concern'      => 'required'
        ];
        if ($this->ref_incoming_request_types_id == '1') {
            $rules['mileage']       =  'required';
        }

        if ($this->editMode) {
            $rules['ref_status_id'] = 'required';
        }

        if ($this->ref_status_id == 2) {
            $rules = [
                'date_and_time_out' => 'required',
                'total_repair_time' => 'required',
                'claimed_by'        => 'required',
                'remarks'           => 'required'
            ];
        }

        if ($this->ref_status_id == 3) {
            $rules = [
                'date_and_time_out' => 'required',
                'claimed_by'        => 'required',
                'remarks'           => 'required'
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'ref_status_id'         => 'status',
            'ref_category_id'       => 'category',
            'ref_sub_category_id'   => 'sub-category',
            'ref_type_of_repair_id' => 'type of repair',
            'ref_mechanics'         => 'mechanics',
            'ref_location_id'       => 'location'
        ];

        if ($this->ref_status_id == 2) {
            $attributes = [
                'jo_date_and_time' => 'date and time'
            ];
        }

        return $attributes;
    }

    public function updated($property)
    {
        $loadPageData = $this->loadPageData();

        if ($property === 'ref_category_id') {
            $this->dispatch('refresh-sub-category-select-options', options: $loadPageData['sub_categories'], selected: $this->ref_sub_category_id_2);
        }

        if ($property === 'ref_status_id') {
            if ($this->ref_status_id == 2 || $this->ref_status_id == 3) {
                if ($this->ref_status_id == 2) {
                    # We automatically assigned that whoever's the person in charge, will be the one to claim. However, end user can still edit it.
                    $this->claimed_by = $this->person_in_charge;
                }
                $this->dispatch('showStatusUpdateModal');
            }
        }
    }

    public function clear2()
    {
        $this->resetExcept('page', 'job_order_no', 'reference_no', 'ref_office_id', 'ref_types_id', 'number', 'ref_models_id_2', 'mileage');
        $this->resetValidation();

        $this->dispatch('reset-status-select');
        $this->dispatch('reset-category-select');
        $this->dispatch('reset-type-of-repair-select');
        $this->dispatch('reset-sub-category-select');
        $this->dispatch('reset-mechanics-select');
        $this->dispatch('reset-location-select');
        $this->dispatch('reset-date-and-time-in');
        $this->dispatch('reset-issue-or-concern-summernote');

        $this->dispatch('reset-signatories-select');
    }

    public function clear3()
    { // custom clearing for statusUpdateModal
        $this->reset(['date_and_time_out', 'total_repair_time', 'claimed_by', 'remarks']);
        $this->dispatch('reset-date-and-time-out');
        $this->dispatch('set-status-select-pending');
        // $this->dispatch('hideStatusUpdateModal');
        $this->resetValidation();
    }

    public function loadPageData()
    {
        # Total Job Orders
        $total = TblJobOrderModel::count();

        # Pending Job Orders
        $pending = TblJobOrderModel::where('ref_status_id', 1)->count();

        # Accomplished Job Orders
        $done = TblJobOrderModel::where('ref_status_id', 2)->count();

        # Referred Job Orders
        $referred = TblJobOrderModel::where('ref_status_id', 3)->count();

        # Table Pending Job Orders
        $table_pending_job_orders = TblJobOrderModel::with(['category', 'sub_category', 'status'])->where('ref_status_id', 1)->get();

        # status-select
        $statuses = RefStatusModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        # category-select
        $categories = RefCategoryModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        // sub-category-select
        $sub_categories = RefSubCategoryModel::when($this->ref_category_id, function ($query) {
            return $query->where('id_ref_category', $this->ref_category_id);
        })
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        # type-of-repair-select
        $type_of_repairs = RefTypeOfRepairModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        # mechanics-select
        $mechanics = RefMechanicsModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        # location-select
        $locations = RefLocationModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        return [
            'total'                    => $total,
            'pending'                  => $pending,
            'done'                     => $done,
            'referred'                 => $referred,
            'table_pending_job_orders' => $table_pending_job_orders,
            'statuses'                 => $statuses,
            'categories'               => $categories,
            'type_of_repairs'          => $type_of_repairs,
            'sub_categories'           => $sub_categories,
            'mechanics'                => $mechanics,
            'locations'                => $locations
        ];
    }

    public function readJobOrder($key)
    {
        $this->authorize('read', TblIncomingRequestModel::class);

        try {
            $this->editMode              = true;

            $job_order                   = TblJobOrderModel::findOrFail($key);

            $this->job_order_no          = $job_order->id;
            $this->contact_number        = $job_order->contact_number;
            $this->ref_sub_category_id_2 = $job_order->ref_sub_category_id;
            $this->person_in_charge      = $job_order->person_in_charge;
            $this->mileage               = $job_order->mileage;

            $this->dispatch('set-category-select', $job_order->ref_category_id);
            $this->dispatch('set-type-of-repair-select', $job_order->ref_type_of_repair_id);
            $this->dispatch('set-status-select', $job_order->ref_status_id);
            $this->dispatch('set-mechanics-select', json_decode($job_order->ref_mechanics));
            $this->dispatch('set-location-select', $job_order->ref_location_id);
            $this->dispatch('set-date-and-time-in', $job_order->date_and_time_in);
            $this->dispatch('set-issue-or-concern-summernote', $job_order->issue_or_concern);

            $incoming_request                    = TblIncomingRequestModel::where('reference_no', $job_order->reference_no)->first();
            $this->ref_incoming_request_types_id = $incoming_request->ref_incoming_request_types_id;
            $this->ref_types_id                  = $incoming_request->type->name;
            $this->ref_models_id_2               = $incoming_request->model->name;
            $this->number                        = $incoming_request->number;

            $this->dispatch('showJobOrderModal');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateJobOrder()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $job_order = TblJobOrderModel::findOrFail($this->job_order_no);

                $job_order->ref_status_id           = $this->ref_status_id;
                $job_order->person_in_charge        = $this->person_in_charge;
                $job_order->contact_number          = $this->contact_number;
                $job_order->ref_category_id         = $this->ref_category_id;
                $job_order->ref_type_of_repair_id   = $this->ref_type_of_repair_id;
                $job_order->ref_sub_category_id     = $this->ref_sub_category_id;
                $job_order->ref_mechanics           = json_encode($this->ref_mechanics);
                $job_order->ref_location_id         = $this->ref_location_id;
                $job_order->date_and_time_in        = $this->date_and_time_in;
                $job_order->issue_or_concern        = $this->issue_or_concern;
                $job_order->findings                = $this->findings;

                if ($this->ref_status_id == 2) {
                    $job_order->date_and_time_out   = $this->date_and_time_out;
                    $job_order->total_repair_time   = $this->total_repair_time;
                    $job_order->claimed_by          = $this->claimed_by;
                    $job_order->remarks             = $this->remarks;
                }

                if ($this->ref_status_id == 3) {
                    $job_order->date_and_time_out   = $this->date_and_time_out;
                    $job_order->claimed_by          = $this->claimed_by;
                    $job_order->remarks             = $this->remarks;
                }

                $job_order->save();
            });

            if ($this->ref_status_id == 2 || $this->ref_status_id == 3) {
                $this->clear3();
                $this->dispatch('hideBothJobOrderModalAndStatusUpdateModal');
            } else {
                $this->clear2();
                $this->dispatch('hideJobOrderModal');
            }

            $this->dispatch('show-success-update-message-toast');

            $loadPageData = $this->loadPageData();
            $this->dispatch('refresh-table-incoming-requests', $loadPageData['table_pending_job_orders']);
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
