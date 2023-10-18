<?php

namespace App\DataTables;

use App\Http\Requests\InventoryInTransitReportRequest;
use App\Reports\InventoryInTransitReport;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InventoryInTransitReportDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function __construct(InventoryInTransitReportRequest $request)
    {
        $this->request = $request;
    }

    public function dataTable($query)
    {
        $collection = Collection::make($query->all());

        $collectionClass = $collection->isEmpty() ? null : get_class($collection->first());

        if ($collectionClass == 'App\Models\PurchaseDetail') {
            return datatables()
                ->collection($query->all())
                ->editColumn('date', fn($purchaseDetail) => $purchaseDetail->purchase->purchased_on?->toFormattedDateString())
                ->editColumn('product', fn($purchaseDetail) => $purchaseDetail->product->name)
                ->editColumn('code', fn($purchaseDetail) => $purchaseDetail->product->code ?? 'N/A')
                ->editColumn('quantity', fn($purchaseDetail) => $purchaseDetail->quantity)
                ->editColumn('supplier', fn($purchaseDetail) => $purchaseDetail->supplier->company_name ?? 'N/A')
                ->editColumn('prepared_by', fn($purchaseDetail) => $purchaseDetail->purchase->createdBy->name ?? 'N/A')
                ->addIndexColumn();
        }

        return datatables()
            ->collection($query->all())
            ->editColumn('date', fn($transferDetail) => $transferDetail->transfer->issued_on?->toFormattedDateString())
            ->editColumn('product', fn($transferDetail) => $transferDetail->product->name)
            ->editColumn('code', fn($transferDetail) => $transferDetail->product->code ?? 'N/A')
            ->editColumn('quantity', fn($transferDetail) => $transferDetail->quantity)
            ->editColumn('source', fn($transferDetail) => $transferDetail->transfer->transferredFrom->name ?? 'N/A')
            ->editColumn('destination', fn($transferDetail) => $transferDetail->transfer->transferredTo->name ?? 'N/A')
            ->editColumn('prepared_by', fn($transferDetail) => $transferDetail->transfer->createdBy->name ?? 'N/A')
            ->addIndexColumn();
    }

    public function query(InventoryInTransitReportRequest $request)
    {
        $inventoryInTransitReport = new InventoryInTransitReport($request->validated());

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
            Column::make('quantity'),
            $this->request->validated('transaction_type') == 'transfers' ? Column::make('source') : Column::make('supplier'),
            $this->request->validated('transaction_type') == 'transfers' ? Column::make('destination') : null,
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename()
    {
        return 'InventoryInTransitReport_' . date('YmdHis');
    }
}
