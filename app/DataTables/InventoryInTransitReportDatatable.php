<?php

namespace App\DataTables;

use App\Http\Requests\InventoryInTransitReportRequest;
use App\Reports\InventoryInTransitReport;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InventoryInTransitReportDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    private $filters;

    public function __construct(InventoryInTransitReportRequest $request)
    {
        $this->filters = $request->validated();
    }

    public function dataTable($query)
    {
        if ($this->filters['transaction_type'] == 'purchases') {
            return datatables()
                ->collection($query->all())
                ->editColumn('date', fn($purchaseDetail) => $purchaseDetail->purchase->purchased_on->toFormattedDateString())
                ->editColumn('product', fn($purchaseDetail) => $purchaseDetail->product->name)
                ->editColumn('code', fn($purchaseDetail) => $purchaseDetail->product->code ?? 'N/A')
                ->editColumn('unit', fn($purchaseDetail) => $purchaseDetail->product->unit_of_measurement)
                ->editColumn('quantity', fn($purchaseDetail) => $purchaseDetail->quantity)
                ->editColumn('supplier', fn($purchaseDetail) => $purchaseDetail->supplier->company_name ?? 'N/A')
                ->editColumn('prepared_by', fn($purchaseDetail) => $purchaseDetail->purchase->createdBy->name)
                ->addIndexColumn();
        }

        return datatables()
            ->collection($query->all())
            ->editColumn('date', fn($transferDetail) => $transferDetail->transfer->issued_on->toFormattedDateString())
            ->editColumn('product', fn($transferDetail) => $transferDetail->product->name)
            ->editColumn('code', fn($transferDetail) => $transferDetail->product->code ?? 'N/A')
            ->editColumn('unit', fn($transferDetail) => $transferDetail->product->unit_of_measurement)
            ->editColumn('quantity', fn($transferDetail) => $transferDetail->quantity)
            ->editColumn('source', fn($transferDetail) => $transferDetail->transfer->transferredFrom->name)
            ->editColumn('destination', fn($transferDetail) => $transferDetail->transfer->transferredTo->name)
            ->editColumn('prepared_by', fn($transferDetail) => $transferDetail->transfer->createdBy->name)
            ->addIndexColumn();
    }

    public function query()
    {
        $inventoryInTransitReport = new InventoryInTransitReport($this->filters);

        return $inventoryInTransitReport->getinventoryInTransits;
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('date'),
            Column::make('prepared_by'),
            Column::make('product'),
            Column::make('code'),
            Column::make('unit'),
            Column::make('quantity'),
            $this->filters['transaction_type'] == 'transfers' ? Column::make('source') : Column::make('supplier'),
            $this->filters['transaction_type'] == 'transfers' ? Column::make('destination') : null,
        ];

        return array_filter($columns);
    }

    protected function filename(): string
    {
        return 'InventoryInTransitReport_' . date('YmdHis');
    }
}
