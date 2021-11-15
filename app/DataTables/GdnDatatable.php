<?php

namespace App\DataTables;

use App\Models\Gdn;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GdnDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($gdn) => route('gdns.show', $gdn->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('delivery order no', fn($gdn) => $gdn->code)
            ->editColumn('receipt no', fn($gdn) => $gdn->sale->code ?? 'N/A')
            ->editColumn('status', fn($gdn) => view('components.datatables.gdn-status', compact('gdn')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when(Str::contains('waiting approval', $keyword), fn($query) => $query->notApproved())
                    ->when(Str::contains('approved', $keyword), fn($query) => $query->notSubtracted()->approved())
                    ->when(Str::contains('subtracted', $keyword), fn($query) => $query->subtracted());
            })
            ->editColumn('total price', function ($gdn) {
                return userCompany()->isDiscountBeforeVAT() ?
                userCompany()->currency . '. ' . number_format($gdn->grandTotalPrice, 2) :
                userCompany()->currency . '. ' . number_format($gdn->grandTotalPriceAfterDiscount, 2);
            })
            ->editColumn('customer', fn($gdn) => $gdn->customer->company_name ?? 'N/A')
            ->editColumn('description', fn($gdn) => view('components.datatables.searchable-description', ['description' => $gdn->description]))
            ->editColumn('issued on', fn($gdn) => $gdn->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($gdn) => $gdn->createdBy->name)
            ->editColumn('approved by', fn($gdn) => $gdn->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($gdn) => $gdn->updatedBy->name)
            ->editColumn('actions', function ($gdn) {
                return view('components.common.action-buttons', [
                    'model' => 'gdns',
                    'id' => $gdn->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Gdn $gdn)
    {
        return $gdn
            ->newQuery()
            ->select('gdns.*')
            ->with([
                'gdnDetails',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'sale:id,code',
                'customer:id,company_name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('delivery order no', 'code')->className('has-text-centered'),
            isFeatureEnabled('Sale Management') ? Column::make('receipt no', 'sale.code')->content('N/A') : null,
            Column::make('status', 'status')->orderable(false),
            Column::make('payment_type', 'payment_type')->visible(false),
            Column::computed('total price')->visible(false),
            Column::make('customer', 'customer.company_name'),
            Column::make('description', 'description')->visible(false),
            Column::make('issued on', 'issued_on'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename()
    {
        return 'Delivery Orders_' . date('YmdHis');
    }
}
