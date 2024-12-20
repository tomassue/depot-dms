<?php

namespace App\Livewire\Mechanics;

use App\Models\RefMechanicsModel;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Sqids\Sqids;

#[Title('Mechanic | DEPOT DMS')]
class Mechanics extends Component
{
    public $search;

    public function mount()
    {
        $this->authorize('read mechanic list');
    }

    public function render()
    {
        return view('livewire.mechanics.mechanics', $this->loadPageData());
    }

    public function loadPageData()
    {
        $sqids = new Sqids(minLength: 10); // For URL obfuscation
        $mechanics = RefMechanicsModel::withTrashed()
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
            ->get()
            ->map(function ($mechanic) use ($sqids) {
                // Obfuscate the ID using Sqids
                $mechanic->sqid = $sqids->encode([$mechanic->id]);
                return $mechanic;
            });

        return [
            'mechanics' => $mechanics
        ];
    }
}
