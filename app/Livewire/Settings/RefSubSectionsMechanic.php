<?php

namespace App\Livewire\Settings;

use App\Models\RefSectionsMechanicModel;
use App\Models\RefSubSectionsMechanicModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Sub-sections(Mechanic) | DEPOT DMS')]
class RefSubSectionsMechanic extends Component
{
    public $editMode;
    public $sub_section_id;

    public $ref_sections_mechanic_id;
    public $name;

    public function rules()
    {
        return [
            'ref_sections_mechanic_id' => 'required',
            'name' => ['required', Rule::unique('ref_sub_sections_mechanic')->ignore($this->sub_section_id, 'id')]
        ];
    }

    public function attributes()
    {
        return [
            'ref_sections_mechanic_id' => 'section',
            'name' => 'sub-section'
        ];
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.settings.ref-sub-sections-mechanic', [
            'ref_sections_mechanic' => $this->loadSections(),
            'sub_sections' => $this->loadSubSections()
        ]);
    }

    public function loadSections()
    {
        // Select
        return RefSectionsMechanicModel::all();
    }

    public function loadSubSections()
    {
        // Table
        return RefSubSectionsMechanicModel::with('section')
            ->withTrashed()
            ->get();
    }

    public function createSubSection()
    {
        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $sub_section = new RefSubSectionsMechanicModel();
                $sub_section->ref_sections_mechanic_id = $this->ref_sections_mechanic_id;
                $sub_section->name = $this->name;
                $sub_section->save();
            });

            $this->clear();
            $this->dispatch('hideSubSectionsMechanicsModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-sub-sections-mechanic', $this->loadSubSections());
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readSubSection($sub_section_id)
    {
        try {
            $this->editMode = true;

            $sub_section = RefSubSectionsMechanicModel::withTrashed()
                ->findOrFail($sub_section_id);

            $this->sub_section_id = $sub_section->id;

            DB::transaction(function () use ($sub_section) {
                $this->ref_sections_mechanic_id = $sub_section->id;
                $this->name = $sub_section->name;
            });

            $this->dispatch('showSubSectionsMechanicsModal');
        } catch (\Throwable $th) {
            // dd($th);
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateSubSection()
    {
        try {
            $this->validate($this->rules(), [], $this->attributes());

            $sub_section = RefSubSectionsMechanicModel::withTrashed()
                ->findOrFail($this->sub_section_id);

            DB::transaction(function () use ($sub_section) {
                $sub_section->ref_sections_mechanic_id = $this->ref_sections_mechanic_id;
                $sub_section->name = $this->name;
                $sub_section->save();
            });

            $this->clear();
            $this->dispatch('hideSubSectionsMechanicsModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-sub-sections-mechanic', $this->loadSubSections());
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function deleteSubSection(RefSubSectionsMechanicModel $sub_section_id)
    {
        // Soft delete
        $sub_section_id->delete();
        $this->dispatch('refresh-table-sub-sections-mechanic', $this->loadSubSections());
    }

    public function restoreSubSection($sub_section_id)
    {
        // Restore
        $sub_section = RefSubSectionsMechanicModel::withTrashed()
            ->findOrFail($sub_section_id);

        $sub_section->restore();
        $this->dispatch('refresh-table-sub-sections-mechanic', $this->loadSubSections());
    }
}
