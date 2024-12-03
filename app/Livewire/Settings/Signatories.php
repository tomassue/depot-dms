<?php

namespace App\Livewire\Settings;

use App\Models\RefSignatoriesModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Signatories | DEPOT DMS')]
class Signatories extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $signatory_id;
    /* -------------------------------------------------------------------------- */
    public $name;
    public $designation;

    public function render()
    {
        return view('livewire.settings.signatories', $this->pageContent());
    }

    public function mount()
    {
        $this->authorize('read', RefSignatoriesModel::class);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function pageContent()
    {
        $signatories = RefSignatoriesModel::withTrashed()->get();

        return [
            'signatories' => $signatories
        ];
    }

    public function rules()
    {
        $rules = [
            'name'        => [
                'required',
                Rule::unique('ref_signatories')->where(function ($query) {
                    return $query->where('designation', $this->designation);
                })
            ],
            'designation' => 'required'
        ];

        return $rules;
    }

    public function createSignatory()
    {
        $this->authorize('create signatory', RefSignatoriesModel::class);

        $this->validate();

        try {
            DB::transaction(function () {
                $signatory              = new RefSignatoriesModel();
                $signatory->name        = $this->name;
                $signatory->designation = $this->designation;
                $signatory->save();
            });

            $pageContent = $this->pageContent();

            $this->clear();
            $this->dispatch('hideSignatoryModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-signatories', $pageContent['signatories']);
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readSignatory($key)
    {
        $this->authorize('read', RefSignatoriesModel::class);

        $this->editMode = true;

        try {
            $signatory = RefSignatoriesModel::findOrFail($key);
            $this->name = $signatory->name;
            $this->designation = $signatory->designation;

            $this->signatory_id = $key;
            $this->dispatch('showSignatoryModal');
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateSignatory()
    {
        $signatory = RefSignatoriesModel::findOrFail($this->signatory_id);

        $this->authorize('update', $signatory);

        $this->validate();

        try {
            DB::transaction(function () use ($signatory) {
                $signatory->name        = $this->name;
                $signatory->designation = $this->designation;
                $signatory->save();
            });

            $pageContent = $this->pageContent();

            $this->clear();
            $this->dispatch('hideSignatoryModal');
            $this->dispatch('show-success-update-message-toast');
            $this->dispatch('refresh-table-signatories', $pageContent['signatories']);
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function softDeleteSignatory($key)
    {
        $signatory = RefSignatoriesModel::findOrFail($key);

        $this->authorize('delete', $signatory);

        try {
            DB::transaction(function () use ($signatory) {
                $signatory->delete();
            });

            $pageContent = $this->pageContent();

            $this->clear();
            $this->dispatch('show-deactivated-message-toast');
            $this->dispatch('refresh-table-signatories', $pageContent['signatories']);
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function restoreSignatory($key)
    {
        $signatory = RefSignatoriesModel::withTrashed()->findOrFail($key);

        $this->authorize('delete', $signatory);

        try {
            DB::transaction(function () use ($signatory) {
                $signatory->restore();
            });

            $pageContent = $this->pageContent();

            $this->clear();
            $this->dispatch('show-activated-message-toast');
            $this->dispatch('refresh-table-signatories', $pageContent['signatories']);
        } catch (\Throwable $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
