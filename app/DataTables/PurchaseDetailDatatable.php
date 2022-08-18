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
            ->editColumn('unit_price', fn($purchaseDetail) => money($purchaseDetail->unit_price))
            ->editColumn('total', fn($purchaseDetail) => money($purchaseDetail->totalPrice))
            ->editColumn('duty_paying_value', fn($purchaseDetail) => number_format($purchaseDetail->dutyPayingValue, 2))
            ->editColumn('custom_duty_tax', fn($purchaseDetail) => number_format($purchaseDetail->customDutyTax, 2))
            ->editColumn('excise_tax', fn($purchaseDetail) => number_format($purchaseDetail->exciseTaxAmount, 2))
            ->editColumn('value_added_tax', fn($purchaseDetail) => number_format($purchaseDetail->valueAddedTax, 2))
            ->editColumn('surtax', fn($purchaseDetail) => number_format($purchaseDetail->surtaxAmount, 2))
            ->editColumn('with_holding_tax', fn($purchaseDetail) => number_format($purchaseDetail->withHoldingTaxAmount, 2))
            ->editColumn('total_payable_tax', fn($purchaseDetail) => number_format($purchaseDetail->totalPayableTax, 2))
            ->editColumn('total_cost_after_tax', fn($purchaseDetail) => number_format($purchaseDetail->totalCostAfterTax, 2))
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
            Column::computed('total')->addClass('has-text-right'),
            request()->route('purchase')->isImported() ? Column::computed('duty_paying_value') : null,
            request()->route('purchase')->isImported() ? Column::computed('custom_duty_tax') : null,
            request()->route('purchase')->isImported() ? Column::computed('excise_tax') : null,
            request()->route('purchase')->isImported() ? Column::computed('value_added_tax') : null,
            request()->route('purchase')->isImported() ? Column::computed('surtax') : null,
            request()->route('purchase')->isImported() ? Column::computed('with_holding_tax') : null,
            request()->route('purchase')->isImported() ? Column::computed('total_payable_tax') : null,
            request()->route('purchase')->isImported() ? Column::computed('total_cost_after_tax') : null,
            Column::computed('actions'),
        ];

        return collect($columns)->filter()->toArray();
    }

    protected function filename()
    {
        return 'Purchase Details_' . date('YmdHis');
    }
}
