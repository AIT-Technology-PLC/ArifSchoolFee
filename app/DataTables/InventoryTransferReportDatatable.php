<?php

namespace App\DataTables;

use App\Http\Requests\InventoryTransferReportRequest;
use App\Reports\InventoryTransferReport;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InventoryTransferReportDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function __construct()
    {
        $this->warehouses = authUser()->getAllowedWarehouses('read');
    }

    public function dataTable($query)
    {
        return datatables()
            ->collection($query->all())
            ->editColumn('date', fn($transferDetail) => $transferDetail->transfer->issued_on?->toFormattedDateString())
            ->editColumn('product', fn($transferDetail) => $transferDetail->product->name)
            ->editColumn('quantity', fn($transferDetail) => $transferDetail->quantity)
            ->editColumn('origin', fn($transferDetail) => $transferDetail->transfer->transferredFrom->name ?? 'N/A')
            ->editColumn('destination', fn($transferDetail) => $transferDetail->transfer->transferredTo->name ?? 'N/A')
            ->editColumn('prepared_by', fn($transferDetail) => $transferDetail->transfer->createdBy->name ?? 'N/A')
            ->addIndexColumn();
    }

    public function query(InventoryTransferReportRequest $request)
    {
        $inventoryTransferReport = new InventoryTransferReport($request->validated());

        return $inventoryTransferReport->getInventoryTransfers;
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('date'),
            Column::make('product'),
            Column::make('quantity'),
            Column::make('origin'),
            Column::make('destination'),
            Column::make('prepared_by'),
        ];
    }
    protected function filename()
    {
        return 'InventoryLevelReport_' . date('YmdHis');
    }
}
