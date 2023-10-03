<?php

namespace App\DataTables;

use App\Models\Adjustment;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AdjustmentDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($adjustment) => route('adjustments.show', $adjustment->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($adjustment) => $adjustment->warehouse->name)
            ->editColumn('status', fn($adjustment) => view('components.datatables.adjustment-status', compact('adjustment')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'waiting-approval', fn($query) => $query->notApproved())
                    ->when($keyword == 'approved', fn($query) => $query->notAdjusted()->approved())
                    ->when($keyword == 'adjusted', fn($query) => $query->adjusted());
            })
            ->editColumn('description', fn($adjustment) => view('components.datatables.searchable-description', ['description' => $adjustment->description]))
            ->editColumn('issued_on', fn($adjustment) => $adjustment->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($adjustment) => $adjustment->createdBy->name)
            ->editColumn('approved by', fn($adjustment) => $adjustment->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($adjustment) => $adjustment->updatedBy->name)
            ->editColumn('actions', function ($adjustment) {
                return view('components.common.action-buttons', [
                    'model' => 'adjustments',
                    'id' => $adjustment->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Adjustment $adjustment)
    {
        return $adjustment
            ->newQuery()
            ->select('adjustments.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('adjustments.warehouse_id', request('branch')))
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->when(request('status') == 'approved', fn($query) => $query->notAdjusted()->approved())
            ->when(request('status') == 'adjusted', fn($query) => $query->adjusted())
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'warehouse:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Adjustment No'),
            Column::make('status')->orderable(false),
            Column::make('description')->visible(false),
            Column::make('issued_on')->className('has-text-right'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Adjustments_' . date('YmdHis');
    }
}
