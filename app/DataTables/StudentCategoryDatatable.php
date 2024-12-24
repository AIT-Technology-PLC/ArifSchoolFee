<?php

namespace App\DataTables;

use App\Models\StudentCategory;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StudentCategoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('description', fn($studentCategory) => view('components.datatables.searchable-description', ['description' => $studentCategory->description]))
            ->editColumn('created_at', fn($studentCategory) => $studentCategory->created_at->toFormattedDateString())
            ->editColumn('added by', fn($studentCategory) => $studentCategory->createdBy->name ?? 'N/A')
            ->editColumn('edited by', fn($studentCategory) => $studentCategory->updatedBy->name ?? 'N/A')
            ->editColumn('actions', function ($studentCategory) {
                return view('components.common.action-buttons', [
                    'model' => 'student-categories',
                    'id' => $studentCategory->id,
                    'buttons' => ['edit','delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(StudentCategory $studentCategory)
    {
        return $studentCategory
            ->newQuery()
            ->select('student_categories.*')
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
        return 'Student Categories_' . date('YmdHis');
    }
}
