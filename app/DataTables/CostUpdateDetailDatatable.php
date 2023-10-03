<?php

namespace App\DataTables;

use App\Models\CostUpdateDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CostUpdateDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product', fn($costUpdateDetail) => $costUpdateDetail->product->name)
            ->editColumn('average_unit_cost', fn($costUpdateDetail) => $costUpdateDetail->average_unit_cost)
            ->editColumn('fifo_unit_cost', fn($costUpdateDetail) => $costUpdateDetail->fifo_unit_cost ?? 'N/A')
            ->editColumn('lifo_unit_cost', fn($costUpdateDetail) => $costUpdateDetail->lifo_unit_cost ?? 'N/A')
            ->editColumn('actions', function ($costUpdateDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'cost-update-details',
                    'id' => $costUpdateDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(CostUpdateDetail $costUpdateDetail)
    {
        return $costUpdateDetail
            ->newQuery()
            ->select('cost_update_details.*')
            ->where('cost_update_id', request()->route('cost_update')->id)
            ->with([
                'product',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('product', 'product.name'),
            Column::make('average_unit_cost'),
            Column::make('fifo_unit_cost'),
            Column::make('lifo_unit_cost'),
            Column::computed('actions'),
        ];
    }
    protected function filename(): string
    {
        return 'CostUpdateDetail_' . date('YmdHis');
    }
}
