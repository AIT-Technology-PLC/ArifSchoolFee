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
            ->editColumn('batch_no', fn($proformaInvoiceDetail) => $proformaInvoiceDetail->merchandiseBatch?->batch_no)
            ->editColumn('expires_on', fn($proformaInvoiceDetail) => $proformaInvoiceDetail->merchandiseBatch?->expires_on?->toFormattedDateString())
            ->editColumn('unit_price', fn($proformaInvoiceDetail) => money($proformaInvoiceDetail->unit_price))
            ->editColumn('discount', fn($proformaInvoiceDetail) => ($proformaInvoiceDetail->discount ?? 0) . '%')
            ->editColumn('total', fn($proformaInvoiceDetail) => money($proformaInvoiceDetail->totalPrice))
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
            Column::make('batch_no', 'merchandiseBatch.batch_no')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('expires_on', 'merchandiseBatch.expires_on')->title('Expiry Date')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('unit_price')->addClass('has-text-right'),
            userCompany()->isDiscountBeforeTax() ? Column::computed('discount') : null,
            Column::computed('total')->addClass('has-text-right'),
            Column::computed('actions'),
        ];

        return collect($columns)->filter()->toArray();
    }

    protected function filename(): string
    {
        return 'Proforma Invoice Details_' . date('YmdHis');
    }
}
