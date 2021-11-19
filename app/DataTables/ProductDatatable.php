<?php

namespace App\DataTables;

use App\Models\Product;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('category', function ($product) {
                return $product->productCategory->name;
            })
            ->editColumn('supplier', function ($product) {
                return $product->supplier->company_name ?? 'N/A';
            })
            ->editColumn('description', function ($product) {
                return $product->description ?? 'N/A';
            })
            ->editColumn('min_on_hand', function ($product) {
                return Str::of($product->min_on_hand ?? 0.0)->append(' ', $product->unit_of_measurement);
            })
            ->editColumn('added by', function ($product) {
                return $product->createdBy->name;
            })
            ->editColumn('edited by', function ($product) {
                return $product->updatedBy->name;
            })
            ->editColumn('actions', function ($product) {
                return view('components.common.action-buttons', [
                    'model' => 'products',
                    'id' => $product->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->rawColumns(['actions'])
            ->addIndexColumn();
    }

    public function query(Product $product)
    {
        return $product
            ->newQuery()
            ->select('products.*')
            ->with([
                'productCategory:id,name',
                'createdBy:id,name',
                'updatedBy:id,name',
                'supplier:id,company_name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->title('Product'),
            Column::make('code')->className('text-purple has-text-weight-medium'),
            Column::make('category', 'productCategory.name'),
            Column::make('type')->visible(false),
            Column::make('supplier', 'supplier.company_name')->visible(false),
            Column::make('description')->visible(false),
            Column::make('min_on_hand')->title('Reorder Level'),
            Column::make('added by', 'createdBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions'),
        ];

    }

    protected function filename()
    {
        return 'Product_' . date('YmdHis');
    }
}
