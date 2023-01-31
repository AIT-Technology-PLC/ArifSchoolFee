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
            ->editColumn('quantity', function ($saleDetail) {
                return quantity($saleDetail->quantity, $saleDetail->product->unit_of_measurement);
            })
            ->editColumn('batch_no', fn($saleDetail) => $saleDetail->merchandiseBatch?->batch_no)
            ->editColumn('expires_on', fn($saleDetail) => $saleDetail->merchandiseBatch?->expires_on?->toFormattedDateString())
            ->editColumn('unit_price', fn($saleDetail) => money($saleDetail->unit_price))
            ->editColumn('total', fn($saleDetail) => money($saleDetail->totalPrice))
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
                'merchandiseBatch',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('product', 'product.name'),
            Column::make('quantity')->addClass('has-text-right'),
            Column::make('batch_no')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('expires_on')->title('Expiry Date')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('unit_price')->addClass('has-text-right'),
            Column::computed('total')->addClass('has-text-right'),
            Column::computed('actions'),
        ];

        return collect($columns)->filter()->toArray();
    }

    protected function filename()
    {
        return 'Sale Details_' . date('YmdHis');
    }
}
