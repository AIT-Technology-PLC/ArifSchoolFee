<?php

namespace App\DataTables;

use App\Models\Department;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DepartmentDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('staff', fn($department) => $department->staffs_count)
            ->editColumn('added on', fn($department) => $department->created_at->toFormattedDateString())
            ->editColumn('created by', fn($department) => $department->createdBy->name)
            ->editColumn('edited by', fn($department) => $department->updatedBy->name)
            ->editColumn('actions', function ($department) {
                return view('components.common.action-buttons', [
                    'model' => 'departments',
                    'id' => $department->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Department $department)
    {
        return $department
            ->newQuery()
            ->select('departments.*')
            ->withCount('staffs')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->addClass('has-text-weight-bold'),
            Column::computed('staff')->className('has-text-centered'),
            Column::make('added on', 'created_at')->className('has-text-right'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Departments_' . date('YmdHis');
    }
}
