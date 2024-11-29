<?php

namespace App\Livewire;

use App\Models\RefStatusModel;
use App\Models\TblIncomingRequestModel;
use App\Models\TblJobOrderModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Report extends Component
{
    use AuthorizesRequests;

    public $filter_date_range;
    public $filter_status_range;

    public function render()
    {
        return view('livewire.report', $this->loadPageData());
    }

    public function mount()
    {
        $this->authorize('read', TblIncomingRequestModel::class);
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

        // TODO - Continue working the report.
        // table_requests
        # TblJobOrderModel::all(), it returns a collection of multiple TblJobOrderModel instances, not a single instance.
        # Since we want to return a collection and we can't access its relationships, we have to map it out.
        # There are several ways and mapping them out is one of them.

        $table_requests = TblJobOrderModel::all()
            ->map(function ($item) {
                return [
                    'created_at' => $item->created_at,
                    'office' => $item->incoming_request->office->name
                ];
            });

        return [
            'filter_status'  => $filter_status,
            'table_requests' => $table_requests
        ];
    }
}
