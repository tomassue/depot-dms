<?php

namespace App\Livewire\Settings;

use App\Models\RefSectionsMechanicModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Sections (Mechanic) | DEPOT DMS')]
class RefSectionsMechanic extends Component
{
    public $editMode;
    public $section_id;

    public $name;

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('ref_sections_mechanic')->ignore($this->section_id, 'id')]
        ];
    }

    public function render()
    {
        return view('livewire.settings.ref-sections-mechanic', [
            'sections_mechanic' => $this->loadSectionsMechanicTable()
        ]);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function loadSectionsMechanicTable()
    {
        return RefSectionsMechanicModel::withTrashed()
            ->get();
    }

    public function createSection()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $section = new RefSectionsMechanicModel();
                $section->name = $this->name;
                $section->save();
            });

            $this->clear();
            $this->dispatch('hideSectionsMechanicsModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-sections-mechanic', $this->loadSectionsMechanicTable());
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readSection($section_id)
    {
        $this->editMode = true;

        $section = RefSectionsMechanicModel::withTrashed()
            ->findOrFail($section_id);
        $this->section_id = $section->id;
        $this->fill($section);
        $this->dispatch('showSectionsMechanicsModal');
    }

    public function updateSection()
    {
        $this->validate();

        try {
            $section = RefSectionsMechanicModel::withTrashed()
                ->findOrFail($this->section_id, 'id');

            DB::transaction(function () use ($section) {
                $section->name = $this->name;
                $section->save();
            });

            $this->clear();
            $this->dispatch('hideSectionsMechanicsModal');
            $this->dispatch('show-success-update-message-toast');
            $this->dispatch('refresh-table-sections-mechanic', $this->loadSectionsMechanicTable());
        } catch (\Throwable $th) {
            // dd($th);
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function deleteSection(RefSectionsMechanicModel $section)
    {
        // Soft delete
        $section->delete();
        $this->dispatch('refresh-table-sections-mechanic', $this->loadSectionsMechanicTable());
    }

    public function restoreSection($section_id)
    {
        // Restore
        $section = RefSectionsMechanicModel::withTrashed()->findOrFail($section_id);
        $section->restore();
        $this->dispatch('refresh-table-sections-mechanic', $this->loadSectionsMechanicTable());
    }
}
