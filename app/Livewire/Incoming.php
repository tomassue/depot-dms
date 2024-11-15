<?php

namespace App\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Incoming extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $id_incoming;

    /* ---------------------------------- Model --------------------------------- */
    public $referenc_no;
    public $office;
    public $date_and_time;
    public $type;
    public $number;
    public $driver_in_charge;
    public $model;
    public $mileage;
    public $contact_number;

    public function render()
    {
        return view('livewire.incoming');
    }
}
