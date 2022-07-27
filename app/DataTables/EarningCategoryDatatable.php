<?php

namespace App\DataTables;

use App\Models\EarningCategory;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EarningCategoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created by', fn($earningCategory) => $earningCategory->createdBy->name)
            ->editColumn('edited by', fn($earningCategory) => $earningCategory->updatedBy->name)
            ->editColumn('actions', function ($earningCategory) {
                return view('components.common.action-buttons', [
                    'model' => 'earning-categories',
                    'id' => $earningCategory->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(EarningCategory $earningCategory)
    {
        return $earningCategory
            ->newQuery()
            ->select('earning_categories.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->addClass('text-green has-text-weight-bold'),
            Column::make('type'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'EarningCategory_' . date('YmdHis');
    }
}
