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
            ->editColumn('batch_no', fn($purchaseDetail) => $purchaseDetail->batch_no)
            ->editColumn('expires_on', fn($purchaseDetail) => $purchaseDetail->expires_on?->toFormattedDateString())
            ->editColumn('unit_cost', fn($purchaseDetail) => number_format($purchaseDetail->unit_price, 4))
            ->editColumn('unit_cost_in_local_currency', fn($purchaseDetail) => number_format($purchaseDetail->unitPriceInLocalCurrency, 4))
            ->editColumn('total_cost', fn($purchaseDetail) => number_format($purchaseDetail->totalPrice, 2))
            ->editColumn('total_cost_in_local_currency', fn($purchaseDetail) => number_format($purchaseDetail->totalPriceInLocalCurrency, 2))
            ->editColumn('freight_volume', fn($purchaseDetail) => quantity($purchaseDetail->amount, $purchaseDetail->freight_unit))
            ->editColumn('freight_cost', fn($purchaseDetail) => money($purchaseDetail->freightCostValue))
            ->editColumn('freight_insurance_cost', fn($purchaseDetail) => money($purchaseDetail->freightInsuranceCostValue))
            ->editColumn('other_cost_before_tax', fn($purchaseDetail) => money($purchaseDetail->otherCostBeforeTaxValue))
            ->editColumn('other_cost_after_tax', fn($purchaseDetail) => money($purchaseDetail->otherCostAfterTaxValue))
            ->editColumn('duty_paying_value', fn($purchaseDetail) => money($purchaseDetail->dutyPayingValue))
            ->editColumn('custom_duty_tax', fn($purchaseDetail) => money($purchaseDetail->customDutyTax))
            ->editColumn('excise_tax', fn($purchaseDetail) => money($purchaseDetail->exciseTaxAmount))
            ->editColumn('value_added_tax', fn($purchaseDetail) => money($purchaseDetail->valueAddedTax))
            ->editColumn('surtax', fn($purchaseDetail) => money($purchaseDetail->surtaxAmount))
            ->editColumn('withholding_tax', fn($purchaseDetail) => money($purchaseDetail->withHoldingTaxAmount))
            ->editColumn('total_payable_tax', fn($purchaseDetail) => money($purchaseDetail->totalPayableTax))
            ->editColumn('unit_cost_after_tax', fn($purchaseDetail) => money($purchaseDetail->unitCostAfterTax))
            ->editColumn('total_cost_after_tax', fn($purchaseDetail) => money($purchaseDetail->totalCostAfterTax))
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
            Column::make('batch_no')->content('N/A')->visible(false),
            Column::make('expires_on')->content('N/A')->visible(false),

            Column::make('unit_cost', 'unit_price')
                ->addClass('has-text-right')
                ->title(request()->route('purchase')->isImported() ? ('Unit Cost In ' . request()->route('purchase')->currency) : 'Unit Cost'),

            request()->route('purchase')->isImported()
            ? Column::computed('unit_cost_in_local_currency')
                ->title('Unit Cost In ' . userCompany()->currency)->addClass('has-text-right')
            : null,

            Column::computed('total_cost')
                ->addClass('has-text-right')
                ->title(request()->route('purchase')->isImported() ? ('Total Cost In ' . request()->route('purchase')->currency) : 'Total Cost'),

            request()->route('purchase')->isImported()
            ? Column::computed('total_cost_in_local_currency')
                ->title('Total Cost In ' . userCompany()->currency)->addClass('has-text-right')
            : null,

            request()->route('purchase')->isImported() ? Column::computed('freight_volume')->visible(false)->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('freight_cost')->visible(false)->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('freight_insurance_cost')->visible(false)->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('other_cost_before_tax')->visible(false)->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('other_cost_after_tax')->visible(false)->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('duty_paying_value')->visible(false)->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('custom_duty_tax')->visible(false)->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('excise_tax')->visible(false)->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('value_added_tax')->visible(false)->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('surtax')->visible(false)->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('withholding_tax')->visible(false)->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('total_payable_tax')->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('unit_cost_after_tax')->addClass('has-text-right') : null,
            request()->route('purchase')->isImported() ? Column::computed('total_cost_after_tax')->addClass('has-text-right') : null,
            Column::computed('actions'),
        ];

        return collect($columns)->filter()->toArray();
    }

    protected function filename(): string
    {
        return 'Purchase Details_' . date('YmdHis');
    }
}
