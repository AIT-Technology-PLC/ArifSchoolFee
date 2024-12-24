<?php

namespace App\DataTables;

use App\Models\Designation;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DesignationDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('staff', fn($designation) => $designation->staffs_count)
            ->editColumn('added on', fn($designation) => $designation->created_at->toFormattedDateString())
            ->editColumn('created by', fn($designation) => $designation->createdBy->name)
            ->editColumn('edited by', fn($designation) => $designation->updatedBy->name)
            ->editColumn('actions', function ($designation) {
                return view('components.common.action-buttons', [
                    'model' => 'designations',
                    'id' => $designation->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Designation $designation)
    {
        return $designation
            ->newQuery()
            ->select('designations.*')
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
            Column::make('name'),
            Column::computed('staff')->className('has-text-centered'),
            Column::make('added on', 'created_at')->className('has-text-right'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Designations_' . date('YmdHis');
    }
}
