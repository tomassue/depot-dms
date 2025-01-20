<?php

namespace App\Livewire\Mechanics;

use App\Models\RefMechanicsModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Sqids\Sqids;
use URL;

#[Title('Mechanic | DEPOT DMS')]
class Mechanics extends Component
{
    use WithPagination;

    public $search;
    public $filter_date_range;

    public function mount()
    {
        $this->authorize('read mechanic list');
    }

    public function updated($property)
    {
        $pageData = $this->loadPageData();

        if ($property === 'filter_date_range') {
            $this->dispatch('refresh-table_mechanic_job_orders', $pageData['mechanics']);
        }
    }

    public function clear()
    {
        $this->dispatch('reset-date-and-time');
    }

    public function render()
    {
        return view('livewire.mechanics.mechanics', $this->loadPageData());
    }

    //* With pagination
    public function loadPageData()
    {
        $sqids = new Sqids(minLength: 10); // For URL obfuscation
        $mechanics = RefMechanicsModel::with(['section', 'sub_section'])
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
                ) as total_jobs")
            )
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
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
            ->paginate(10);

        // Process items within the paginator
        $mechanics->getCollection()->transform(function ($mechanic) use ($sqids) {
            $mechanic->sqid = $sqids->encode([$mechanic->id]);
            return $mechanic;
        });

        return [
            'mechanics' => $mechanics
        ];
    }


    public function print()
    {
        // Generate signed URL
        $signedURL = URL::temporarySignedRoute(
            'generate-mechanics-list-pdf',
            now()->addMinutes(5),
            ['date' => $this->filter_date_range]
        );

        // Dispatch the event
        $this->dispatch('generate-pdf', url: $signedURL);
    }
}
