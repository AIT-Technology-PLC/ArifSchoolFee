<?php

namespace App\DataTables;

use App\Models\StudentHistory;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StudentHistoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    protected $studentId;

    public function __construct($studentId = null)
    {
        $this->studentId = $studentId;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('type', fn($studentHistory) => view('components.datatables.student-history-type', compact('studentHistory')))
            ->editColumn('branch', fn($studentHistory) => $studentHistory->warehouse->name)
            ->editColumn('class', fn($studentHistory) => $studentHistory->schoolClass->name)
            ->editColumn('section', fn($studentHistory) => $studentHistory->section->name)
            ->editColumn('academic year', fn($studentHistory) => $studentHistory->academicYear->year)
            ->editColumn('added on', fn($studentHistory) => $studentHistory->created_at->toFormattedDateString())
            ->editColumn('added by', fn($studentHistory) => $studentHistory->createdBy->name ?? 'N/A')
            ->addIndexColumn();
    }

    public function query(StudentHistory $studentHistory)
    {
        return $studentHistory
            ->newQuery()
            ->select('student_histories.*')
            ->where('student_id', $this->studentId) 
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
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
            Column::make('added on', 'created_at')->title('Date'),
            Column::make('type')->orderable(false),
            Column::make('branch', 'warehouse.name'),
            Column::make('class', 'schoolClass.name'),
            Column::make('section', 'section.name'),
            Column::make('academic year', 'academicYear.year'),
            Column::make('added by', 'createdBy.name')->visible(false),
        ];
    }

    protected function filename(): string
    {
        return 'Student Histories_' . date('YmdHis');
    }
}
