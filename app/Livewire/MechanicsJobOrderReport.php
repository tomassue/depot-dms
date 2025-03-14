<?php

namespace App\Livewire;

use App\Models\RefMechanicsModel;
use App\Models\RefSectionsMechanicModel;
use App\Models\RefSubSectionsMechanicModel;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Report | DEPOT DMS')]
class MechanicsJobOrderReport extends Component
{
    public $filter_date_range_mechanics_job_orders,
        $filter_section_mechanics_job_orders,
        $filter_sub_section_mechanics_job_orders,
        $search;
    public $pdfData;

    public function clear()
    {
        $this->reset();
        $this->dispatch('reset-filter-date-range-mechanics-job-orders');
    }

    public function updated($property)
    {
        if ($property === 'search') {
            $this->dispatch('refresh-table-mechanic-job-orders', $this->loadMechanics());
        }

        if ($property === 'filter_date_range_mechanics_job_orders') {
            $this->dispatch('refresh-table-mechanic-job-orders', $this->loadMechanics());
        }

        if ($property === 'filter_section_mechanics_job_orders') {
            $this->dispatch('refresh-table-mechanic-job-orders', $this->loadMechanics());
        }

        if ($property === 'filter_sub_section_mechanics_job_orders') {
            $this->dispatch('refresh-table-mechanic-job-orders', $this->loadMechanics());
        }
    }

    public function render()
    {
        return view(
            'livewire.mechanics-job-order-report',
            [
                'mechanics' => $this->loadMechanics(),
                'filter_sections' => $this->loadSectionsFilter(),
                'filter_sub_sections' => $this->loadSubSectionsFilter()
            ]
        );
    }

    public function loadSectionsFilter()
    {
        return RefSectionsMechanicModel::all();
    }

    public function loadSubSectionsFilter()
    {
        return RefSubSectionsMechanicModel::all();
    }

    public function loadMechanics()
    {
        return RefMechanicsModel::with(['section', 'sub_section'])
            ->select(
                'id',
                'name',
                DB::raw("IF(deleted_at IS NULL, 'Active', 'Inactive') as status"),
                DB::raw("(SELECT COUNT(*)
                    FROM tbl_job_order
                    WHERE ref_status_id = 1
                        AND JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                        " . $this->getDateRangeFilter() . "
                ) as pending_jobs"),
                // Total pending jobs without date filter
                DB::raw("(SELECT COUNT(*)
                    FROM tbl_job_order
                    WHERE ref_status_id = 1
                        AND JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                ) as total_pending_jobs"),
                DB::raw("(SELECT COUNT(*)
                    FROM tbl_job_order
                    WHERE ref_status_id = 2
                        AND JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                        " . $this->getDateRangeFilter() . "
                ) as completed_jobs"),
                DB::raw("(SELECT COUNT(*)
                    FROM tbl_job_order
                    WHERE JSON_CONTAINS(tbl_job_order.ref_mechanics, JSON_QUOTE(CAST(ref_mechanics.id AS CHAR)))
                        " . $this->getDateRangeFilter() . "
                ) as total_jobs"),
                'ref_sections_mechanic_id',
                'ref_sub_sections_mechanic_id'
            )
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('section', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('sub_section', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filter_section_mechanics_job_orders, function ($query) {
                $query->where('ref_sections_mechanic_id', $this->filter_section_mechanics_job_orders);
            })
            ->when($this->filter_sub_section_mechanics_job_orders, function ($query) {
                $query->where('ref_sub_sections_mechanic_id', $this->filter_sub_section_mechanics_job_orders);
            })
            ->get();
    }

    /**
     * Generates a SQL string for filtering by date range.
     * Returns an empty string if $this->filter_date_range_mechanics_job_orders is empty.
     * Supports date ranges in the format 'YYYY-MM-DD to YYYY-MM-DD' or
     * single dates in the format 'YYYY-MM-DD'.
     * @return string
     */
    private function getDateRangeFilter()
    {
        if ($this->filter_date_range_mechanics_job_orders) {
            if (str_contains($this->filter_date_range_mechanics_job_orders, ' to ')) {
                [$startDate, $endDate] = array_map('trim', explode(' to ', $this->filter_date_range_mechanics_job_orders));
                $startDate = Carbon::parse($startDate)->startOfDay()->toDateTimeString();
                $endDate = Carbon::parse($endDate)->endOfDay()->toDateTimeString();
                return "AND date_and_time_in BETWEEN '$startDate' AND '$endDate'";
            } else {
                $date = Carbon::parse($this->filter_date_range_mechanics_job_orders)->toDateString();
                return "AND DATE(date_and_time_in) = '$date'";
            }
        }
        return "";
    }

    public function printMechanicsJobOrders()
    {
        try {
            $mechanics = $this->loadMechanics();

            // Group by sections and subsections
            $groupedSections = $mechanics->groupBy(function ($mechanic) {
                return $mechanic->section->name ?? 'Unassigned Section';
            })->map(function ($sectionGroup) {
                return $sectionGroup->groupBy(function ($mechanic) {
                    return $mechanic->sub_section->name ?? null; // If no subsection, group under null
                });
            });

            $loadImage = fn($path) => base64_encode(File::get(public_path($path)));

            if ($this->filter_date_range_mechanics_job_orders) {
                $dateRange = explode(' to ', $this->filter_date_range_mechanics_job_orders);

                if (count($dateRange) === 2) {
                    // If both start and end dates exist
                    $formattedDateRange = Carbon::parse($dateRange[0])->format('M d, Y') . ' to ' . Carbon::parse($dateRange[1])->format('M d, Y');
                } else {
                    // If only one date exists
                    $formattedDateRange = Carbon::parse($dateRange[0])->format('M d, Y');
                }
            } else {
                $formattedDateRange = '-';
            }

            $data = [
                'mechanics'       => $mechanics,
                'groupedSections' => $groupedSections,
                'cdo_full'        => $loadImage('assets/images/cdo-seal.png'),
                'rise_logo'       => $loadImage('assets/images/risev2.png'),
                'watermark'       => $loadImage('assets/images/compressed_city_depot_logo.png'),
                'date'            => $formattedDateRange ?? '-'
            ];

            $htmlContent = view('livewire.pdf.mechanics_list_pdf', $data)->render();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($htmlContent);
            $dompdf->render();

            $this->pdfData = 'data:application/pdf;base64,' . base64_encode($dompdf->output());

            $this->dispatch('show-pdf');
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('error');
        }
    }
}
