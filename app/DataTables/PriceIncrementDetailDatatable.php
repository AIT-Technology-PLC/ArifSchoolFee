<?php

namespace App\DataTables;

use App\Models\PriceIncrementDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PriceIncrementDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product', fn($priceIncrementDetail) => $priceIncrementDetail->product->name)
            ->editColumn('actions', function ($priceIncrementDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'price-increment-details',
                    'id' => $priceIncrementDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(PriceIncrementDetail $priceIncrementDetail)
    {
        return $priceIncrementDetail
            ->newQuery()
            ->select('price_increment_details.*')
            ->with([
                'product',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('product', 'product.name'),
            Column::computed('actions')->className('actions')->addClass('has-text-right'),
        ];
    }

    protected function filename()
    {
        return 'PriceIncrementDetail_' . date('YmdHis');
    }
}