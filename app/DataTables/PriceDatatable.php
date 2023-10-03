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
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($product) => route('products.prices.index', $product->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('product', fn($product) => $product->name)
            ->editColumn('code', fn($product) => $product->code ?: '-')
            ->editColumn('Active Prices', fn($product) => $product->active_prices_count)
            ->editColumn('Inactive Prices', fn($product) => $product->inactive_prices_count)
            ->editColumn('Total Prices', fn($product) => $product->prices_count)
            ->editColumn('actions', fn($product) => view('components.datatables.price-action', compact('product')))
            ->addIndexColumn();
    }

    public function query(Product $product)
    {
        return $product
            ->newQuery()
            ->whereHas('prices')
            ->select('products.*')
            ->withCount([
                'prices as prices_count' => fn(Builder $query) => $query,
                'prices as active_prices_count' => fn(Builder $query) => $query->active(),
                'prices as inactive_prices_count' => fn(Builder $query) => $query->notActive(),
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('product', 'products.name'),
            Column::make('code', 'products.code'),
            Column::make('Active Prices', 'active_prices_count')->searchable(false)->addClass('has-text-centered'),
            Column::make('Inactive Prices', 'inactive_prices_count')->searchable(false)->addClass('has-text-centered'),
            Column::make('Total Prices', 'prices_count')->searchable(false)->addClass('has-text-centered'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Price_' . date('YmdHis');
    }
}
