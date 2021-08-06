<?php

namespace App\DataTables;

use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class OutOfStockInventoryDatatable extends DataTable
{
    public function __construct()
    {
        $this->warehouses = (new Warehouse())->getAllWithoutRelations();
    }

    public function dataTable($query)
    {
        $datatable = datatables()->collection($query->all());

        $this->warehouses->each(function ($warehouse) use ($datatable) {

            $datatable->addColumn($warehouse->name, function ($row) use ($warehouse) {
                return "
                    <a href='/warehouses/" . $warehouse->id . "/products/" . $row['product_id'] . "'" . "data-title='View Product History'>
                        <span class='tag is-small btn-green is-outlined'> Track History </span>
                    </a>
                ";
            });

        });

        return $datatable
            ->rawColumns([
                ...$this->warehouses->pluck('name')->toArray(),
            ])
            ->addIndexColumn();
    }

    public function query()
    {
        $outOfStockMerchandises = DB::table('merchandises')
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('warehouses', 'merchandises.warehouse_id', '=', 'warehouses.id')
            ->where('merchandises.company_id', '=', userCompany()->id)
            ->where('merchandises.reserved', '=', 0)
            ->where('merchandises.available', '=', 0)
            ->select([
                'products.id as product_id',
                'products.name as product',
                'product_categories.name as category',
                'warehouses.name as warehouse',
            ])
            ->get()
            ->unique();

        $organizedoutOfStockMerchandise = collect();

        foreach ($outOfStockMerchandises as $outOfStockMerchandise) {
            $organizedoutOfStockMerchandise->push([
                'product' => $outOfStockMerchandise->product,
                'product_id' => $outOfStockMerchandise->product_id,
                'category' => $outOfStockMerchandise->category,
            ]);
        }

        return $organizedoutOfStockMerchandise;
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
            ->setTableId('inventoryleveldatatable-table')
            ->addTableClass('display is-hoverable is-size-7 nowrap')
            ->preDrawCallback("
                function(settings){
                    changeDtButton();
                    $('table').css('display', 'table')
                }
            ")
            ->orderBy(1, 'asc');
    }

    protected function getColumns()
    {
        $warehouses = $this->warehouses->pluck('name');

        return [
            '#' => [
                'sortable' => false,
            ],
            'product',
            'category',
            ...$warehouses,
        ];

    }

    protected function filename()
    {
        return 'InventoryLevel_' . date('YmdHis');
    }
}
