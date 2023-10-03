<?php

namespace App\DataTables;

use App\Models\EmployeeTransfer;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeeTransferDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($employeeTransfer) => route('employee-transfers.show', $employeeTransfer->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('issued_on', fn($employeeTransfer) => $employeeTransfer->issued_on->toFormattedDateString())
            ->editColumn('status', fn($employeeTransfer) => view('components.datatables.employee-transfer-status', compact('employeeTransfer')))
            ->editColumn('prepared by', fn($employeeTransfer) => $employeeTransfer->createdBy->name ?? 'N/A')
            ->editColumn('approved by', fn($employeeTransfer) => $employeeTransfer->approvedBy->name ?? 'N/A')
            ->editColumn('actions', function ($employeeTransfer) {
                return view('components.common.action-buttons', [
                    'model' => 'employee-transfers',
                    'id' => $employeeTransfer->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(EmployeeTransfer $employeeTransfer)
    {
        return $employeeTransfer
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->Approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->select('employee_transfers.*')
            ->with([
                'employeeTransferDetails',
                'createdBy:id,name',
                'approvedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('code')->className('has-text-centered')->title('Transfer No'),
            Column::computed('status')->orderable(false),
            Column::make('issued_on'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'EmployeeTransfer_' . date('YmdHis');
    }
}
