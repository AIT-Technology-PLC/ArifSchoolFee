<?php

namespace App\DataTables;

use App\Http\Requests\InventoryBatchReportRequest;
use App\Reports\InventoryBatchReport;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InventoryBatchReportDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->collection($query->all())
            ->editColumn('product', fn($merchandiseBatch) => $merchandiseBatch->name)
            ->editColumn('code', fn($merchandiseBatch) => $merchandiseBatch->code ?? 'N/A')
            ->editColumn('unit', fn($merchandiseBatch) => $merchandiseBatch->unit)
            ->editColumn('batch_no', fn($merchandiseBatch) => $merchandiseBatch->batch_no)
            ->editColumn('expires_on', fn($merchandiseBatch) => view('components.datatables.inventory-batch-tag', ['content' => $merchandiseBatch->expires_on]))
            ->editColumn('quantity', fn($merchandiseBatch) => $merchandiseBatch->quantity)
            ->addIndexColumn();
    }

    public function query(InventoryBatchReportRequest $request)
    {
        $inventoryBatchReport = new InventoryBatchReport($request->validated());

        return $inventoryBatchReport->getInventoryBatchReports;
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('product'),
            Column::make('code'),
            Column::make('unit'),
            Column::make('batch_no'),
            Column::make('expires_on')->title('Expiry Date'),
            Column::make('quantity'),
        ];
    }

    protected function filename(): string
    {
        return 'InventoryBatchReport_' . date('YmdHis');
    }
}
