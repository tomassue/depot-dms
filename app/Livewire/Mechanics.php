<?php

namespace App\Livewire;

use App\Models\RefMechanicsModel;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Mechanic | DEPOT DMS')]
class Mechanics extends Component
{
    public $search;

    public function render()
    {
        return view('livewire.mechanics', $this->loadPageData());
    }

    public function loadPageData()
    {
        $mechanics = RefMechanicsModel::withTrashed()
            ->select(
                'id',
                'name',
                DB::raw("IF(deleted_at IS NULL, 'Active', 'Inactive') as status")
            )
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->get();

        return [
            'mechanics' => $mechanics
        ];
    }
}
