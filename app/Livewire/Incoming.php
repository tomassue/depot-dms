<?php

namespace App\Livewire;

use App\Models\RefCategoryModel;
use App\Models\RefLocationModel;
use App\Models\RefMechanicsModel;
use App\Models\RefModelModel;
use App\Models\RefOfficesModel;
use App\Models\RefStatusModel;
use App\Models\RefSubCategoryModel;
use App\Models\RefTypeModel;
use App\Models\RefTypeOfRepairModel;
use App\Models\TblIncomingRequestModel;
use App\Models\TblJobOrderModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Incoming extends Component
{
    use AuthorizesRequests;

    public $page = 1; // Change this to 1 after finishing the modal's layout in jobOrderModal

    /* -------------------------------------------------------------------------- */

    public $editMode, $disable_input;
    public $incoming_request_id;
    public $job_orders = [];
    public $reference_no;
    public $job_order_no;
    public $job_order_details_pdf;

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
    public $ref_sub_category_id, $ref_sub_category_id_2;
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

        if ($this->page == 2) {
            $rules = [
                // 'ref_status_id' => 'required',
                'ref_category_id' => 'required',
                'ref_sub_category_id' => 'required',
                'ref_type_of_repair_id' => 'required',
                'ref_mechanics' => 'required',
                'ref_location_id' => 'required',
                'issue_or_concern' => 'required'
            ];

            if ($this->ref_status_id == 2) {
                $rules = [
                    'jo_date_and_time' => 'required',
                    'total_repair_time' => 'required',
                    'claimed_by' => 'required',
                    'remarks' => 'required'
                ];
            }
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'ref_office_id' => 'office',
            'ref_types_id' => 'type of equipment or vehicle',
            'ref_models_id' => 'model of equipment or vehicle'
        ];

        if ($this->page == 2) {
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
        }

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

        if ($property === 'ref_category_id') {
            $this->dispatch('refresh-sub-category-select-options', options: $virtual_select['sub_categories'], selected: $this->ref_sub_category_id_2);
        }

        if ($property === 'ref_status_id') {
            if ($this->ref_status_id == 2) {
                $this->dispatch('showStatusUpdateModal');
            }
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

        // status-select
        $statuses = RefStatusModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        // category-select
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

        // type-of-repair-select
        $type_of_repairs = RefTypeOfRepairModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        // mechanics-select
        $mechanics = RefMechanicsModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        // location-select
        $locations = RefLocationModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        return [
            'incoming_requests' => $incoming_requests,
            'offices'           => $offices,
            'types'             => $types,
            'models'            => $models,
            'statuses'          => $statuses,
            'categories'        => $categories,
            'type_of_repairs'   => $type_of_repairs,
            'sub_categories'    => $sub_categories,
            'mechanics'         => $mechanics,
            'locations'         => $locations
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
            dd($th);
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    /* -------------------------------------------------------------------------- */

    public function setPage()
    {
        if ($this->page == 1) {
            $this->page = 2;
        } elseif ($this->page == 2) {
            $this->clear();
            $this->page = 1;
        }
    }

    public function clear2()
    {  // customed clearing for page 2 - Job Order Page
        $this->resetExcept('page', 'job_order_no', 'reference_no', 'ref_office_id', 'ref_types_id', 'number', 'ref_models_id_2', 'mileage', 'driver_in_charge', 'contact_number');
        $this->resetValidation();

        $this->dispatch('reset-status-select');
        $this->dispatch('reset-category-select');
        $this->dispatch('reset-type-of-repair-select');
        $this->dispatch('reset-sub-category-select');
        $this->dispatch('reset-mechanics-select');
        $this->dispatch('reset-location-select');
        $this->dispatch('reset-issue-or-concern-summernote');
    }

    public function clear3()
    { // custom clearing for statusUpdateModal
        $this->reset(['jo_date_and_time', 'total_repair_time', 'claimed_by', 'remarks']);
        $this->dispatch('reset-date-and-time');
        $this->dispatch('set-status-select-pending');
        // $this->dispatch('hideStatusUpdateModal');
        $this->resetValidation();
    }

    // public function generateJobOrder($referenceNo)
    // {
    // Ensure referenceNo is passed or available in the component
    // return TblJobOrderModel::getNextJobOrderNumber($referenceNo);
    // $this->job_order_no = TblJobOrderModel::getNextJobOrderNumber($referenceNo);
    // }


    public function generateJobOrderNo()
    {
        // Set the supposed-to-be ID
        $this->job_order_no = TblJobOrderModel::max('id') + 1;
    }

    public function readJobOrders($key)
    {
        $this->authorize('read', TblIncomingRequestModel::class);

        try {
            $this->page = 2;

            $incoming_request       = TblIncomingRequestModel::with(['office', 'type', 'model'])->findOrFail($key);
            $this->reference_no     = $incoming_request->reference_no;

            // Dispatch an event with reference_no as a parameter to be used for generating updated job_order_no
            // $this->dispatch('send-reference-no', $incoming_request->reference_no);

            // Generate the next job order number
            // $this->job_order_no     = $this->generateJobOrder($this->reference_no);


            $this->job_order_no     = $incoming_request->id + 1;
            $this->ref_office_id    = $incoming_request->office->name;
            $this->ref_types_id     = $incoming_request->type->name;
            $this->ref_models_id_2  = $incoming_request->model->name;
            $this->number           = $incoming_request->number;
            $this->mileage          = $incoming_request->mileage;
            $this->driver_in_charge = $incoming_request->driver_in_charge;
            $this->contact_number   = $incoming_request->contact_number;

            $job_orders = TblJobOrderModel::with(['category', 'sub_category', 'status'])
                ->where('reference_no', $incoming_request->reference_no)
                ->get();

            $this->dispatch('load-table-job-orders', $job_orders->toJson());
        } catch (\Throwable $th) {
            $this->page = 1;
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function createJobOrder()
    {
        $this->authorize('create', TblIncomingRequestModel::class);

        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $job_order                          = new TblJobOrderModel();
                $job_order->reference_no            = $this->reference_no;
                $job_order->ref_category_id         = $this->ref_category_id;
                $job_order->ref_sub_category_id     = $this->ref_sub_category_id;
                $job_order->ref_location_id         = $this->ref_location_id;
                $job_order->ref_status_id           = 1;
                $job_order->ref_type_of_repair_id   = $this->ref_type_of_repair_id;
                $job_order->ref_mechanics           = $this->ref_mechanics;
                $job_order->issue_or_concern        = $this->issue_or_concern;
                $job_order->save();
            });

            $job_orders = TblJobOrderModel::with(['category', 'sub_category', 'status'])
                ->where('reference_no', $this->reference_no)
                ->get();

            $this->dispatch('load-table-job-orders', $job_orders->toJson());

            $this->clear2();
            $this->dispatch('hideJobOrderModal');
            $this->dispatch('show-success-save-message-toast');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readJobOrder($key)
    {
        $this->authorize('read', TblIncomingRequestModel::class);

        try {
            $this->editMode              = true;

            $job_order                   = TblJobOrderModel::findOrFail($key);
            $this->job_order_no          = $job_order->id;
            $this->dispatch('set-status-select', $job_order->ref_status_id);
            $this->dispatch('set-category-select', $job_order->ref_category_id);
            $this->dispatch('set-type-of-repair-select', $job_order->ref_type_of_repair_id);
            $this->ref_sub_category_id_2 = $job_order->ref_sub_category_id;
            $this->dispatch('set-mechanics-select', $job_order->ref_mechanics);
            $this->dispatch('set-location-select', $job_order->ref_location_id);
            $this->dispatch('set-issue-or-concern-summernote', $job_order->issue_or_concern);

            $this->dispatch('showJobOrderModal');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateJobOrder()
    {
        // $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $job_order = TblJobOrderModel::findOrFail($this->job_order_no);

                $job_order->ref_status_id           = $this->ref_status_id;
                $job_order->ref_category_id         = $this->ref_category_id;
                $job_order->ref_type_of_repair_id   = $this->ref_type_of_repair_id;
                $job_order->ref_sub_category_id     = $this->ref_sub_category_id;
                $job_order->ref_mechanics           = $this->ref_mechanics;
                $job_order->ref_location_id         = $this->ref_location_id;
                $job_order->issue_or_concern        = $this->issue_or_concern;

                if ($this->ref_status_id == 2) {
                    $job_order->date_and_time       = $this->jo_date_and_time;
                    $job_order->total_repair_time   = $this->total_repair_time;
                    $job_order->claimed_by          = $this->claimed_by;
                    $job_order->remarks             = $this->remarks;
                }

                // $job_order->save();
            });

            $job_orders = TblJobOrderModel::with(['category', 'sub_category', 'status'])
                ->where('reference_no', $this->reference_no)
                ->get();

            $this->dispatch('load-table-job-orders', $job_orders->toJson());

            if ($this->ref_status_id == 2) {
                $this->clear3();
                $this->dispatch('hideBothJobOrderModalAndStatusUpdateModal');
            } else {
                $this->clear2();
                $this->dispatch('hideJobOrderModal');
            }

            $this->dispatch('show-success-update-message-toast');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readJobOrdersDetails($key)
    {   // jobOrderDetailsModal - this is for mainly displaying the overall details. I normally use the same modal and just manipulate it through editMode, but I have the ref_status_id where it's done, the statusModal will show and I can't see a better course of action in regards to this predicament.
        // Instead of using plug-ins like summernote and virtual select, I will only use disabled_input fields. :)

        $this->authorize('read', TblIncomingRequestModel::class);

        try {
            $job_order                      = TblJobOrderModel::findOrFail($key);
            $this->job_order_no             = $job_order->id;
            $this->ref_status_id            = $job_order->status->name;
            $this->ref_category_id          = $job_order->category->name;
            $this->ref_type_of_repair_id    = $job_order->type_of_repair->name;
            $this->ref_sub_category_id_2    = $job_order->sub_category->name;
            $this->ref_mechanics            = $job_order->mechanic->name;
            $this->ref_location_id          = $job_order->location->name;
            $this->issue_or_concern         = $job_order->issue_or_concern;
            $this->jo_date_and_time         = Carbon::parse($job_order->date_and_time)->format('M. d, Y g:i A');
            $this->total_repair_time        = $job_order->total_repair_time;
            $this->claimed_by               = $job_order->claimed_by;
            $this->remarks                  = $job_order->remarks;

            $this->dispatch('showJobOrderDetailsModal');
        } catch (\Throwable $th) {
            dd($th);
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    //TODO - Before printing, the system should ask for the signatory first.

    public function printJobOrder($key)
    {
        try {
            $job_order = TblJobOrderModel::findOrFail($key);

            $data = [
                'cdo_full'          => base64_encode(file_get_contents(public_path('assets/images/compressed_cdofull.png'))),
                'rise_logo'         => base64_encode(file_get_contents(public_path('assets/images/risev2.png'))),
                'watermark'         => base64_encode(file_get_contents(public_path('assets/images/compressed_city_depot_logo.png'))),
                'job_order_no'      => $job_order->id,
                'equipment_type'    => $job_order->incoming_request->type->name,
                'department'        => $job_order->incoming_request->office->name,
                'model'             => $job_order->incoming_request->model->name,
                'date_and_time_in'  => Carbon::parse($job_order->incoming_request->date_and_time)->format('M. d, Y g:i A'),
                'date_and_time_out' => Carbon::parse($job_order->date_and_time)->format('M. d, Y g:i A'),
                'plate_no'          => $job_order->incoming_request->number,
                'issues_or_concern' => $job_order->issue_or_concern,
                'mechanic'          => $job_order->mechanic->name,
                'name'              => $job_order->incoming_request->driver_in_charge,
                'contact_number'    => $job_order->incoming_request->contact_number
            ];

            $htmlContent = view('livewire.pdf.job_order_details_pdf', $data)->render();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true); // Helps with complex HTML

            $dompdf = new Dompdf();
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $this->job_order_details_pdf = 'data:application/pdf;base64,' . base64_encode($dompdf->output());

            $this->dispatch('showJobOrderDetailsPDF');
        } catch (\Throwable $th) {
            dd($th);
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
