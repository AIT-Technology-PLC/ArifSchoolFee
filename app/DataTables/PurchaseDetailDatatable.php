<?php

namespace App\DataTables;

use App\Models\PurchaseDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PurchaseDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product', function ($purchaseDetail) {
                return view('components.datatables.product-code', [
                    'product' => $purchaseDetail->product->name,
                    'code' => $purchaseDetail->product->code ?? '',
                ]);
            })
            ->editColumn('quantity', function ($purchaseDetail) {
                return quantity($purchaseDetail->quantity, $purchaseDetail->product->unit_of_measurement);
            })
            ->editColumn('unit_price', fn ($purchaseDetail) => money($purchaseDetail->unit_price))
            ->editColumn('discount', fn ($purchaseDetail) => number_format($purchaseDetail->discount * 100, 2).'%')
            ->editColumn('total', fn ($purchaseDetail) => money($purchaseDetail->totalPrice))
            ->editColumn('actions', function ($purchaseDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'purchase-details',
                    'id' => $purchaseDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(PurchaseDetail $purchaseDetail)
    {
        return $purchaseDetail
            ->newQuery()
            ->select('purchase_details.*')
            ->where('purchase_id', request()->route('purchase')->id)
            ->with('product');
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('product', 'product.name'),
            Column::make('quantity')->addClass('has-text-right'),
            Column::make('unit_price')->addClass('has-text-right'),
            userCompany()->isDiscountBeforeVAT() ? Column::computed('discount') : null,
            Column::computed('total')->addClass('has-text-right'),
            Column::computed('actions'),
        ];

        return collect($columns)->filter()->toArray();
    }

    protected function filename()
    {
        return 'Purchase Details_'.date('YmdHis');
    }
}
