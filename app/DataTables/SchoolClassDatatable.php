<?php

namespace App\DataTables;

use App\Models\SchoolClass;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SchoolClassDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', fn($schoolClass) => $schoolClass->created_at->toFormattedDateString())
            ->editColumn('created by', fn($schoolClass) => $schoolClass->createdBy->name)
            ->editColumn('edited by', fn($schoolClass) => $schoolClass->updatedBy->name)
            ->editColumn('sections', fn($schoolClass) => implode(', ', $schoolClass->sections->pluck('name')->toArray()))
            ->editColumn('actions', function ($schoolClass) {
                return view('components.common.action-buttons', [
                    'model' => 'school-classes',
                    'id' => $schoolClass->id,
                    'buttons' => ['edit','delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(SchoolClass $schoolClass)
    {
        return $schoolClass
            ->newQuery()
            ->select('school_classes.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'sections:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->addClass('has-text-weight-bold'),
            Column::make('sections','section.name'),
            Column::make('created_at')->className('has-text-right'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'School Classes_' . date('YmdHis');
    }
}
