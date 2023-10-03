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
            ->editColumn('code', fn($priceIncrementDetail) => $priceIncrementDetail->product->code ?: 'N/A')
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
            ->where('price_increment_id', request()->route('price_increment')->id)
            ->with([
                'product',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('product', 'product.name'),
            Column::make('code', 'product.code'),
            Column::computed('actions')->className('actions')->addClass('has-text-right'),
        ];
    }

    protected function filename(): string
    {
        return 'PriceIncrementDetail_' . date('YmdHis');
    }
}
