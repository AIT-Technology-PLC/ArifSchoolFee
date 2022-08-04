<?php

namespace App\DataTables;

use App\Models\CompensationAdjustmentDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CompensationAdjustmentDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('employee name', fn($compensationAdjustmentDetail) => $compensationAdjustmentDetail->employee->user->name)
            ->editColumn('compensation', fn($compensationAdjustmentDetailDetail) => $compensationAdjustmentDetailDetail->compensation->name)
            ->editColumn('amount', fn($compensationAdjustmentDetailDetail) => $compensationAdjustmentDetailDetail->amount)
            ->editColumn('actions', function ($compensationAdjustmentDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'compensation-adjustment-details',
                    'id' => $compensationAdjustmentDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(CompensationAdjustmentDetail $compensationAdjustmentDetail)
    {
        return $compensationAdjustmentDetail
            ->newQuery()
            ->select('compensation_adjustment_details.*')
            ->where('adjustment_id', request()->route('compensation-adjustment')->id)
            ->with([
                'employee.user',
                'compensation:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('employee name', 'employee.user.name'),
            Column::make('compensation', 'compensation.name'),
            Column::make('amount'),
            Column::computed('actions'),
        ];
    }

    protected function filename()
    {
        return 'CompensationAdjustmentDetail_' . date('YmdHis');
    }
}
