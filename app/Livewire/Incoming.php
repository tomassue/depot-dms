<?php

namespace App\Livewire;

use App\Models\FileDataModel;
use App\Models\RefCategoryModel;
use App\Models\RefIncomingRequestTypeModel;
use App\Models\RefLocationModel;
use App\Models\RefMechanicsModel;
use App\Models\RefModelModel;
use App\Models\RefOfficesModel;
use App\Models\RefSignatoriesModel;
use App\Models\RefStatusModel;
use App\Models\RefSubCategoryModel;
use App\Models\RefTypeModel;
use App\Models\RefTypeOfRepairModel;
use App\Models\TblIncomingRequestModel;
use App\Models\TblJobOrderModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Activitylog\Models\Activity;
use URL;

#[Title('Incoming | DEPOT DMS')]
class Incoming extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $page = 1; // Change this to 1 after finishing the modal's layout in jobOrderModal

    /* -------------------------------------------------------------------------- */

    public $editMode, $disable_input;
    public $incoming_request_id;
    public $job_orders = [];
    public $reference_no;
    public $job_order_no;
    public $job_order_details_pdf;
    public $job_order_logs = [];
    public $incoming_request_type_filter;

    /* ---------------------------------- Model --------------------------------- */

    public $ref_incoming_request_types_id;
    public $ref_office_id;
    public $ref_types_id;
    public $ref_models_id, $ref_models_id_2;
    public $number;
    public $mileage;

    /* ----------------------------- Job Order Model ---------------------------- */

    public $date_and_time_in;
    public $ref_category_id = [];
    public $ref_sub_category_id = [];
    public $ref_location_id;
    public $person_in_charge;
    public $contact_number;
    public $ref_status_id;
    public $ref_type_of_repair_id;
    public $ref_mechanics = [];
    public $issue_or_concern;
    public $findings;
    public $date_and_time_out;
    public $total_repair_time;
    public $claimed_by;
    public $remarks;
    public $ref_signatories_id;
    public $files = [], $previewFiles = [];

    public function rules()
    {
        $rules = [
            'ref_incoming_request_types_id' => 'required',
            'ref_office_id'                 => 'required',
            'ref_types_id'                  => 'required',
            'ref_models_id'                 => 'required',
            'number'                        => [
                'required',
                Rule::unique('tbl_incoming_requests', 'number')->ignore($this->incoming_request_id, 'id')
            ],
        ];

        if ($this->page == 2) {
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
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'ref_incoming_request_types_id' => 'equipment type',
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

        //* We disabled this because sub-category is not dependent on category.
        // if ($property === 'ref_category_id') {
        //     $this->dispatch('refresh-sub-category-select-options', options: $virtual_select['sub_categories'], selected: $this->ref_sub_category_id_2);
        // }

        if ($property === 'ref_status_id') {
            if ($this->ref_status_id == 2 || $this->ref_status_id == 3) {
                if ($this->ref_status_id == 2) {
                    # We automatically assigned that whoever's the person in charge, will be the one to claim. However, end user can still edit it.
                    $this->claimed_by = $this->person_in_charge;
                }
                $this->dispatch('showStatusUpdateModal');
            }
        }

        # Filter
        if ($property === 'incoming_request_type_filter') {
            $table_incoming_request = $this->loadPageData(); // reloads the method so that we can fetch updated data from incoming_requests.
            $this->dispatch('refresh-table-incoming-requests', $table_incoming_request['incoming_requests']);
        }

        # Calculate automatically the total repair time
        // if ($property === 'date_and_time_out') {
        //     $dateTimeIn  = Carbon::parse($this->date_and_time_in);
        //     $dateTimeOut = Carbon::parse($this->date_and_time_out);

        //     $this->total_repair_time = $dateTimeIn->diffInHours($dateTimeOut);
        // }
    }

    public function clear()
    {
        $this->resetExcept('page');
        $this->resetValidation();

        $this->dispatch('reset-date-and-time');
        $this->dispatch('reset-office-select');
        $this->dispatch('reset-type-select');
        $this->dispatch('reset-model-select');
        $this->dispatch('reset-incoming-request-types-select');
    }

    // This function reads all necessary data from the database.
    public function loadPageData()
    {
        // table_incoming_requests
        $incoming_requests = TblIncomingRequestModel::with(['incoming_request_type', 'office', 'type', 'model'])
            ->when($this->incoming_request_type_filter != NULL, function ($query) {
                return $query->where('ref_incoming_request_types_id', $this->incoming_request_type_filter);
            })
            ->get();

        // incoming-request-types-select
        $incoming_request_types = RefIncomingRequestTypeModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

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
        $sub_categories = RefSubCategoryModel::all()
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

        $jobOrderCounts = TblJobOrderModel::selectRaw('JSON_UNQUOTE(JSON_EXTRACT(ref_mechanics, "$[*]")) as mechanic_id')
            ->where('ref_status_id', '1') // Only count pending job orders
            ->get()
            ->pluck('mechanic_id')
            ->flatMap(function ($item) {
                return json_decode($item); // Convert JSON string to array
            })
            ->countBy()
            ->all();

        // mechanics-select
        $mechanics = RefMechanicsModel::all()
            ->map(function ($item) use ($jobOrderCounts) {
                $count = $jobOrderCounts[$item->id] ?? 0; // Now it will match properly
                return [
                    'label' => $item->name,
                    'value' => $item->id,
                    'description' => "Has {$count} pending job orders"
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

        // signatories-select
        $signatories = RefSignatoriesModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id,
                    'description' => $item->designation
                ];
            });

        return [
            'incoming_requests'             => $incoming_requests,
            'incoming_request_types'        => $incoming_request_types,
            'offices'                       => $offices,
            'types'                         => $types,
            'models'                        => $models,
            'statuses'                      => $statuses,
            'categories'                    => $categories,
            'type_of_repairs'               => $type_of_repairs,
            'sub_categories'                => $sub_categories,
            'mechanics'                     => $mechanics,
            'locations'                     => $locations,
            'signatories'                   => $signatories
        ];
    }

    public function createIncomingRequest()
    {
        $this->authorize('create', TblIncomingRequestModel::class);

        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $incoming_request                                = new TblIncomingRequestModel();
                $incoming_request->reference_no                  = $this->reference_no;
                $incoming_request->ref_office_id                 = $this->ref_office_id;
                $incoming_request->ref_types_id                  = $this->ref_types_id;
                $incoming_request->ref_models_id                 = $this->ref_models_id;
                $incoming_request->number                        = strtoupper($this->number);
                $incoming_request->ref_incoming_request_types_id = $this->ref_incoming_request_types_id;

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
            $this->dispatch('set-type-select', $incoming_request->ref_types_id);
            $this->dispatch('set-incoming-request-types-select', $incoming_request->ref_incoming_request_types_id);

            /**
             * This is for displaying ref_models_id without causing it to disappear due to setOptions by assigning another property that will hold on this value.
             * Refer to updated($property) -> ($property === 'ref_types_id') if block.
             * */
            $this->ref_models_id_2      = $incoming_request->ref_models_id;

            $this->number               = $incoming_request->number;
            $this->mileage              = $incoming_request->mileage;

            $this->dispatch('showIncomingModal');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateIncomingRequest()
    {
        $incoming_request = TblIncomingRequestModel::findOrFail($this->incoming_request_id);

        $this->authorize('update', $incoming_request);

        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () use ($incoming_request) {
                $incoming_request->ref_office_id    = $this->ref_office_id;
                $incoming_request->ref_types_id     = $this->ref_types_id;
                $incoming_request->ref_models_id    = $this->ref_models_id;
                $incoming_request->number           = strtoupper($this->number);

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

    public function setPage()
    {
        if ($this->page == 1) {
            $this->page = 2;
        } elseif ($this->page == 2) {
            $this->clear();
            $this->page = 1;

            $table_incoming_request = $this->loadPageData(); // reloads the method so that we can fetch updated data from incoming_requests.
            $this->dispatch('refresh-table-incoming-requests', $table_incoming_request['incoming_requests']);
        }
    }

    public function clear2()
    {  // customed clearing for page 2 - Job Order Page
        $this->resetExcept('ref_incoming_request_types_id', 'page', 'job_order_no', 'reference_no', 'ref_office_id', 'ref_types_id', 'number', 'ref_models_id_2');
        $this->resetValidation();

        $this->dispatch('reset-status-select');
        $this->dispatch('reset-category-select');
        $this->dispatch('reset-type-of-repair-select');
        $this->dispatch('reset-sub-category-select');
        $this->dispatch('reset-mechanics-select');
        $this->dispatch('reset-location-select');
        $this->dispatch('reset-date-and-time-in');
        $this->dispatch('reset-issue-or-concern-summernote');
        $this->dispatch('reset-findings-summernote');
        $this->dispatch('reset-my-pond-files');

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


            $this->ref_incoming_request_types_id = $incoming_request->ref_incoming_request_types_id;
            $this->job_order_no                  = $incoming_request->id + 1;
            $this->ref_office_id                 = $incoming_request->office->name;
            $this->ref_types_id                  = $incoming_request->type->name;
            $this->ref_models_id_2               = $incoming_request->model->name;
            $this->number                        = $incoming_request->number;

            $job_orders = TblJobOrderModel::with(['category', 'status'])
                ->where('reference_no', $incoming_request->reference_no)
                ->orderBy('created_at', 'desc')
                ->get();

            $job_orders->each(function ($job_order) {
                $job_order->append('category_names');
            });

            $job_orders->each(function ($job_order) {
                $job_order->append('sub_category_names');
            });

            $this->dispatch('load-table-job-orders', $job_orders->toJson());
        } catch (\Throwable $th) {
            // dd($th);
            $this->page = 1;
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function jobOrderModal()
    {
        $check_pending_job_order = TblJobOrderModel::where('reference_no', $this->reference_no)
            ->where('ref_status_id', '1')
            ->exists();

        if ($check_pending_job_order) {
            $this->dispatch('show-can-not-add-job-order-alert');
        } else {
            $virtual_select = $this->loadPageData();

            // dd($virtual_select['mechanics']);

            $this->dispatch('refresh-mechanics-select-options', options: $virtual_select['mechanics']);
            $this->dispatch('showJobOrderModal');
        }
    }

    public function createJobOrder()
    {
        $this->authorize('create', TblIncomingRequestModel::class);

        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                foreach ($this->files ?? [] as $file) {
                    $fileData = FileDataModel::create([
                        'name'    => $file->getClientOriginalName(),   // Original name of the file
                        'size'    => $file->getSize(),                 // Size of the file
                        'type'    => $file->getMimeType(),             // MIME type of the file
                        'data'    => file_get_contents($file->path()), // File contents (stored as BLOB in DB)
                        'user_id' => Auth::user()->id              // The ID of the authenticated user
                    ]);
                    $fileDataIds[] = $fileData->id;
                }

                $job_order                          = new TblJobOrderModel();
                $job_order->reference_no            = $this->reference_no;
                $job_order->date_and_time_in        = $this->date_and_time_in;
                $job_order->ref_category_id         = json_encode($this->ref_category_id);
                $job_order->ref_sub_category_id     = json_encode($this->ref_sub_category_id);
                $job_order->mileage                 = $this->mileage;
                $job_order->ref_location_id         = $this->ref_location_id;
                $job_order->person_in_charge        = $this->person_in_charge;
                $job_order->contact_number          = $this->contact_number;
                $job_order->ref_status_id           = 1;
                $job_order->ref_type_of_repair_id   = $this->ref_type_of_repair_id;
                $job_order->ref_mechanics           = json_encode($this->ref_mechanics);
                $job_order->issue_or_concern        = $this->issue_or_concern;
                $job_order->files                   = json_encode($fileDataIds ?? []);
                $job_order->findings                = $this->findings;

                $job_order->save();
            });

            $job_orders = TblJobOrderModel::with(['category', 'status'])
                ->where('reference_no', $this->reference_no)
                ->get();

            $job_orders->each(function ($job_order) {
                $job_order->append('category_names');
            });

            $job_orders->each(function ($job_order) {
                $job_order->append('sub_category_names');
            });

            $this->dispatch('load-table-job-orders', $job_orders->toJson());

            $this->clear2();
            $this->dispatch('hideJobOrderModal');
            $this->dispatch('show-success-save-message-toast');
        } catch (\Throwable $th) {
            dd($th->getMessage());
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
            $this->person_in_charge      = $job_order->person_in_charge;
            $this->mileage               = $job_order->mileage;
            $this->contact_number        = $job_order->contact_number;

            if ($job_order->files) {
                foreach (json_decode($job_order->files) as $item) {
                    $file = FileDataModel::find($item);

                    if ($file) {
                        $this->previewFiles[] = $file;
                    }
                }
            }

            $this->dispatch('set-category-select', json_decode($job_order->ref_category_id));
            $this->dispatch('set-sub-category-select', json_decode($job_order->ref_sub_category_id));
            $this->dispatch('set-type-of-repair-select', $job_order->ref_type_of_repair_id);
            $this->dispatch('set-status-select', $job_order->ref_status_id);
            $this->dispatch('set-mechanics-select', json_decode($job_order->ref_mechanics));
            $this->dispatch('set-location-select', $job_order->ref_location_id);
            $this->dispatch('set-date-and-time-in', $job_order->date_and_time_in);
            $this->dispatch('set-issue-or-concern-summernote', $job_order->issue_or_concern);

            $this->dispatch('showJobOrderModal');
        } catch (\Throwable $th) {
            // dd($th);
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function viewFile($fileId)
    {
        // Generate the signed URL with a 10-minute expiration
        $signedURL = URL::temporarySignedRoute(
            'file.view',
            now()->addMinutes(10),
            ['id' => $fileId]
        );

        // Dispatch an event to the browser to open the URL in a new tab
        $this->dispatch('open-file', url: $signedURL);
    }

    public function removeFile($fileId)
    {
        try {
            DB::transaction(function () use ($fileId) {
                // Step 1: Find the job order
                $job_order = TblJobOrderModel::findOrFail($this->job_order_no);

                // Step 2: Decode the existing file IDs from the files JSON column
                $existingFileIds = json_decode($job_order->files, true) ?? [];

                // Step 3: Check if the fileId exists in the array
                if (in_array($fileId, $existingFileIds)) {
                    // Step 4: Remove the file record from the FileDataModel
                    FileDataModel::where('id', $fileId)->delete();

                    // Step 5: Remove the file ID from the existing file IDs array
                    $updatedFileIds = array_filter($existingFileIds, fn($id) => $id != $fileId);

                    // Step 6: Save the updated file IDs back into the files column
                    $job_order->files = json_encode(array_values($updatedFileIds)); // Re-index array
                    $job_order->save();
                }
            });

            // Step 7: Remove file from previewFiles
            $this->previewFiles = array_filter($this->previewFiles, function ($file) use ($fileId) {
                return $file->id != $fileId; // Remove the file with matching ID
            });
            $this->previewFiles = array_values($this->previewFiles); // Reset array keys to maintain clean indices
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateJobOrder()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $job_order = TblJobOrderModel::findOrFail($this->job_order_no);

                // Step 1: Initialize $fileDataIds as an empty array to avoid "undefined variable" error
                $fileDataIds = [];

                foreach ($this->files ?? [] as $file) {
                    $fileData = FileDataModel::create([
                        'name'    => $file->getClientOriginalName(),   // Original name of the file
                        'size'    => $file->getSize(),                 // Size of the file
                        'type'    => $file->getMimeType(),             // MIME type of the file
                        'data'    => file_get_contents($file->path()), // File contents (stored as BLOB in DB)
                        'user_id' => Auth::user()->id                  // The ID of the authenticated user
                    ]);
                    $fileDataIds[] = $fileData->id;
                }

                // Step 2: Check if $fileDataIds has any new file IDs
                if (!empty($fileDataIds)) {
                    // Get the existing file IDs from the JSON column, decode it, and merge it with the new file IDs
                    $existingFileIds    = json_decode($job_order->files, true) ?? []; // Convert JSON to array
                    $updatedFileIds     = array_unique(array_merge($existingFileIds, $fileDataIds)); // Merge and remove duplicates
                    $job_order->files   = json_encode($updatedFileIds); // Save the updated file IDs
                }

                $job_order->ref_status_id           = $this->ref_status_id;
                $job_order->person_in_charge        = $this->person_in_charge;
                $job_order->contact_number          = $this->contact_number;
                $job_order->ref_category_id         = json_encode($this->ref_category_id);
                $job_order->ref_type_of_repair_id   = $this->ref_type_of_repair_id;
                $job_order->ref_sub_category_id     = json_encode($this->ref_sub_category_id);
                $job_order->ref_mechanics           = json_encode($this->ref_mechanics);
                $job_order->ref_location_id         = $this->ref_location_id;
                $job_order->date_and_time_in        = $this->date_and_time_in;
                $job_order->issue_or_concern        = $this->issue_or_concern;
                $job_order->findings                = $this->findings;
                $job_order->mileage                 = $this->mileage;

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

            $job_orders = TblJobOrderModel::with(['category', 'status'])
                ->where('reference_no', $this->reference_no)
                ->get();

            $job_orders->each(function ($job_order) {
                $job_order->append('category_names');
            });

            $job_orders->each(function ($job_order) {
                $job_order->append('sub_category_names');
            });

            $this->dispatch('load-table-job-orders', $job_orders->toJson());

            if ($this->ref_status_id == 2 || $this->ref_status_id == 3) {
                $this->clear2();
                $this->clear3();
                $this->dispatch('hideBothJobOrderModalAndStatusUpdateModal');
            } else {
                $this->clear2();
                $this->dispatch('hideJobOrderModal');
            }

            $this->dispatch('show-success-update-message-toast');
        } catch (\Throwable $th) {
            dd($th);
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
            $this->person_in_charge         = $job_order->person_in_charge;
            $this->contact_number           = $job_order->contact_number;
            $this->ref_category_id          = $job_order->category_names;
            $this->ref_type_of_repair_id    = $job_order->type_of_repair->name;
            $this->ref_sub_category_id      = $job_order->sub_category_names;
            $this->ref_mechanics            = $job_order->mechanics()->pluck('name')->implode(', ');
            $this->ref_location_id          = $job_order->location->name;
            $this->mileage                  = $job_order->mileage;
            $this->issue_or_concern         = $job_order->issue_or_concern;
            $this->date_and_time_out        = Carbon::parse($job_order->date_and_time_out)->format('M. d, Y g:i A');
            $this->total_repair_time        = $job_order->total_repair_time;
            $this->claimed_by               = $job_order->claimed_by;
            $this->remarks                  = $job_order->remarks;
            $this->ref_signatories_id       = $job_order->ref_signatories_id;

            if ($job_order->files) {
                foreach (json_decode($job_order->files) as $item) {
                    $file = FileDataModel::find($item);

                    if ($file) {
                        $this->previewFiles[] = $file;
                    }
                }
            }

            $this->dispatch('showJobOrderDetailsModal');
        } catch (\Throwable $th) {
            // dd($th);
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function printReleaseForm($id)
    {
        $division_chief = RefSignatoriesModel::where('is_division_chief', '1')->first();

        if ($division_chief) {
            $signedURL = URL::temporarySignedRoute(
                'generate-release-form',
                now()->addMinutes(5),
                ['id' => $id]
            );

            $this->dispatch('generate-pdf', url: $signedURL);
        } else {
            $this->dispatch('show-assign-division-chief-toast');
        }
    }

    public function readLogs($key)
    {
        try {
            $this->job_order_logs = Activity::where('subject_type', TblJobOrderModel::class)
                ->where('subject_id', $key)
                ->whereNot('event', 'printed job order')
                // ->with([
                //     'causer',
                //     'subject.category',
                //     'subject.type_of_repair',
                //     'subject.location'
                // ]) // Load the user that triggered the activity
                ->orderBy('created_at', 'desc')
                ->get();

            $this->dispatch('showJobOrderLogsModal');
        } catch (\Throwable $th) {
            // dd($th);
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function assignSignatory($key)
    {
        $this->job_order_no = $key;

        $this->dispatch('showAssignSignatoryModal');
    }

    public function printJobOrder($key)
    {
        $this->validate(['ref_signatories_id' => 'required'], [], ['ref_signatories_id' => 'signatory']);

        try {
            $job_order = TblJobOrderModel::findOrFail($key);
            $signatory = RefSignatoriesModel::findOrFail($this->ref_signatories_id);

            $data = [
                'cdo_full'              => base64_encode(file_get_contents(public_path('assets/images/cdo-seal.png'))),
                'rise_logo'             => base64_encode(file_get_contents(public_path('assets/images/risev2.png'))),
                'watermark'             => base64_encode(file_get_contents(public_path('assets/images/compressed_city_depot_logo.png'))),
                'job_order_no'          => $job_order->id,
                'equipment_type'        => $job_order->incoming_request->type->name,
                'department'            => $job_order->incoming_request->office->name,
                'model'                 => $job_order->incoming_request->model->name,
                'date_and_time_in'      => Carbon::parse($job_order->date_and_time_in)->format('M. d, Y g:i A'),
                'date_and_time_out'     => $job_order->date_and_time_out ? Carbon::parse($job_order->date_and_time_out)->format('M. d, Y g:i A') : '-',
                'plate_no'              => $job_order->incoming_request->number,
                'issues_or_concern'     => $job_order->issue_or_concern,
                'mechanic'              => $job_order->mechanics()->pluck('name')->map(function ($name) {
                    return $name . ' ________ ';
                })->implode(''),
                'name'                  => $job_order->person_in_charge,
                'contact_number'        => $job_order->contact_number,
                'signatory_name'        => $signatory->name,
                'signatory_designation' => $signatory->designation
            ];

            $htmlContent = view('livewire.pdf.job_order_details_pdf', $data)->render();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true); // Helps with complex HTML

            $customPaper = array(0, 0, 594, 923); // Still A4 but adjustments in DPI will make the content fit in the paper

            $dompdf = new Dompdf();
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper($customPaper, 'portrait');
            $dompdf->render();

            $this->job_order_details_pdf = 'data:application/pdf;base64,' . base64_encode($dompdf->output());

            $this->dispatch('showJobOrderDetailsPDF');

            activity()
                ->causedBy(Auth::user()) // The user who printed the job order
                ->performedOn($job_order) // The job order being printed
                ->withProperties([
                    'job_order_id' => $job_order->id,
                    'signatory_id' => $this->ref_signatories_id
                ])
                ->event('printed job order')
                ->log("Job Order #{$job_order->id} printed with Signatory (ID: {$this->ref_signatories_id})");
        } catch (\Throwable $th) {
            dd($th);
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
