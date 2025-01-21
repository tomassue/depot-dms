<?php

namespace App\Livewire\Mechanics;

use App\Livewire\Settings\RefSectionsMechanic;
use App\Models\RefMechanicsModel;
use App\Models\RefSectionsMechanicModel;
use App\Models\RefSubSectionsMechanicModel;
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
    public $filter_date_range, $filter_section, $filter_sub_section;

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
        $this->reset();
        $this->dispatch('reset-date-and-time');
    }

    public function render()
    {
        $pageData = $this->loadPageData();

        return view('livewire.mechanics.mechanics', [
            'groupedSections' => $pageData['groupedSections'],
            'mechanics' => $pageData['mechanics'],
            'filter_sections' => $this->loadSections(),
            'filter_sub_sections' => $this->loadSubSections()
        ]);
    }

    //* With pagination
    public function loadPageData()
    {
        $sqids = new Sqids(minLength: 10);

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
                ) as total_jobs"),
                'ref_sections_mechanic_id',
                'ref_sub_sections_mechanic_id'
            )
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->filter_section != NULL, function ($query) {
                $query->where('ref_sections_mechanic_id', $this->filter_section);
            })
            ->when($this->filter_sub_section != NULL, function ($query) {
                $query->where('ref_sub_sections_mechanic_id', $this->filter_sub_section);
            })
            ->when($this->filter_date_range != null, function ($query) {
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

        // Transform and add sqid to each mechanic
        $mechanics->transform(function ($mechanic) use ($sqids) {
            $mechanic->sqid = $sqids->encode([$mechanic->id]);
            return $mechanic;
        });

        // Group by sections and subsections
        $groupedSections = $mechanics->groupBy(function ($mechanic) {
            return $mechanic->section->name ?? 'Unassigned Section';
        })->map(function ($sectionGroup) {
            return $sectionGroup->groupBy(function ($mechanic) {
                return $mechanic->sub_section->name ?? null; // If no subsection, group under null
            });
        });

        // Pass the grouped sections to the view
        return [
            'groupedSections' => $groupedSections,
            'mechanics' => $mechanics
        ];
    }

    public function loadSections()
    {
        // Select filtered sections
        return RefSectionsMechanicModel::all();
    }

    public function loadSubSections()
    {
        // Select filtered subsections
        return RefSubSectionsMechanicModel::when($this->filter_section != NULL, function ($query) {
            $query->where('ref_sections_mechanic_id', $this->filter_section);
        })
            ->get();
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
