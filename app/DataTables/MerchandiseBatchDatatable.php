<?php

namespace App\DataTables;

use App\Models\MerchandiseBatch;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MerchandiseBatchDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->collection($query->all())
            ->editColumn('branch', fn($merchandiseBatch) => $merchandiseBatch->branch)
            ->editColumn('product', fn($merchandiseBatch) => $merchandiseBatch->product)
            ->editColumn('code', fn($merchandiseBatch) => $merchandiseBatch->code ?? 'N/A')
            ->editColumn('unit', fn($merchandiseBatch) => $merchandiseBatch->unit)
            ->editColumn('batch_no', fn($merchandiseBatch) => $merchandiseBatch->batch_no)
            ->editColumn('expires_on', fn($merchandiseBatch) => view('components.datatables.inventory-batch-tag', ['content' => $merchandiseBatch->expires_on]))
            ->editColumn('quantity', fn($merchandiseBatch) => $merchandiseBatch->quantity)
            ->editColumn('actions', function ($merchandiseBatch) {
                return view('components.common.action-buttons', [
                    'model' => 'merchandise-batches',
                    'id' => $merchandiseBatch->id,
                    'buttons' => ['edit'],
                ]);
            })
            ->rawColumns(['actions'])
            ->addIndexColumn();
    }

    public function query(MerchandiseBatch $merchandiseBatch)
    {
        return $merchandiseBatch
            ->newQuery()
            ->select('merchandise_batches.*')
            ->join('merchandises', 'merchandise_batches.merchandise_id', '=', 'merchandises.id')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('merchandises.warehouse_id', request('branch')))
            ->when(request('availability') == 'available', fn($q) => $q->available())
            ->when(request('availability') == 'out_of_stock', fn($q) => $q->where('merchandise_batches.quantity', '=', 0))
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('warehouses', 'merchandises.warehouse_id', '=', 'warehouses.id')
            ->selectRaw('
                warehouses.name AS branch,
                products.name AS product,
                products.code as code,
                products.unit_of_measurement as unit,
                merchandise_batches.quantity as quantity,
                merchandise_batches.batch_no as batch_no,
                merchandise_batches.expires_on as expires_on')->get();
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('branch'),
            Column::make('product'),
            Column::make('code'),
            Column::make('unit'),
            Column::make('batch_no'),
            Column::make('expires_on')->title('Expiry Date'),
            Column::make('quantity'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'MerchandiseBatch_' . date('YmdHis');
    }
}
