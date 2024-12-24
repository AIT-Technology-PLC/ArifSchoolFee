<?php

namespace App\DataTables;

use App\Models\FeeGroup;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FeeGroupDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', fn($feeGroup) => $feeGroup->created_at->toFormattedDateString())
            ->editColumn('added by', fn($feeGroup) => $feeGroup->createdBy->name ?? 'N/A')
            ->editColumn('edited by', fn($feeGroup) => $feeGroup->updatedBy->name ?? 'N/A')
            ->editColumn('description', fn($feeGroup) => view('components.datatables.searchable-description', ['description' => $feeGroup->description]))
            ->editColumn('actions', function ($feeGroup) {
                return view('components.common.action-buttons', [
                    'model' => 'fee-groups',
                    'id' => $feeGroup->id,
                    'buttons' => ['edit','delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(FeeGroup $feeGroup)
    {
        return $feeGroup
            ->newQuery()
            ->select('fee_groups.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name'),
            Column::make('description')->visible(false)->content('N/A'),
            Column::make('created_at')->className('has-text-right'),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Fee Groups_' . date('YmdHis');
    }
}
