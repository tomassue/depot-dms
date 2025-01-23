<?php

namespace App\Livewire;

use App\Models\FileDataModel;
use App\Models\RefCategoryModel;
use App\Models\RefLocationModel;
use App\Models\RefMechanicsModel;
use App\Models\RefStatusModel;
use App\Models\RefSubCategoryModel;
use App\Models\RefTypeOfRepairModel;
use App\Models\TblIncomingRequestModel;
use App\Models\TblJobOrderModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use URL;

#[Title('Dashboard | DEPOT DMS')]
class Dashboard extends Component
{
    use WithFileUploads;

    public $editMode;
    public $ref_incoming_request_types_id;

    /* -------------------------------------------------------------------------- */

    public $ref_types_id;
    public $ref_models_id_2;
    public $number;

    public $job_order_no;
    public $person_in_charge;
    public $contact_number;
    public $ref_sub_category_id = [];
    public $ref_category_id = [];
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
    public $ref_signatories_id;
    public $files = [], $previewFiles = [];

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
        $table_pending_job_orders = TblJobOrderModel::with(['category', 'status', 'incoming_request.type'])->where('ref_status_id', 1)->get();
        $table_pending_job_orders->each(function ($table_pending_job_orders) {
            $table_pending_job_orders->append('sub_category_names');
            $table_pending_job_orders->append('category_names');
        });

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
        $sub_categories = RefSubCategoryModel::all()
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
            $this->person_in_charge      = $job_order->person_in_charge;
            $this->mileage               = $job_order->mileage;

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
                    $existingFileIds                = json_decode($job_order->files, true) ?? []; // Convert JSON to array
                    $updatedFileIds                 = array_unique(array_merge($existingFileIds, $fileDataIds)); // Merge and remove duplicates
                    $job_order->files               = json_encode($updatedFileIds); // Save the updated file IDs
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
            dd($th);
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
