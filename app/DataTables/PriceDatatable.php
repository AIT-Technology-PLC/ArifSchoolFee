<?php

namespace App\DataTables;

use App\Models\Price;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PriceDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product', fn($price) => $price->product->name)
            ->editColumn('fixed_price', fn($price) => $price->fixed_price ? money($price->fixed_price) : '-')
            ->editColumn('min_price', fn($price) => $price->min_price ? money($price->min_price) : '-')
            ->editColumn('max_price', fn($price) => $price->max_price ? money($price->max_price) : '-')
            ->editColumn('last update date', fn($price) => $price->updated_at->toDayDateTimeString())
            ->editColumn('prepared by', fn($price) => $price->createdBy->name)
            ->editColumn('edited by', fn($price) => $price->updatedBy->name)
            ->editColumn('actions', function ($credit) {
                return view('components.common.action-buttons', [
                    'model' => 'prices',
                    'id' => $credit->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Price $price)
    {
        return $price
            ->newQuery()
            ->select('prices.*')
            ->with([
                'product:id,name',
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('product', 'product.name')->addClass('is-capitalized'),
            Column::make('type', 'type')->addClass('is-capitalized'),
            Column::make('fixed_price', 'fixed_price'),
            Column::make('min_price', 'min_price'),
            Column::make('max_price', 'max_price'),
            Column::make('last update date', 'updated_at')->visible(false),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'Price_' . date('YmdHis');
    }
}
