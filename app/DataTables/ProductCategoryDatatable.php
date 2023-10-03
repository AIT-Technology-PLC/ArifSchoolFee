<?php

namespace App\DataTables;

use App\Models\ProductCategory;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductCategoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('products', fn($category) => $category->products_count)
            ->editColumn('added on', fn($category) => $category->created_at->toFormattedDateString())
            ->editColumn('created by', fn($category) => $category->createdBy->name)
            ->editColumn('edited by', fn($category) => $category->updatedBy->name)
            ->editColumn('actions', function ($category) {
                return view('components.common.action-buttons', [
                    'model' => 'categories',
                    'id' => $category->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(ProductCategory $category)
    {
        return $category
            ->newQuery()
            ->select('product_categories.*')
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
            Column::make('added on', 'created_at')->className('has-text-right'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Product Categories_' . date('YmdHis');
    }
}
