<?php

namespace App\Livewire\Tables\Incoming;

use App\Models\TblIncomingRequestModel;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class IncomingRequests extends PowerGridComponent
{
    public string $tableName = 'incoming-requests-iznxuw-table';
    public bool $deferLoading = true;

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [0, 10, 50, 100, 500])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return TblIncomingRequestModel::query()->with(['incoming_request_type', 'office', 'type', 'model']);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('reference_no')
            ->add('ref_incoming_request_types_id', fn($item) => e($item->type->name))
            ->add('ref_office_id', fn($item) => e($item->office->name))
            ->add('ref_types_id', fn($item) => e($item->type->name))
            ->add('ref_models_id', fn($item) => e($item->model->name))
            ->add('number')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id'),

            Column::make('Reference No.', 'reference_no')
                ->sortable()
                ->searchable(),

            Column::make('Office/Department', 'ref_office_id')
                ->sortable()
                ->searchable(),

            Column::make('Equipment', 'ref_incoming_request_types_id')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'ref_types_id')
                ->sortable()
                ->searchable(),

            Column::make('Model', 'ref_models_id')
                ->sortable()
                ->searchable(),

            Column::make('No.', 'number')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(TblIncomingRequestModel $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->attributes([
                    'class' => 'btn btn-primary',
                ])
                ->dispatch('editIncomingRequest', ['key' => $row->id]),
        ];
    }

    // public function actionRules($row): array
    // {
    //     return [
    //         // Hide button edit for ID 1
    //         Rule::button('edit')
    //             ->when(fn($row) => $row->id === 1)
    //             ->hide(),
    //     ];
    // }
}
