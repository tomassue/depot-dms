<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $data = [];

        return view('livewire.dashboard', $data);
    }
}
