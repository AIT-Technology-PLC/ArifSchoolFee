<?php

namespace App\DataTables;

use App\Models\Student;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StudentPromoteDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('first_name', fn($student) => str($student->first_name)->append(' '.$student->last_name))
            ->editColumn('branch', fn($student) => $student->warehouse->name)
            ->editColumn('academic_year', fn($student) => $student->academicYear->year)
            ->editColumn('class', fn($student) => str($student->schoolClass->name)->append(' ( '.$student->section->name . ' )'))
            ->editColumn('checkbox', function ($student) {
                return view('components.datatables.student-promote-checkbox-action', compact('student'));
            })
            ->addIndexColumn();
    }

    public function query(Student $student)
    {
        $query = $student->newQuery()->select('students.*');

        $query->when(is_numeric(request('branch')), fn($q) => $q->where('students.warehouse_id', request('branch')))
            ->when(is_numeric(request('class')), fn($q) => $q->where('students.school_class_id', request('class')))
            ->when(is_numeric(request('section')), fn($q) => $q->where('students.section_id', request('section')))
            ->when(is_numeric(request('academicYear')), fn($q) => $q->where('students.academic_year_id', request('academicYear')));

            if (!request('branch') && !request('class') && !request('section') && !request('academicYear')) {
                $query->whereRaw('1 = 0');
            }
    
            return $query->with([
                'warehouse:id,name',
                'schoolClass:id,name',
                'section:id,name',
                'academicYear:id,year',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('checkbox')->orderable(false)->exportable(false)->printable(false)->width(20)->title('<input type="checkbox" class="select-all-checkbox">'),
            Column::make('code')->title('Admission No'),
            Column::make('first_name')->title('Name'),
            Column::make('branch', 'warehouse.name'),
            Column::make('class', 'schoolClass.name'),
            Column::make('academic_year', 'academicYear.year'),
        ];
    }

    protected function filename(): string
    {
        return 'Student Promotes_' . date('YmdHis');
    }
}
