<?php

namespace App\DataTables;

use App\Models\AttendanceDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AttendanceDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('employee', fn($attendanceDetail) => $attendanceDetail->employee->user->name)
            ->editColumn('actions', function ($attendanceDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'attendance-details',
                    'id' => $attendanceDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(AttendanceDetail $attendanceDetail)
    {
        return $attendanceDetail
            ->newQuery()
            ->select('attendance_details.*')
            ->where('attendance_id', request()->route('attendance')->id)
            ->with([
                'employee.user',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('employee', 'employee.user.name'),
            Column::make('days'),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'AttendanceDetail_' . date('YmdHis');
    }
}
