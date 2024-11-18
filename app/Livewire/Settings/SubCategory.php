<?php

namespace App\Livewire\Settings;

use App\Models\RefCategoryModel;
use App\Models\RefSubCategoryModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SubCategory extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $id_sub_category;

    /* ---------------------------------- Model --------------------------------- */
    public $id_ref_category;
    public $sub_category;

    public function rules()
    {
        $rules = [
            'id_ref_category' => 'required',
            'sub_category' => [
                'required',
                Rule::unique('ref_sub_category', 'name')
                    ->where(function ($query) {
                        $query->where('id_ref_category', $this->id_ref_category);
                    })
                    ->ignore($this->id_sub_category, 'id') // Replace $this->id with your primary key field
            ]
        ];

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'id_ref_category' => 'category',
            'sub_category'    => 'sub-category'
        ];

        return $attributes;
    }

    public function render()
    {
        $data = [
            'sub_categories' => $this->readSubCategories(),
            'id_ref_categories' => $this->readIDRefCategories()
        ];

        return view('livewire.settings.sub-category', $data);
    }

    public function mount()
    {
        $this->authorize('read', RefSubCategoryModel::class);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('refresh-plugin');
    }

    public function readSubCategories()
    { // table sub-categories
        $sub_categories = RefSubCategoryModel::with('category')
            ->withTrashed()
            ->get();

        return $sub_categories;
    }

    public function readIDRefCategories()
    { // categories-select
        $id_ref_categories = RefCategoryModel::all()
            ->map(function ($item) {
                return [
                    'label' => $item->name,
                    'value' => $item->id
                ];
            });

        return $id_ref_categories;
    }

    public function createSubCategory()
    {
        $this->authorize('create', RefSubCategoryModel::class);

        $this->validate($this->rules(), [], $this->attributes());

        try {
            DB::transaction(function () {
                $sub_category = new RefSubCategoryModel();
                $sub_category->id_ref_category = $this->id_ref_category;
                $sub_category->name = $this->sub_category;
                $sub_category->save();
            });

            $this->clear();
            $this->dispatch('hideSubCategoryModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-sub-categories', $this->readSubCategories());
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readSubCategory($key)
    {
        $this->authorize('read', RefSubCategoryModel::class);

        try {
            $sub_category = RefSubCategoryModel::withTrashed()
                ->findOrFail($key);
            $this->dispatch('select-id-ref-category', $sub_category->id_ref_category);
            $this->sub_category = $sub_category->name;
            $this->id_sub_category = $key;
            $this->editMode = true;

            $this->dispatch('showSubCategoryModal');
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateSubCategory()
    {
        $sub_category =  RefSubCategoryModel::withTrashed()
            ->findOrFail($this->id_sub_category);

        $this->authorize('update', $sub_category);

        $this->validate();

        try {
            DB::transaction(function () use ($sub_category) {
                $sub_category->id_ref_category = $this->id_ref_category;
                $sub_category->name            = $this->sub_category;
                $sub_category->save();
            });

            $this->clear();
            $this->dispatch('hideSubCategoryModal');
            $this->dispatch('show-success-update-message-toast');
            $this->dispatch('refresh-table-sub-categories', $this->readSubCategories());
        } catch (\Exception $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function softDeleteSubCategory($key)
    {
        $sub_category =  RefSubCategoryModel::findOrFail($key);

        $this->authorize('delete', $sub_category);

        try {
            $sub_category->delete();

            $this->clear();
            $this->dispatch('show-deactivated-message-toast');
            $this->dispatch('refresh-table-sub-categories', $this->readSubCategories());
        } catch (\Exception $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function restoreSubCategory($key)
    {
        $sub_category =  RefSubCategoryModel::withTrashed()
            ->findOrFail($key);

        $this->authorize('delete', $sub_category);

        try {
            $sub_category->restore();

            $this->clear();
            $this->dispatch('show-activated-message-toast');
            $this->dispatch('refresh-table-sub-categories', $this->readSubCategories());
        } catch (\Exception $th) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
