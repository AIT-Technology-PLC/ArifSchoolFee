<?php

namespace App\DataTables;

use App\Models\Attendance;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AttendanceDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($attendance) => route('attendances.show', $attendance->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($attendance) => $attendance->warehouse->name)
            ->editColumn('status', fn($attendance) => view('components.datatables.attendance-status', compact('attendance')))
            ->editColumn('issued_on', fn($attendance) => $attendance->issued_on->toFormattedDateString())
            ->editColumn('starting_period', fn($attendance) => $attendance->starting_period->toDateString())
            ->editColumn('ending_period', fn($attendance) => $attendance->ending_period->toDateString())
            ->editColumn('prepared by', fn($attendance) => $attendance->createdBy->name)
            ->editColumn('approved by', fn($attendance) => $attendance->approvedBy->name ?? 'N/A')
            ->editColumn('cancelled by', fn($attendance) => $attendance->cancelledBy->name ?? 'N/A')
            ->editColumn('edited by', fn($attendance) => $attendance->updatedBy->name)
            ->editColumn('actions', function ($attendance) {
                return view('components.common.action-buttons', [
                    'model' => 'attendances',
                    'id' => $attendance->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Attendance $attendance)
    {
        return $attendance
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved()->notCancelled())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved()->notCancelled())
            ->when(request('status') == 'cancelled', fn($query) => $query->cancelled())
            ->select('attendances.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'cancelledBy:id,name',
                'warehouse:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Attendance No'),
            Column::computed('status'),
            Column::make('issued_on'),
            Column::make('starting_period')->visible(false),
            Column::make('ending_period')->visible(false),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('cancelled by', 'cancelledBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'Attendance_' . date('YmdHis');
    }
}
