<?php

namespace App\Livewire;

use App\Models\TblJobOrderModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard | DEPOT DMS')]
class Dashboard extends Component
{
    public $editMode;

    /* -------------------------------------------------------------------------- */

    public $job_order_no;
    public $driver_in_charge;
    public $contact_number;
    public $ref_sub_category_id_2;
    public $ref_category_id;
    public $ref_type_of_repair_id;
    public $ref_status_id;
    public $ref_mechanics;
    public $ref_location_id;
    public $date_and_time_in;
    public $issue_or_concern;

    public function render()
    {
        return view('livewire.dashboard', $this->loadPageData());
    }

    public function clear2()
    {
        $this->resetExcept('page', 'job_order_no', 'reference_no', 'ref_office_id', 'ref_types_id', 'number', 'ref_models_id_2', 'mileage');
        $this->resetValidation();

        //TODO - Complete the new updates first.

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

    public function loadPageData()
    {
        # Total Job Orders
        $total = TblJobOrderModel::count();

        # Pending Job Orders
        $pending = TblJobOrderModel::where('ref_status_id', 1)->count();

        # Accomplished Job Orders
        $done = TblJobOrderModel::where('ref_status_id', 2)->count();

        # Table Pending Job Orders
        $table_pending_job_orders = TblJobOrderModel::with(['category', 'sub_category', 'status'])->where('ref_status_id', 1)->get();

        return [
            'total'                    => $total,
            'pending'                  => $pending,
            'done'                     => $done,
            'table_pending_job_orders' => $table_pending_job_orders
        ];
    }

    public function readJobOrder($key)
    {
        $this->authorize('read', TblIncomingRequestModel::class);

        try {
            $this->editMode              = true;

            $job_order                   = TblJobOrderModel::findOrFail($key);

            $this->job_order_no          = $job_order->id;
            $this->driver_in_charge      = $job_order->driver_in_charge;
            $this->contact_number        = $job_order->contact_number;
            $this->ref_sub_category_id_2 = $job_order->ref_sub_category_id;

            $this->dispatch('set-category-select', $job_order->ref_category_id);
            $this->dispatch('set-type-of-repair-select', $job_order->ref_type_of_repair_id);
            $this->dispatch('set-status-select', $job_order->ref_status_id);
            $this->dispatch('set-mechanics-select', $job_order->ref_mechanics);
            $this->dispatch('set-location-select', $job_order->ref_location_id);
            $this->dispatch('set-date-and-time-in', $job_order->date_and_time_in);
            $this->dispatch('set-issue-or-concern-summernote', $job_order->issue_or_concern);

            $this->dispatch('showJobOrderModal');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
