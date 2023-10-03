<?php

namespace App\DataTables;

use App\Models\LeaveCategory;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LeaveCategoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('added on', fn($leaveCategory) => $leaveCategory->created_at->toFormattedDateString())
            ->editColumn('created by', fn($leaveCategory) => $leaveCategory->createdBy->name)
            ->editColumn('edited by', fn($leaveCategory) => $leaveCategory->updatedBy->name)
            ->editColumn('actions', function ($leaveCategory) {
                return view('components.common.action-buttons', [
                    'model' => 'leave-categories',
                    'id' => $leaveCategory->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(LeaveCategory $leaveCategory)
    {
        return $leaveCategory
            ->newQuery()
            ->select('leave_categories.*')
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
            Column::make('added on', 'created_at'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }
    protected function filename(): string
    {
        return 'LeaveCategory_' . date('YmdHis');
    }
}
