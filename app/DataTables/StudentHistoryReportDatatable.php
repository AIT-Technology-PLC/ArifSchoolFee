<?php

namespace App\DataTables;

use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\StudentHistory;

class StudentHistoryReportDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('code', fn($studentHistory) => str($studentHistory->student->code) ?? 'N/A')
            ->editColumn('first_name', fn($studentHistory) => str($studentHistory->student->first_name) ?? "N/A")
            ->editColumn('father_name', fn($studentHistory) => str($studentHistory->student->father_name) ?? "N/A")
            ->editColumn('gender', fn ($studentHistory) => str()->ucfirst($studentHistory->student->gender) ?? 'N/A')
            ->editColumn('phone', fn ($studentHistory) => str()->ucfirst($studentHistory->student->phone) ?? 'N/A')
            ->editColumn('branch', fn ($studentHistory) => str()->ucfirst($studentHistory->warehouse->name) ?? 'N/A')
            ->editColumn('class', fn($studentHistory) => str($studentHistory->schoolClass->name)->append(' ( '.$studentHistory->section->name . ' )'))
            ->editColumn('academic_year', fn ($studentHistory) => str()->ucfirst($studentHistory->academicYear->year) ?? 'N/A')
            ->editColumn('change_date', fn($studentHistory) => $studentHistory->created_at->toFormattedDateString())
            ->editColumn('added_by', fn($studentHistory) => $studentHistory->createdBy->name ?? 'N/A')
            ->editColumn('edited_by', fn($studentHistory) => $studentHistory->updatedBy->name ?? 'N/A')
            ->addIndexColumn();
    }

    public function query(StudentHistory $studentHistory)
    {
        return $studentHistory
            ->newQuery()
            ->when(is_numeric(request('branches')), fn($query) => $query->where('student_histories.warehouse_id', request('branches')))
            ->when(is_numeric(request('classes')), fn($query) => $query->where('student_histories.school_class_id', request('classes')))
            ->when(is_numeric(request('sections')), fn($query) => $query->where('student_histories.section_id', request('sections')))
            ->when(is_numeric(request('academicYears')), fn($query) => $query->where('student_histories.academic_year_id', request('academicYears')))
            ->select('student_histories.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'student',
                'warehouse:id,name',
                'schoolClass:id,name',
                'section:id,name',
                'academicYear:id,year',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('code', 'student.code')->title('Admission No'),
            Column::make('first_name', 'student.first_name'),
            Column::make('father_name', 'student.father_name'),
            Column::make('gender', 'student.gender'),
            Column::make('phone', 'student.phone')->visible(false),
            Column::make('branch', 'warehouse.name'),
            Column::make('class', 'schoolClass.name'),
            Column::make('academic_year', 'academicYear.year'),
            Column::make('change_date', 'created_at'),
            Column::make('added_by', 'createdBy.name')->visible(false),
            Column::make('edited_by', 'updatedBy.name')->visible(false),
        ];
    }

    protected function filename(): string
    {
        return 'Student History Reports_' . date('YmdHis');
    }
}

