<?php

namespace App\DataTables;

use App\Models\Student;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StudentDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('first_name', fn($student) => str($student->first_name)->append(' '.$student->last_name))
            ->editColumn('branch', fn($student) => $student->warehouse->name)
            ->editColumn('class', fn($student) => str($student->schoolClass->name)->append(' ( '.$student->section->name . ' )'))
            ->editColumn('category', fn($student) => $student->studentCategory->name)
            ->editColumn('added on', fn($student) => $student->created_at->toFormattedDateString())
            ->editColumn('added by', fn($student) => $student->createdBy->name ?? 'N/A')
            ->editColumn('edited by', fn($student) => $student->updatedBy->name ?? 'N/A')
            ->editColumn('actions', function ($student) {
                return view('components.common.action-buttons', [
                    'model' => 'students',
                    'id' => $student->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Student $student)
    {
        return $student
            ->newQuery()
            ->select('students.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('students.warehouse_id', request('branch')))
            ->when(is_numeric(request('class')), fn($query) => $query->where('students.school_class_id', request('class')))
            ->when(is_numeric(request('section')), fn($query) => $query->where('students.section_id', request('section')))
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'warehouse:id,name',
                'schoolClass:id,name',
                'section:id,name',
                'studentCategory:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('code')->title('Admission No'),
            Column::make('first_name')->title('Name'),
            Column::make('branch', 'warehouse.name'),
            Column::make('class', 'schoolClass.name'),
            Column::make('category', 'studentCategory.name'),
            Column::make('added on', 'created_at')->className('has-text-right')->visible(false),
            Column::make('added by', 'createdBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Students_' . date('YmdHis');
    }
}
