<?php

namespace App\Livewire;

use App\Models\RefStatusModel;
use App\Models\TblIncomingRequestModel;
use App\Models\TblJobOrderModel;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Report | DEPOT DMS')]
class Report extends Component
{
    use AuthorizesRequests;

    public $filter_date_range;
    public $filter_status_range;
    public $pdfData;

    /* -------------------------------------------------------------------------- */

    public $ref_types_id;
    public $ref_models_id;
    public $number;
    public $mileage;
    public $driver_in_charge;
    public $contact_number;
    public $job_order_no;
    public $ref_status_id;
    public $ref_category_id;
    public $ref_type_of_repair_id;
    public $ref_sub_category_id_2;
    public $ref_mechanics;
    public $ref_location_id;
    public $issue_or_concern;
    public $date_and_time_out;
    public $total_repair_time;
    public $claimed_by;
    public $remarks;

    public function render()
    {
        return view('livewire.report', $this->loadPageData());
    }

    public function mount()
    {
        $this->authorize('read', TblIncomingRequestModel::class);
    }

    public function updated($property)
    {
        $loadPageData = $this->loadPageData();

        if ($property === 'filter_date_range') {
            $this->dispatch('refresh-table-incoming-requests', $loadPageData['table_requests']);
        }

        if ($property === 'filter_status_range') {
            $this->dispatch('refresh-table-incoming-requests', $loadPageData['table_requests']);
        }
    }

    public function clear()
    {
        $this->reset();
        $this->dispatch('reset-date-and-time');
        $this->dispatch('reset-filter-status-select-select');
    }

    public function loadPageData()
    {
        // filter_status_select
        $filter_status = RefStatusModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        $query = TblJobOrderModel::query();

        // Check if the date range is set and not empty
        if ($this->filter_date_range) {
            if (str_contains($this->filter_date_range, ' to ')) {
                [$startDate, $endDate] = array_map('trim', explode(' to ', $this->filter_date_range));
                $query->whereBetween('created_at', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            } else {
                $query->whereDate('created_at', Carbon::parse($this->filter_date_range));
            }
        }

        if ($this->filter_status_range) {
            $query->where('ref_status_id', $this->filter_status_range);
        }

        $table_requests = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'created_at' => $item->created_at,
                'office' => $item->incoming_request->office->name,
                'category' => $item->category->name,
                'sub_category' => $item->sub_category->name,
                'type' => $item->incoming_request->type->name,
                'issue_or_concern' => $item->issue_or_concern
            ];
        });

        return [
            'filter_status'  => $filter_status,
            'table_requests' => $table_requests
        ];
    }

    public function readJobOrder($key)
    {
        // jobOrderDetailsModal - this is for mainly displaying the overall details. I normally use the same modal and just manipulate it through editMode, but I have the ref_status_id where it's done, the statusModal will show and I can't see a better course of action in regards to this predicament.
        // Instead of using plug-ins like summernote and virtual select, I will only use disabled_input fields. :)

        try {
            $job_order                      = TblJobOrderModel::findOrFail($key);
            $this->ref_types_id             = $job_order->incoming_request->type->name;
            $this->ref_models_id            = $job_order->incoming_request->model->name;
            $this->number                   = $job_order->incoming_request->number;
            $this->mileage                  = $job_order->incoming_request->mileage;
            $this->driver_in_charge         = $job_order->driver_in_charge;
            $this->contact_number           = $job_order->contact_number;
            $this->job_order_no             = $job_order->id;
            $this->ref_status_id            = $job_order->status->name;
            $this->ref_category_id          = $job_order->category->name;
            $this->ref_type_of_repair_id    = $job_order->type_of_repair->name;
            $this->ref_sub_category_id_2    = $job_order->sub_category->name;
            $this->ref_mechanics            = $job_order->mechanic->name;
            $this->ref_location_id          = $job_order->location->name;
            $this->issue_or_concern         = $job_order->issue_or_concern;
            $this->date_and_time_out        = Carbon::parse($job_order->date_and_time_out)->format('M. d, Y g:i A');
            $this->total_repair_time        = $job_order->total_repair_time;
            $this->claimed_by               = $job_order->claimed_by;
            $this->remarks                  = $job_order->remarks;

            $this->dispatch('showJobOrderDetailsModal');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function print()
    {
        try {
            $query = TblJobOrderModel::query();

            // Check if the date range is set and not empty
            if ($this->filter_date_range) {
                if (str_contains($this->filter_date_range, ' to ')) {
                    [$startDate, $endDate] = array_map('trim', explode(' to ', $this->filter_date_range));
                    $query->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ]);
                } else {
                    $query->whereDate('created_at', Carbon::parse($this->filter_date_range));
                }
            }

            if ($this->filter_status_range) {
                $query->where('ref_status_id', $this->filter_status_range);
            }

            // Eager load related models
            $table_requests = $query->with(['incoming_request.office', 'incoming_request.type'])
                ->get()
                ->map(function ($item) {
                    $dateIn = Carbon::parse($item->created_at)->format('F d, Y');
                    $elapsedDays = Carbon::parse($item->created_at)->diffInDays(Carbon::now());

                    return [
                        'department'        => $item->incoming_request->office->name ?? '-',
                        'type'              => $item->incoming_request->type->name ?? '-',
                        'number'            => $item->incoming_request->number ?? '-',
                        'issues_or_concern' => $item->issue_or_concern ?? '-',
                        'status'            => $item->status->name ?? '-',
                        'date_in'           => $dateIn,
                        'date_elapsed'      => round($elapsedDays) . ' days'
                    ];
                });


            $loadImage = fn($path) => base64_encode(File::get(public_path($path)));

            $data = [
                'cdo_full'       => $loadImage('assets/images/compressed_cdofull.png'),
                'rise_logo'      => $loadImage('assets/images/risev2.png'),
                'watermark'      => $loadImage('assets/images/compressed_city_depot_logo.png'),
                'table_requests' => $table_requests
            ];

            $htmlContent = view('livewire.pdf.weekly_depot_repair_bay_vehicle_or_equipment_inventory_report', $data)->render();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            $this->pdfData = 'data:application/pdf;base64,' . base64_encode($dompdf->output());

            $this->dispatch('showPdfModal');

            $user = Auth::user();
            $filter_date_range   = $this->filter_date_range ?? 'All';
            $filter_status_range = $this->filter_status_range ?? 'All';

            activity()
                ->causedBy($user) // The user who printed the job order
                ->withProperties([
                    'date_range' => $filter_date_range,
                    'status' => $filter_status_range
                ])
                ->event('printed report')
                ->log("Job Orders with dates from {$filter_date_range} with status of {$filter_status_range} were generated by a user with ID: {$user->id}");
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
