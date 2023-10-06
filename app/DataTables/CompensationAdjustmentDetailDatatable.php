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
            ->editColumn('employee', fn($compensationAdjustmentDetail) => $compensationAdjustmentDetail->employee->user->name)
            ->editColumn('compensation', fn($compensationAdjustmentDetail) => $compensationAdjustmentDetail->compensation->name)
            ->editColumn('amount', fn($compensationAdjustmentDetail) => $compensationAdjustmentDetail->amount)
            ->editColumn('overtime', fn($compensationAdjustmentDetail) => $compensationAdjustmentDetail->options?->overtime_period ?? 'N/A')
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
            ->where('adjustment_id', request()->route('compensation_adjustment')->id)
            ->with([
                'employee.user',
                'compensation:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('employee', 'employee.user.name'),
            Column::make('compensation', 'compensation.name'),
            Column::make('amount'),
            Column::make('overtime', 'options->overtime_period')->visible(false),
            Column::make('description')->visible(false)->content('N/A'),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'CompensationAdjustmentDetail_' . date('YmdHis');
    }
}
