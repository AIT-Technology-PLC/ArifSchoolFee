<?php

namespace App\DataTables;

use App\Models\Sale;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SaleDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($sale) => route('sales.show', $sale->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($sale) => $sale->warehouse->name)
            ->editColumn('status', fn($sale) => view('components.datatables.sale-status', compact('sale')))
            ->editColumn('total price', function ($sale) {
                return userCompany()->isDiscountBeforeVAT() ?
                userCompany()->currency . '. ' . number_format($sale->grandTotalPrice, 2) :
                userCompany()->currency . '. ' . number_format($sale->grandTotalPriceAfterDiscount, 2);
            })
            ->editColumn('customer', fn($sale) => $sale->customer->company_name ?? 'N/A')
            ->editColumn('description', fn($sale) => view('components.datatables.searchable-description', ['description' => $sale->description]))
            ->editColumn('issued_on', fn($sale) => $sale->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($sale) => $sale->createdBy->name)
            ->editColumn('edited by', fn($sale) => $sale->updatedBy->name)
            ->editColumn('actions', function ($sale) {
                return view('components.common.action-buttons', [
                    'model' => 'sales',
                    'id' => $sale->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Sale $sale)
    {
        return $sale
            ->newQuery()
            ->select('sales.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('sales.warehouse_id', request('branch')))
            ->with([
                'saleDetails',
                'createdBy:id,name',
                'updatedBy:id,name',
                'customer:id,company_name',
                'warehouse:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Invoice No'),
            Column::make('fs_number')->className('has-text-centered')->title('FS No')->content('N/A'),
            Column::computed('status'),
            Column::make('payment_type')->visible(false),
            Column::computed('total price'),
            Column::make('customer', 'customer.company_name'),
            Column::make('description')->visible(false),
            Column::make('issued_on')->title('Issued On'),
            Column::make('prepared by', 'createdBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'Sales Invoice_' . date('YmdHis');
    }
}
