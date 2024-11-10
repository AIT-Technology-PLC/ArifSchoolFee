<?php

namespace App\DataTables\Admin;

use App\Models\SchoolType;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SchoolTypeDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->editColumn('name', fn($schoolType) => str()->ucfirst($schoolType->name))
            ->editColumn('created_at', fn($schoolType) => $schoolType->created_at->toFormattedDateString())
            ->editColumn('actions', function ($schoolType) {
                return view('components.common.action-buttons', [
                    'model' => 'admin.school-types',
                    'id' => $schoolType->id,
                    'buttons' => ['edit','delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(SchoolType $schoolType)
    {
        return $schoolType
            ->newQuery()
            ->select('school_types.*');
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name'),
            Column::make('created_at'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'School Types' . date('YmdHis');
    }
}

