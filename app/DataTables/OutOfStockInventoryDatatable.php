<?php

namespace App\DataTables;

use App\Services\Inventory\MerchandiseProductService;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OutOfStockInventoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    private $warehouses;

    private $service;

    public function __construct()
    {
        $this->warehouses = authUser()->getAllowedWarehouses('read');

        $this->service = new MerchandiseProductService;
    }

    public function dataTable($query)
    {
        return $this
            ->editWarehouses(datatables()->collection($query->all()))
            ->editColumn('product', fn($row) => $row['product'])
            ->editColumn('code', fn($row) => $row['code'] ?? 'N/A')
            ->editColumn('description', fn($row) => view('components.datatables.searchable-description', ['description' => $row['description']]))
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
        if (authUser()->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        $outOfStockProducts = $this->service
            ->getOutOfStockMerchandiseProductsQuery(user:authUser())->with('productCategory')
            ->when(request('type') == 'finished goods', fn($query) => $query->where('products.type', '=', 'Finished Goods'))
            ->when(request('type') == 'raw material', fn($query) => $query->where('products.type', '=', 'Raw Material'))
            ->get();

        $organizedoutOfStockProducts = collect();

        foreach ($outOfStockProducts as $outOfStockProduct) {
            $organizedoutOfStockProducts->push([
                'product' => $outOfStockProduct->name,
                'code' => $outOfStockProduct->code ?? '',
                'type' => $outOfStockProduct->type,
                'description' => $outOfStockProduct->description,
                'product_id' => $outOfStockProduct->id,
                'category' => $outOfStockProduct->productCategory->name,
            ]);
        }

        return $organizedoutOfStockProducts;
    }

    protected function getColumns()
    {
        $warehouses = $this->warehouses->pluck('name');

        return collect([
            '#' => [
                'sortable' => false,
            ],
            'product',
            'code',
            isFeatureEnabled('Job Management') ? 'type' : null,
            'category',
            ...$warehouses,
        ])
            ->push(Column::make('description')->visible(false))
            ->filter()
            ->toArray();
    }

    protected function filename()
    {
        return 'InventoryLevel_' . date('YmdHis');
    }
}
