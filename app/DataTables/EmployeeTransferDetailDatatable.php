<?php

namespace App\DataTables;

use App\Models\EmployeeTransferDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeeTransferDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('employee name', fn($employeeTransferDetail) => $employeeTransferDetail->employee->user->name)
            ->editColumn('transfer to', fn($employeeTransferDetail) => $employeeTransferDetail->warehouse->name)
            ->editColumn('actions', function ($employeeTransferDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'employee-transfer-details',
                    'id' => $employeeTransferDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(EmployeeTransferDetail $employeeTransferDetail)
    {
        return $employeeTransferDetail
            ->newQuery()
            ->select('employee_transfer_details.*')
            ->where('employee_transfer_id', request()->route('employee_transfer')->id)
            ->with([
                'warehouse:id,name',
                'employee.user',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('employee name', 'employee.user.name'),
            Column::make('transfer to', 'warehouse.name'),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'EmployeeTransferDetail_' . date('YmdHis');
    }
}
