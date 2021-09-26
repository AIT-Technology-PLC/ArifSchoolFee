<?php

namespace App\DataTables;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class OutOfStockInventoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function __construct()
    {
        $this->warehouses = Warehouse::orderBy('name')->get(['id', 'name']);
    }

    public function dataTable($query)
    {
        $datatable = datatables()->collection($query->all());

        $datatable->editColumn('product', function ($row) {
            if ($row['code']) {
                return $row['product'] . "<span class='has-text-grey has-has-text-weight-bold'> - " . $row['code'] . "</span>";
            }

            return $row['product'];
        });

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
                'product',
            ])
            ->addIndexColumn();
    }

    public function query()
    {
        $onHandMerchandiseProducts = (new Merchandise())->getAllOnHand()->load('product')->pluck('product')->unique();

        $outOfStockProducts = (new Product())->getOutOfStockMerchandiseProducts($onHandMerchandiseProducts)->load('productCategory');

        $organizedoutOfStockProducts = collect();

        foreach ($outOfStockProducts as $outOfStockProduct) {
            $organizedoutOfStockProducts->push([
                'product' => $outOfStockProduct->name,
                'code' => $outOfStockProduct->code ?? '',
                'product_id' => $outOfStockProduct->id,
                'category' => $outOfStockProduct->productCategory->name,
            ]);
        }

        return $organizedoutOfStockProducts;
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
