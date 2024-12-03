<?php

namespace App\Livewire\Settings;

use App\Models\RefCategoryModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Category | DEPOT DMS')]
class Category extends Component
{
    use AuthorizesRequests;

    public $editMode, $disable_input;
    public $id_category;

    /* ---------------------------------- Model --------------------------------- */
    public $category;

    public function mount()
    {
        $this->authorize('can read category');
    }

    public function rules()
    {
        $rules = [
            'category' => ['required', Rule::unique('ref_category', 'name')->ignore($this->id_category, 'id')]
        ];

        return $rules;
    }

    public function render()
    {
        $data = [
            'categories' => $this->readCategories()
        ];

        return view('livewire.settings.category', $data);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function createCategory()
    {
        $this->authorize('create', RefCategoryModel::class);
        $this->validate();

        DB::beginTransaction();

        try {
            $category = new RefCategoryModel();
            $category->name = $this->category;
            $category->save();

            DB::commit();

            $this->clear();
            $this->dispatch('hideCategoryModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('refresh-table-category', $this->readCategories());
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readCategory($key)
    {
        try {
            $category           = RefCategoryModel::withTrashed()->findOrFail($key);
            $this->category     = $category->name;
            $this->id_category  = $key;
            $this->editMode     = true;

            $this->dispatch('showCategoryModal');
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function softDeleteCategory($key)
    {
        $category = RefCategoryModel::findOrFail($key);

        $this->authorize('delete', $category);

        DB::beginTransaction();
        try {
            $category->delete();

            DB::commit();

            $this->clear();
            $this->dispatch('show-deactivated-message-toast');
            $this->dispatch('refresh-table-category', $this->readCategories());
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function restoreCategory($key)
    {
        $category = RefCategoryModel::withTrashed()->findOrFail($key);

        $this->authorize('restore', $category);

        DB::beginTransaction();
        try {
            $category->restore();

            DB::commit();

            $this->clear();
            $this->dispatch('show-activated-message-toast');
            $this->dispatch('refresh-table-category', $this->readCategories());
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function updateCategory()
    {
        $category = RefCategoryModel::withTrashed()->findOrFail($this->id_category);

        $this->authorize('update', $category);

        $this->validate();

        DB::beginTransaction();
        try {
            $category->name = $this->category;
            $category->save();

            DB::commit();

            $this->clear();
            $this->dispatch('hideCategoryModal');
            $this->dispatch('show-success-update-message-toast');
            $this->dispatch('refresh-table-category', $this->readCategories());
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function readCategories()
    { // table_categories
        $categories = RefCategoryModel::withTrashed()->get();

        return $categories;
    }
}
