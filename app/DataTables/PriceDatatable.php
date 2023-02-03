<?php

namespace App\DataTables;

use App\Models\Product;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PriceDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product', function ($product) {
                return view('components.datatables.product-code', [
                    'product' => $product->name,
                    'code' => $product->code ?? '',
                ]);
            })
            ->editColumn('Active Price', fn($product) => $product->active_prices_count)
            ->editColumn('Inactive Price', fn($product) => $product->inactive_prices_count)
            ->editColumn('actions', function ($product) {
                return view('components.datatables.price-action', compact('product'));
            })
            ->addIndexColumn();
    }

    public function query(Product $product)
    {
        return $product
            ->newQuery()
            ->whereHas('prices')
            ->select('products.*')
            ->withCount([
                'prices as active_prices_count' => function (Builder $query) {
                    $query->active();
                },
                'prices as inactive_prices_count' => function (Builder $query) {
                    $query->notActive();
                },
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('product', 'products.name')->addClass('is-capitalized'),
            Column::make('Active Price', 'active_prices_count')->addClass('has-text-centered'),
            Column::make('Inactive Price', 'inactive_prices_count')->addClass('has-text-centered'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'Price_' . date('YmdHis');
    }
}
