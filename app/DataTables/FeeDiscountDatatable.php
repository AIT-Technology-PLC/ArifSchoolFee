<?php

namespace App\DataTables;

use App\Models\FeeDiscount;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FeeDiscountDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('discount_type', fn($feeDiscount) => str()->ucfirst($feeDiscount->discount_type))
            ->editColumn('created_at', fn($feeDiscount) => $feeDiscount->created_at->toFormattedDateString())
            ->editColumn('added by', fn($feeDiscount) => $feeDiscount->createdBy->name ?? 'N/A')
            ->editColumn('edited by', fn($feeDiscount) => $feeDiscount->updatedBy->name ?? 'N/A')
            ->editColumn('description', fn($feeDiscount) => view('components.datatables.searchable-description', ['description' => $feeDiscount->description]))
            ->editColumn('actions', function ($feeDiscount) {
                return view('components.datatables.fee-discount-action', compact('feeDiscount'));
            })
            ->addIndexColumn();
    }

    public function query(FeeDiscount $feeDiscount)
    {
        return $feeDiscount
            ->newQuery()
            ->select('fee_discounts.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name'),
            Column::make('discount_code'),
            Column::make('discount_type'),
            Column::make('amount'),
            Column::make('description')->visible(false)->content('N/A'),
            Column::make('created_at')->className('has-text-right')->visible(false),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Fee Discounts_' . date('YmdHis');
    }
}
