<?php

namespace App\DataTables;

use App\Models\Price;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductPriceDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('fixed_price', fn($price) => money($price->fixed_price))
            ->editColumn('price_tag', fn($price) => $price->price_tag)
            ->editColumn('is_active', fn($price) => $price->is_active ? 'Yes' : 'No')
            ->editColumn('actions', function ($price) {
                return view('components.datatables.price-detail-action', compact('price'));
            })
            ->addIndexColumn();
    }

    public function query(Price $price)
    {
        return $price
            ->newQuery()
            ->select('prices.*')
            ->where('product_id', request()->route('product')->id);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('fixed_price')->title('Price'),
            Column::make('price_tag')->content('N/A'),
            Column::make('is_active'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'ProductPrice_' . date('YmdHis');
    }
}
