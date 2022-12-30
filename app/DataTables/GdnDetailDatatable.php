<?php

namespace App\DataTables;

use App\Models\GdnDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GdnDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('from', fn($gdnDetail) => $gdnDetail->warehouse->name)
            ->editColumn('product', function ($gdnDetail) {
                return view('components.datatables.product-code', [
                    'product' => $gdnDetail->product->name,
                    'code' => $gdnDetail->product->code ?? '',
                ]);
            })
            ->editColumn('quantity', function ($gdnDetail) {
                return quantity($gdnDetail->quantity, $gdnDetail->product->unit_of_measurement);
            })
            ->editColumn('unit_price', fn($gdnDetail) => money($gdnDetail->unit_price))
            ->editColumn('discount', fn($gdnDetail) => ($gdnDetail->discount ?? 0) . '%')
            ->editColumn('total', fn($gdnDetail) => money($gdnDetail->totalPrice))
            ->editColumn('description', fn($gdnDetail) => nl2br(e($gdnDetail->description)))
            ->editColumn('actions', function ($gdnDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'gdn-details',
                    'id' => $gdnDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(GdnDetail $gdnDetail)
    {
        return $gdnDetail
            ->newQuery()
            ->select('gdn_details.*')
            ->where('gdn_id', request()->route('gdn')->id)
            ->with([
                'warehouse',
                'product',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('from', 'warehouse.name'),
            Column::make('product', 'product.name'),
            Column::make('quantity')->addClass('has-text-right'),
            Column::make('unit_price')->addClass('has-text-right'),
            userCompany()->isDiscountBeforeTax() ? Column::computed('discount')->addClass('has-text-right') : null,
            Column::computed('total')->addClass('has-text-right'),
            Column::make('description')->visible(false),
            Column::computed('actions'),
        ];

        return collect($columns)->filter()->toArray();
    }

    protected function filename()
    {
        return 'DO Details_' . date('YmdHis');
    }
}
