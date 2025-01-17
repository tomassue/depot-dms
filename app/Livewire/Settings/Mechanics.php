<?php

namespace App\Livewire\Settings;

use App\Models\RefMechanicsModel;
use App\Models\RefSectionsMechanicModel;
use App\Models\RefSubSectionsMechanicModel;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use URL;

#[Title('Mechanics | DEPOT DMS')]
class Mechanics extends Component
{
    use AuthorizesRequests;

    public $filter_date_range;

    public $editMode, $disable_input;
    public $id_mechanic;

    /* ---------------------------------- Model --------------------------------- */
    public $ref_sections_mechanic_id, $ref_sub_sections_mechanic_id, $mechanic;

    public function mount()
    {
        $this->authorize('can read mechanics');
    }

    public function updated($property)
    {
        if ($property === 'filter_date_range') {
            $this->dispatch('refresh-table-mechanics', $this->readMechanics());
        }
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('reset-date-and-time');
    }

    public function rules()
    {
        $rules = [
            'mechanic' => ['required', Rule::unique('ref_mechanics', 'name')->ignore($this->id_mechanic, 'id')]
        ];

        return $rules;
    }

    public function loadSections()
    {
        // Select
        return RefSectionsMechanicModel::all();
    }

    public function loadSubSections()
    {
        return RefSubSectionsMechanicModel::where('ref_sections_mechanic_id', $this->ref_sections_mechanic_id)
            ->get();
    }

    public function render()
    {
        $data = [
            'mechanics' => $this->readMechanics(),
            'sections' => $this->loadSections(),
            'sub_sections' => $this->loadSubSections(),
        ];

        return view('livewire.settings.mechanics', $data);
    }

    public function refreshTableMechanics()
    {
        $this->dispatch('refresh-table-mechanics', $this->readMechanics());
    }

    public function readMechanics()
    { // table_mechanics
        $mechanics = RefMechanicsModel::withTrashed()
            ->when($this->filter_date_range != NULL, function ($query) {
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

        return $mechanics;
    }

    /* --------------------------------- Modals --------------------------------- */
    public function showAddMechanicsModal()
    {
        $this->dispatch('showAddMechanicsModal');
    }

    public function createMechanic()
    {
        $this->authorize('create', RefMechanicsModel::class);
        $this->validate();

        DB::beginTransaction();
        try {
            $mechanic = new RefMechanicsModel();
            $mechanic->name = $this->mechanic;
            $mechanic->ref_sections_mechanic_id = $this->ref_sections_mechanic_id;
            $mechanic->ref_sub_sections_mechanic_id = $this->ref_sub_sections_mechanic_id;
            $mechanic->save();
            DB::commit();
            $this->clear();
            $this->dispatch('hideAddMechanicsModal');
            $this->dispatch('show-success-save-message-toast');
            $this->refreshTableMechanics();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readMechanic($key)
    {
        try {
            $mechanic           = RefMechanicsModel::withTrashed()->findOrFail($key);
            $this->mechanic     = $mechanic->name;
            $this->id_mechanic  = $key;
            $this->editMode     = true;
            $this->showAddMechanicsModal();
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateMechanic()
    {
        $mechanic = RefMechanicsModel::withTrashed()->findOrFail($this->id_mechanic); // Include soft-deleted records in the search
        $this->authorize('update', $mechanic);
        $this->validate();
        DB::beginTransaction();
        try {
            $mechanic->name = $this->mechanic;
            $mechanic->ref_sections_mechanic_id = $this->ref_sections_mechanic_id;
            $mechanic->ref_sub_sections_mechanic_id = $this->ref_sub_sections_mechanic_id;
            $mechanic->save();
            DB::commit();
            $this->clear();
            $this->dispatch('hideAddMechanicsModal');
            $this->dispatch('show-success-update-message-toast');
            $this->refreshTableMechanics();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function softDeleteMechanic($key)
    {
        $mechanic = RefMechanicsModel::findOrFail($key);
        $this->authorize('delete', $mechanic); // Pass the specific instance of RefMechanicsModel to the authorize method

        DB::beginTransaction();
        try {
            $mechanic->delete();

            DB::commit();

            $this->clear();
            $this->dispatch('show-deactivated-message-toast');
            $this->refreshTableMechanics();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function restoreMechanic($key)
    {
        $mechanic = RefMechanicsModel::withTrashed()->findOrFail($key); // Include soft-deleted records in the search
        $this->authorize('restore', $mechanic); // Authorize the restore action

        DB::beginTransaction();
        try {
            $mechanic->restore(); // Restore the soft-deleted mechanic record
            DB::commit();
            $this->clear();
            $this->dispatch('show-activated-message-toast');
            $this->refreshTableMechanics();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function generateMechanicsPDF()
    {
        $signedURL = URL::temporarySignedRoute(
            'generate-mechanics-pdf',
            now()->addMinutes(5),
            ['date' => $this->filter_date_range]
        );

        $this->dispatch('print-pdf', url: $signedURL);
    }
}
