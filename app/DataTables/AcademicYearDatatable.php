<?php

namespace App\DataTables;

use App\Models\AcademicYear;
use App\Models\Section;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AcademicYearDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('starting_period', fn($academicYear) => $academicYear->starting_period?->toFormattedDateString() ?? 'N/A')
            ->editColumn('ending_period', fn($academicYear) => $academicYear->ending_period?->toFormattedDateString() ?? 'N/A')
            ->editColumn('created at', fn($academicYear) => $academicYear->created_at->toFormattedDateString())
            ->editColumn('created by', fn($academicYear) => $academicYear->createdBy->name)
            ->editColumn('edited by', fn($academicYear) => $academicYear->updatedBy->name)
            ->editColumn('actions', function ($academicYear) {
                return view('components.common.action-buttons', [
                    'model' => 'academic-years',
                    'id' => $academicYear->id,
                    'buttons' => ['edit','delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(AcademicYear $academicYear)
    {
        return $academicYear
            ->newQuery()
            ->select('academic_years.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('year'),
            Column::make('title'),
            Column::make('starting_period'),
            Column::make('ending_period'),
            Column::make('created by', 'createdBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('created at', 'created_at'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Academic Years_' . date('YmdHis');
    }
}
