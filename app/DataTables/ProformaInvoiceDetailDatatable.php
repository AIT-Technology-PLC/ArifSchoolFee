<?php

namespace App\DataTables;

use App\Models\ProformaInvoiceDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProformaInvoiceDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product', function ($proformaInvoiceDetail) {
                return view('components.datatables.product-code', [
                    'product' => $proformaInvoiceDetail->product->name ?? $proformaInvoiceDetail->custom_product,
                    'code' => $proformaInvoiceDetail->product->code ?? '',
                ]);
            })
            ->editColumn('quantity', function ($proformaInvoiceDetail) {
                return quantity($proformaInvoiceDetail->quantity, $proformaInvoiceDetail->product->unit_of_measurement ?? null);
            })
            ->editColumn('unit_price', fn ($proformaInvoiceDetail) => money($proformaInvoiceDetail->unit_price))
            ->editColumn('discount', fn ($proformaInvoiceDetail) => number_format($proformaInvoiceDetail->discount * 100, 2).'%')
            ->editColumn('total', fn ($proformaInvoiceDetail) => money($proformaInvoiceDetail->totalPrice))
            ->editColumn('actions', function ($proformaInvoiceDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'proforma-invoice-details',
                    'id' => $proformaInvoiceDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(ProformaInvoiceDetail $proformaInvoiceDetail)
    {
        return $proformaInvoiceDetail
            ->newQuery()
            ->select('proforma_invoice_details.*')
            ->where('proforma_invoice_id', request()->route('proforma_invoice')->id)
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
        return 'Proforma Invoice Details_'.date('YmdHis');
    }
}
