<?php

namespace App\DataTables;

use App\Models\Brand;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BrandDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('products', fn($brand) => $brand->products_count)
            ->editColumn('added on', fn($brand) => $brand->created_at->toFormattedDateString())
            ->editColumn('created by', fn($brand) => $brand->createdBy->name)
            ->editColumn('edited by', fn($brand) => $brand->updatedBy->name)
            ->editColumn('actions', function ($brand) {
                return view('components.common.action-buttons', [
                    'model' => 'brands',
                    'id' => $brand->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Brand $brand)
    {
        return $brand
            ->newQuery()
            ->select('brands.*')
            ->withCount('products')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->addClass('text-green has-text-weight-bold'),
            Column::computed('products')->className('has-text-centered'),
            Column::make('description')->visible(false),
            Column::make('added on', 'created_at')->className('has-text-right'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Brand_' . date('YmdHis');
    }
}
