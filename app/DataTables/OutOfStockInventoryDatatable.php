<?php

namespace App\DataTables;

use App\Services\Inventory\MerchandiseProductService;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class OutOfStockInventoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    private $warehouses, $service;

    public function __construct()
    {
        $this->warehouses = auth()->user()->getAllowedWarehouses('read');

        $this->service = new MerchandiseProductService;
    }

    public function dataTable($query)
    {
        return $this
            ->editWarehouses(datatables()->collection($query->all()))
            ->editColumn('product', function ($row) {
                return view('components.datatables.product-code', [
                    'product' => $row['product'],
                    'code' => $row['code'],
                ]);
            })
            ->rawColumns([
                ...$this->warehouses->pluck('name')->toArray(),
                'product',
            ])
            ->addIndexColumn();
    }

    private function editWarehouses($datatable)
    {
        $this->warehouses->each(function ($warehouse) use ($datatable) {
            $datatable->addColumn($warehouse->name, function ($row) use ($warehouse) {
                return view('components.datatables.history-link', [
                    'productId' => $row['product_id'],
                    'warehouseId' => $warehouse->id,
                ]);
            });
        });

        return $datatable;
    }

    public function query()
    {
        if (auth()->user()->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        $outOfStockProducts = $this->service->getOutOfStockMerchandiseProductsQuery(user:auth()->user())->with('productCategory')->get();

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
