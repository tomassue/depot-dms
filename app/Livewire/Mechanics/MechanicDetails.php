<?php

namespace App\Livewire\Mechanics;

use App\Models\RefMechanicsModel;
use App\Models\TblJobOrderModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Sqids\Sqids;
use URL;

#[Title('Mechanic Details | DEPOT DMS')]
class MechanicDetails extends Component
{
    public $filter_date_range;
    public $mechanic_id; //* This is the obfuscated ID from the URL

    public function mount($id)
    {
        $sqids = new Sqids(minLength: 10);
        $decodedValue = $sqids->decode($id);

        // Assuming the ID is the first element in the returned array
        $this->mechanic_id = $decodedValue[0]; // Extract the first element of the array
    }

    public function updated($property)
    {
        $pageData = $this->loadPageData();

        if ($property === 'filter_date_range') {
            $this->dispatch('refresh-table_mechanic_job_orders', $pageData['mechanic_jobs']);
        }
    }

    public function clear()
    {
        $this->dispatch('reset-date-and-time');
    }

    public function render()
    {
        return view('livewire.mechanics.mechanic-details', $this->loadPageData());
    }

    public function loadPageData()
    {
        $mechanic = RefMechanicsModel::with(['section', 'sub_section'])
            ->select(
                'id',
                'name',
                DB::raw("IF(deleted_at IS NULL, 'Active', 'Inactive') as status"),
                DB::raw("(SELECT COUNT(*)
                    FROM tbl_job_order
                    WHERE ref_status_id = 1
                        AND JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                ) as pending_jobs"),
                DB::raw("(SELECT COUNT(*)
                    FROM tbl_job_order
                    WHERE ref_status_id = 2
                        AND JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                ) as completed_jobs"),
                DB::raw("(SELECT COUNT(*)
                    FROM tbl_job_order
                        WHERE JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                ) as total_jobs"),
                'ref_sections_mechanic_id',
                'ref_sub_sections_mechanic_id'
            )
            ->withTrashed()
            ->findOrFail($this->mechanic_id);

        // Query to get job orders where this mechanic is listed in the 'ref_mechanics' JSON array
        // Cast mechanic_id to string to avoid the JSON_QUOTE error
        $mechanic_jobs = TblJobOrderModel::with(['category', 'status', 'type_of_repair'])
            ->whereRaw("JSON_CONTAINS(ref_mechanics, JSON_QUOTE(?))", [(string) $this->mechanic_id])
            ->when($this->filter_date_range != NULL, function ($query) {
                if (str_contains($this->filter_date_range, ' to ')) {
                    [$startDate, $endDate] = array_map('trim', explode(' to ', $this->filter_date_range));
                    $query->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ]);
                } else {
                    $query->whereDate('created_at', Carbon::parse($this->filter_date_range));
                }
            })
            ->get();

        $mechanic_jobs->each(function ($mechanic_job) {
            $mechanic_job->append('sub_category_names');
            $mechanic_job->append('category_names');
        });

        return [
            'mechanic' => $mechanic,
            'mechanic_jobs' => $mechanic_jobs
        ];
    }

    public function printJobOrders()
    {
        $signedURL = URL::temporarySignedRoute(
            'generate-job-orders-pdf',
            now()->addMinutes(5),
            [
                'id' => $this->mechanic_id,
                'date' => $this->filter_date_range
            ]
        );

        $this->dispatch('generate-pdf', url: $signedURL);
    }
}
