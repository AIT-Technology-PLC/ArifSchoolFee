<?php

namespace App\DataTables;

use App\Models\SaleDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SaleDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product', function ($saleDetail) {
                return view('components.datatables.product-code', [
                    'product' => $saleDetail->product->name,
                    'code' => $saleDetail->product->code ?? '',
                ]);
            })
            ->editColumn('warehouse', fn($saleDetail) => $saleDetail->warehouse?->name ?? 'N/A')
            ->editColumn('quantity', function ($saleDetail) {
                return quantity($saleDetail->quantity, $saleDetail->product->unit_of_measurement);
            })
            ->editColumn('delivered_quantity', function ($saleDetail) {
                return quantity($saleDetail->delivered_quantity, $saleDetail->product->unit_of_measurement);
            })
            ->editColumn('batch_no', fn($saleDetail) => $saleDetail->merchandiseBatch?->batch_no)
            ->editColumn('expires_on', fn($saleDetail) => $saleDetail->merchandiseBatch?->expires_on?->toFormattedDateString())
            ->editColumn('unit_price', fn($saleDetail) => money($saleDetail->unit_price))
            ->editColumn('total', fn($saleDetail) => money($saleDetail->totalPrice))
            ->editColumn('description', fn($saleDetail) => nl2br(e($saleDetail->description)))
            ->editColumn('actions', function ($saleDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'sale-details',
                    'id' => $saleDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(SaleDetail $saleDetail)
    {
        return $saleDetail
            ->newQuery()
            ->select('sale_details.*')
            ->where('sale_id', request()->route('sale')->id)
            ->with([
                'product',
                'warehouse',
                'merchandiseBatch',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('product', 'product.name'),
            userCompany()->canSaleSubtract() ? Column::make('warehouse', 'warehouse.name') : null,
            Column::make('quantity')->addClass('has-text-right'),
            Column::make('delivered_quantity')->addClass('has-text-right')->visible(false),
            Column::make('batch_no', 'merchandiseBatch.batch_no')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('expires_on', 'merchandiseBatch.expires_on')->title('Expiry Date')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('unit_price')->addClass('has-text-right'),
            Column::computed('total')->addClass('has-text-right'),
            Column::make('description')->visible(false),
            Column::computed('actions'),
        ];

        return collect($columns)->filter()->toArray();
    }

    protected function filename(): string
    {
        return 'Sale Details_' . date('YmdHis');
    }
}
