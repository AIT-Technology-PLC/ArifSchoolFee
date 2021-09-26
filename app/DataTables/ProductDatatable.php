<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDatatable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product', function ($product) {
                return $product->name;
            })
            ->editColumn('category', function ($product) {
                return $product->productCategory->name;
            })
            ->editColumn('supplier', function ($product) {
                return $product->supplier->company_name ?? 'N/A';
            })
            ->editColumn('description', function ($product) {
                return $product->description ?? 'N/A';
            })
            ->editColumn('reorder level', function ($product) {
                return $product->min_on_hand ?? 0.0;
            })
            ->editColumn('added by', function ($product) {
                return $product->createdBy->name;
            })
            ->editColumn('edited by', function ($product) {
                return $product->updatedBy->name;
            })
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

    public function html()
    {
        return $this->builder()
            ->responsive(true)
            ->scrollX(true)
            ->scrollY('500px')
            ->scrollCollapse(true)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('lBfrtip')
            ->lengthMenu([
                [10, 25, 50, 75, 100, -1],
                [10, 25, 50, 75, 100, "All"],
            ])
            ->buttons([
                'colvis', 'excelHtml5', 'print', 'pdfHtml5',
            ])
            ->addTableClass('display is-hoverable is-size-7 nowrap')
            ->preDrawCallback("
                function(settings){
                    changeDtButton();
                    $('table').css('display', 'table');
                    removeDtSearchLabel();
                }
            ")
            ->orderBy(1, 'asc');
    }

    protected function getColumns()
    {
        return [
            Column::make('#')->searchable(false)->orderable(false),
            Column::make('product', 'name'),
            Column::make('category', 'productCategory.name'),
            Column::make('type'),
            Column::make('code'),
            Column::make('supplier', 'supplier.company_name'),
            Column::make('description'),
            Column::make('reorder level', 'min_on_hand'),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name'),
        ];

    }

    protected function filename()
    {
        return 'Product_' . date('YmdHis');
    }
}
