<?php

namespace App\DataTables;

use App\Models\FeeType;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FeeTypeDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('fee_group', fn($feeType) => $feeType->feeGroup->name)
            ->editColumn('created_at', fn($feeType) => $feeType->created_at->toFormattedDateString())
            ->editColumn('added by', fn($feeType) => $feeType->createdBy->name ?? 'N/A')
            ->editColumn('edited by', fn($feeType) => $feeType->updatedBy->name ?? 'N/A')
            ->editColumn('description', fn($feeType) => view('components.datatables.searchable-description', ['description' => $feeType->description]))
            ->editColumn('actions', function ($feeType) {
                return view('components.common.action-buttons', [
                    'model' => 'fee-types',
                    'id' => $feeType->id,
                    'buttons' => ['edit','delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(FeeType $feeType)
    {
        return $feeType
            ->newQuery()
            ->select('fee_types.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'feeGroup:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->addClass('has-text-weight-bold'),
            Column::make('fee_group', 'feeGroup.name')->content('N/A'),
            Column::make('description')->visible(false)->content('N/A'),
            Column::make('created_at')->className('has-text-right'),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Fee Types_' . date('YmdHis');
    }
}
