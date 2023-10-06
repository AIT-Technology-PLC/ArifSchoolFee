<?php

namespace App\DataTables;

use App\Models\ProformaInvoice;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProformaInvoiceDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($proformaInvoice) => route('proforma-invoices.show', $proformaInvoice->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('branch', fn($proformaInvoice) => $proformaInvoice->warehouse->name)
            ->editColumn('code', fn($proformaInvoice) => $proformaInvoice->reference)
            ->editColumn('transaction', function ($proformaInvoice) {
                return view('components.datatables.link', [
                    'url' => !is_null($proformaInvoice->proformaInvoiceable_id) ? route((new $proformaInvoice->proformaInvoiceable_type())->getTable() . '.show', $proformaInvoice->proformaInvoiceable_id) : 'javascript:void(0)',
                    'label' => !is_null($proformaInvoice->proformaInvoiceable_id) ? $proformaInvoice->proformaInvoiceable_type::find($proformaInvoice->proformaInvoiceable_id)->code : 'N/A',
                ]);
            })
            ->editColumn('status', fn($proformaInvoice) => view('components.datatables.proforma-invoice-status', compact('proformaInvoice')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'pending', fn($query) => $query->pending())
                    ->when($keyword == 'confirmed', fn($query) => $query->converted())
                    ->when($keyword == 'cancelled', fn($query) => $query->notPending()->notConverted());
            })
            ->editColumn('customer', fn($proformaInvoice) => $proformaInvoice->customer->company_name ?? 'N/A')
            ->editColumn('customer_tin', fn($proformaInvoice) => $proformaInvoice->customer->tin ?? 'N/A')
            ->editColumn('contact', fn($proformaInvoice) => $proformaInvoice->contact->name ?? 'N/A')
            ->editColumn('terms', fn($proformaInvoice) => view('components.datatables.searchable-description', ['description' => $proformaInvoice->description]))
            ->editColumn('issued_on', fn($proformaInvoice) => $proformaInvoice->issued_on->toFormattedDateString())
            ->editColumn('expires_on', fn($proformaInvoice) => $proformaInvoice->expires_on?->toFormattedDateString() ?? 'N/A')
            ->editColumn('prepared by', fn($proformaInvoice) => $proformaInvoice->createdBy->name)
            ->editColumn('edited by', fn($proformaInvoice) => $proformaInvoice->updatedBy->name)
            ->editColumn('actions', function ($proformaInvoice) {
                return view('components.common.action-buttons', [
                    'model' => 'proforma-invoices',
                    'id' => $proformaInvoice->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(ProformaInvoice $proformaInvoice)
    {
        return $proformaInvoice
            ->newQuery()
            ->select('proforma_invoices.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('proforma_invoices.warehouse_id', request('branch')))
            ->when(request('status') == 'pending', fn($query) => $query->pending())
            ->when(request('status') == 'confirmed', fn($query) => $query->converted())
            ->when(request('status') == 'converted', fn($query) => $query->associated())
            ->when(request('status') == 'cancelled', fn($query) => $query->notPending()->notConverted())
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'customer:id,company_name,tin',
                'contact:id,name',
                'warehouse:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->title('PI No'),
            Column::computed('transaction')->className('has-text-centered actions'),
            Column::make('status')->orderable(false),
            Column::make('customer', 'customer.company_name'),
            Column::make('customer_tin', 'customer.tin')->visible(false)->title('Customer TIN'),
            Column::make('contact', 'contact.name'),
            Column::make('terms')->visible(false),
            Column::make('issued_on'),
            Column::make('expires_on')->visible(false),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Proforma Invoices_' . date('YmdHis');
    }
}
