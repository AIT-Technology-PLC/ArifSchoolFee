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
            ->editColumn('brand', function ($product) {
                return $product->brand->name ?? 'N/A';
            })
            ->editColumn('description', fn($product) => view('components.datatables.searchable-description', ['description' => $product->description]))
            ->editColumn('min_on_hand', function ($product) {
                return Str::of($product->min_on_hand ?? 0.0)->append(' ', $product->unit_of_measurement);
            })
            ->editColumn('tax_type', function ($product) {
                return $product->tax->type;
            })
            ->editColumn('is_batchable', fn($product) => $product->is_batchable ? 'Yes' : 'No')
            ->editColumn('used_for_sale', fn($product) => $product->is_active_for_sale ? 'Yes' : 'No')
            ->editColumn('used_for_purchase', fn($product) => $product->is_active_for_purchase ? 'Yes' : 'No')
            ->editColumn('used_for_job', fn($product) => $product->is_active_for_job ? 'Yes' : 'No')
            ->editColumn('inventory_valuation_method', fn($product) => $product->inventory_valuation_method ?? 'N/A')
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
                'brand:id,name',
                'tax:id,type',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->title('Product'),
            Column::make('lifo_unit_cost'),
            Column::make('fifo_unit_cost'),
            Column::make('average_unit_cost'),
            Column::make('code')->className('text-purple has-text-weight-medium')->content('N/A'),
            Column::make('category', 'productCategory.name'),
            Column::make('type'),
            Column::make('supplier', 'supplier.company_name')->visible(false),
            Column::make('brand', 'brand.name')->visible(false),
            Column::make('description')->visible(false),
            Column::make('min_on_hand')->title('Reorder Level'),
            Column::make('tax_type', 'tax.type')->visible(false),
            Column::make('inventory_valuation_method')->visible(false),
            Column::make('is_batchable')->searchable(false)->addClass('has-text-centered')->visible(false),
            Column::make('used_for_sale', 'is_active_for_sale')->searchable(false)->addClass('has-text-centered')->visible(false),
            Column::make('used_for_purchase', 'is_active_for_purchase')->searchable(false)->addClass('has-text-centered')->visible(false),
            Column::make('used_for_job', 'is_active_for_job')->searchable(false)->addClass('has-text-centered')->visible(false),
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
