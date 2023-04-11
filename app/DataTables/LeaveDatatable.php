<?php

namespace App\DataTables;

use App\Models\Leave;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LeaveDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($leave) => route('leaves.show', $leave->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($leave) => $leave->warehouse->name)
            ->editColumn('status', fn($leave) => view('components.datatables.leave-status', compact('leave')))
            ->editColumn('employee', fn($leave) => $leave->employee->user->name)
            ->editColumn('category', fn($leave) => $leave->leaveCategory->name)
            ->editColumn('type', fn($leave) => $leave->isPaidTimeOff() ? 'Paid' : 'Unpaid')
            ->editColumn('starting_period', fn($leave) => $leave->starting_period->toFormattedDateString())
            ->editColumn('ending_period', fn($leave) => $leave->ending_period->toFormattedDateString())
            ->editColumn('description', fn($leave) => view('components.datatables.searchable-description', ['description' => $leave->description]))
            ->editColumn('prepared by', fn($leave) => $leave->createdBy->name)
            ->editColumn('approved by', fn($leave) => $leave->approvedBy->name ?? 'N/A')
            ->editColumn('cancelled by', fn($leave) => $leave->cancelledBy->name ?? 'N/A')
            ->editColumn('edited by', fn($leave) => $leave->updatedBy->name)
            ->editColumn('actions', function ($leave) {
                return view('components.common.action-buttons', [
                    'model' => 'leaves',
                    'id' => $leave->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Leave $leave)
    {
        return $leave
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved()->notCancelled())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved()->notCancelled())
            ->when(request('status') == 'cancelled', fn($query) => $query->cancelled())
            ->select('leaves.*')
            ->with([
                'employee.user',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'cancelledBy:id,name',
                'warehouse:id,name',
                'leaveCategory:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Leave No'),
            Column::computed('status'),
            Column::make('category', ' leaveCategory.name'),
            Column::make('employee', 'employee.user.name'),
            Column::computed('type'),
            Column::make('starting_period')->visible(false),
            Column::make('ending_period')->visible(false),
            Column::make('description')->visible(false),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('cancelled by', 'cancelledBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }
    protected function filename()
    {
        return 'Leave_' . date('YmdHis');
    }
}