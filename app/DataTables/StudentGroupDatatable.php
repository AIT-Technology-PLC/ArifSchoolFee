<?php

namespace App\DataTables;

use App\Models\StudentGroup;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StudentGroupDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('description', fn($studentGroup) => view('components.datatables.searchable-description', ['description' => $studentGroup->description]))
            ->editColumn('created_at', fn($studentGroup) => $studentGroup->created_at->toFormattedDateString())
            ->editColumn('added by', fn($studentGroup) => $studentGroup->createdBy->name ?? 'N/A')
            ->editColumn('edited by', fn($studentGroup) => $studentGroup->updatedBy->name ?? 'N/A')
            ->editColumn('actions', function ($studentGroup) {
                return view('components.common.action-buttons', [
                    'model' => 'student-groups',
                    'id' => $studentGroup->id,
                    'buttons' => ['edit','delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(StudentGroup $studentGroup)
    {
        return $studentGroup
            ->newQuery()
            ->select('student_groups.*')
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
            Column::make('description')->content('N/A')->visible(false),
            Column::make('created_at'),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Student Groups_' . date('YmdHis');
    }
}
