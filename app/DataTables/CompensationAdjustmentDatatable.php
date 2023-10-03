<?php

namespace App\DataTables;

use App\Models\CompensationAdjustment;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CompensationAdjustmentDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($compensationAdjustment) => route('compensation-adjustments.show', $compensationAdjustment->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('issued_on', fn($compensationAdjustment) => $compensationAdjustment->issued_on->toFormattedDateString())
            ->editColumn('starting_period', fn($compensationAdjustment) => $compensationAdjustment->starting_period->toDateString())
            ->editColumn('ending_period', fn($compensationAdjustment) => $compensationAdjustment->ending_period->toDateString())
            ->editColumn('status', fn($compensationAdjustment) => view('components.datatables.compensation-adjustment-status', compact('compensationAdjustment')))
            ->editColumn('prepared by', fn($compensationAdjustment) => $compensationAdjustment->createdBy->name)
            ->editColumn('edited by', fn($compensationAdjustment) => $compensationAdjustment->updatedBy->name)
            ->editColumn('approved by', fn($compensationAdjustment) => $compensationAdjustment->approvedBy->name ?? 'N/A')
            ->editColumn('cancelled by', fn($compensationAdjustment) => $compensationAdjustment->cancelledBy->name ?? 'N/A')
            ->editColumn('actions', function ($compensationAdjustment) {
                return view('components.common.action-buttons', [
                    'model' => 'compensation-adjustments',
                    'id' => $compensationAdjustment->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(CompensationAdjustment $compensationAdjustment)
    {
        return $compensationAdjustment
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->when(request('status') == 'cancelled', fn($query) => $query->cancelled())
            ->select('compensation_adjustments.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'cancelledBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('code')->className('has-text-centered')->title('Reference No'),
            Column::computed('status'),
            Column::make('issued_on'),
            Column::make('starting_period'),
            Column::make('ending_period'),
            Column::make('prepared by', 'createdBy.name')->visible(false),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('cancelled by', 'cancelledBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }
    protected function filename(): string
    {
        return 'CompensationAdjustment_' . date('YmdHis');
    }
}
