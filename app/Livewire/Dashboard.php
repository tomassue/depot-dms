<?php

namespace App\Livewire;

use App\Models\TblJobOrderModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard | DEPOT DMS')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard', $this->loadPageData());
    }

    public function loadPageData()
    {
        # Total Job Orders
        $total = TblJobOrderModel::count();

        # Pending Job Orders
        $pending = TblJobOrderModel::where('ref_status_id', 1)->count();

        # Accomplished Job Orders
        $done = TblJobOrderModel::where('ref_status_id', 2)->count();

        return [
            'total'   => $total,
            'pending' => $pending,
            'done'    => $done
        ];
    }
}
